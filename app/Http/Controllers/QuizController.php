<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\SoalSession;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    /**
     * Tampilkan form input nama murid sebelum quiz
     */
    public function showNameInput($sessionCode)
    {
        $session = SoalSession::with('items.soal')->where('session_code', $sessionCode)->firstOrFail();
        
        if (!$session->isActive()) {
            return view('quiz.session-ended', compact('session'));
        }

        $totalSoal = $session->items()->count();
        
        return view('quiz.name-input', compact('session', 'sessionCode', 'totalSoal'));
    }

    /**
     * Tampilkan quiz interface tanpa login (multiple soal)
     */
    public function quiz($sessionCode, Request $request)
    {
        $session = SoalSession::with('items.soal')->where('session_code', $sessionCode)->firstOrFail();
        
        if (!$session->isActive()) {
            return view('quiz.session-ended', compact('session'));
        }

        $namurid = $request->query('nama') ?? session('quiz_nama_murid');
        
        if (!$namurid) {
            return redirect()->route('quiz.name-input', ['sessionCode' => $sessionCode]);
        }

        // Store nama di session untuk persistence
        session(['quiz_nama_murid' => $namurid]);

        // Ambil soal list
        $soalList = $session->items()->with('soal')->orderBy('urutan')->get();
        
        if ($soalList->isEmpty()) {
            return back()->with('error', 'Tidak ada soal dalam session ini');
        }

        // Ambil soal index dari query (default 0)
        $soalIndex = (int) $request->query('soal', 0);
        
        // Pastikan index valid
        if ($soalIndex < 0 || $soalIndex >= $soalList->count()) {
            $soalIndex = 0;
        }

        // Ambil soal saat ini
        $currentItem = $soalList[$soalIndex];
        $soal = $currentItem->soal;

        // Cek apakah murid sudah menjawab soal ini
        $identifier = $this->getIdentifier();
        $jawabanSebelumnya = Jawaban::where('session_id', $session->id)
                                     ->where('soal_id', $soal->id)
                                     ->where('identifier', $identifier)
                                     ->first();
        
        // Hitung progress
        $totalSoal = $soalList->count();
        $soalKeberapa = $soalIndex + 1;
        $progressPersen = round(($soalKeberapa / $totalSoal) * 100);

        return view('quiz.student-quiz', compact('session', 'soal', 'namurid', 'jawabanSebelumnya', 'soalIndex', 'totalSoal', 'soalKeberapa', 'progressPersen', 'soalList'));
    }

    /**
     * Simpan jawaban dari murid (tanpa login)
     */
    public function submitAnswer(Request $request, $sessionCode)
    {
        $validated = $request->validate([
            'soal_id' => 'required|exists:soal,id',
            'jawaban_dipilih' => 'required|in:a,b,c,d',
            'nama_murid' => 'required|string|max:100',
        ]);
        
        $session = SoalSession::where('session_code', $sessionCode)->firstOrFail();
        
        if (!$session->isActive()) {
            return response()->json(['error' => 'Sesi soal sudah ditutup'], 403);
        }

        $soal = Soal::findOrFail($validated['soal_id']);
        $isCorrect = $validated['jawaban_dipilih'] === $soal->jawaban_benar;
        $identifier = $this->getIdentifier();

        // Simpan jawaban
        $jawaban = Jawaban::updateOrCreate(
            [
                'session_id' => $session->id,
                'soal_id' => $soal->id,
                'identifier' => $identifier,
            ],
            [
                'nama_murid' => $validated['nama_murid'],
                'jawaban_dipilih' => $validated['jawaban_dipilih'],
                'is_correct' => $isCorrect,
                'waktu_selesai' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'jawaban_benar' => $soal->jawaban_benar,
        ]);
    }

    /**
     * Tampilkan hasil quiz (per soal atau final)
     */
    public function result($sessionCode, Request $request)
    {
        $session = SoalSession::with('items.soal')->where('session_code', $sessionCode)->firstOrFail();
        $namurid = session('quiz_nama_murid') ?? 'Siswa';
        $identifier = $this->getIdentifier();
        
        // Ambil semua jawaban murid dalam session ini
        $jawabanList = Jawaban::where('session_id', $session->id)
                              ->where('identifier', $identifier)
                              ->with('soal')
                              ->get();

        // Hitung statistik
        $totalSoal = $session->items()->count();
        $totalJawab = $jawabanList->count();
        $benarJawab = $jawabanList->where('is_correct', true)->count();
        $skorFinal = $totalJawab > 0 ? round(($benarJawab / $totalJawab) * 100) : 0;

        return view('quiz.result', compact('session', 'jawabanList', 'namurid', 'totalSoal', 'totalJawab', 'benarJawab', 'skorFinal'));
    }

    /**
     * Get identifier unik untuk murid (berbasis session/device)
     */
    private function getIdentifier(): string
    {
        if (!session()->has('quiz_identifier')) {
            session(['quiz_identifier' => Str::uuid()]);
        }
        
        return session('quiz_identifier');
    }
}
