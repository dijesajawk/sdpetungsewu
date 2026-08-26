<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Kelas;
use App\Models\Kategori;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Tampilkan dashboard siswa
     */
    public function dashboard()
    {
        $kelas = Kelas::all();
        $kategori = Kategori::all();
        $totalSoalJawab = Jawaban::where('user_id', Auth::id())->count();
        $benarJawab = Jawaban::where('user_id', Auth::id())->where('is_correct', true)->count();
        $skorRataRata = $totalSoalJawab > 0 ? ($benarJawab / $totalSoalJawab) * 100 : 0;
        
        return view('student.dashboard', compact('kelas', 'kategori', 'totalSoalJawab', 'benarJawab', 'skorRataRata'));
    }

    /**
     * Tampilkan daftar soal berdasarkan kelas/kategori
     */
    public function listSoal(Request $request)
    {
        $kelasId = $request->query('kelas_id');
        $kategoriId = $request->query('kategori_id');
        
        $query = Soal::query();
        
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }
        
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }
        
        $soal = $query->with(['guru', 'kelas', 'kategori'])->paginate(12);
        
        $kelas = Kelas::all();
        $kategori = Kategori::all();
        $selectedKelas = $kelasId;
        $selectedKategori = $kategoriId;
        
        return view('student.soal.list', compact('soal', 'kelas', 'kategori', 'selectedKelas', 'selectedKategori'));
    }

    /**
     * Tampilkan interface quiz
     */
    public function quiz($id)
    {
        $soal = Soal::with(['guru', 'kelas', 'kategori'])->find($id);
        
        if (!$soal) {
            return back()->with('error', 'Soal tidak ditemukan');
        }
        
        // Cek apakah siswa sudah pernah menjawab soal ini
        $jawabanSebelumnya = Jawaban::where('user_id', Auth::id())
                                     ->where('soal_id', $id)
                                     ->first();
        
        return view('student.soal.quiz', compact('soal', 'jawabanSebelumnya'));
    }

    /**
     * Simpan jawaban soal
     */
    public function submitJawaban(Request $request, $soalId)
    {
        $validated = $request->validate([
            'jawaban_dipilih' => 'required|in:a,b,c,d',
        ]);
        
        $soal = Soal::find($soalId);
        
        if (!$soal) {
            return response()->json(['error' => 'Soal tidak ditemukan'], 404);
        }
        
        $isCorrect = $validated['jawaban_dipilih'] === $soal->jawaban_benar;
        
        // Jika sudah pernah menjawab, update; jika belum, buat baru
        $jawaban = Jawaban::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'soal_id' => $soalId,
            ],
            [
                'jawaban_dipilih' => $validated['jawaban_dipilih'],
                'is_correct' => $isCorrect,
                'waktu_selesai' => now(),
            ]
        );
        
        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'jawaban_benar' => $soal->jawaban_benar,
            'message' => $isCorrect ? 'Jawaban Benar! 🎉' : 'Jawaban Salah! Coba Lagi! 📚',
        ]);
    }

    /**
     * Tampilkan hasil nilai
     */
    public function result(Request $request)
    {
        $kelasId = $request->query('kelas_id');
        $kategoriId = $request->query('kategori_id');

        $query = Jawaban::where('user_id', Auth::id())->with(['soal', 'soal.kelas', 'soal.kategori']);
        
        if ($kelasId) {
            $query->whereHas('soal', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }
        
        if ($kategoriId) {
            $query->whereHas('soal', function ($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }
        
        $jawaban = $query->get();
        
        $totalSoal = $jawaban->count();
        $benarJawab = $jawaban->where('is_correct', true)->count();
        $skor = $totalSoal > 0 ? ($benarJawab / $totalSoal) * 100 : 0;
        
        $kelas = Kelas::all();
        $kategori = Kategori::all();
        
        return view('student.soal.result', compact(
            'jawaban',
            'totalSoal',
            'benarJawab',
            'skor',
            'kelas',
            'kategori',
            'kelasId',
            'kategoriId'
        ));
    }
}

