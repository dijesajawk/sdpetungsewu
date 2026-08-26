<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Kelas;
use App\Models\Kategori;
use App\Models\SoalSession;
use App\Models\SoalSessionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    /**
     * Tampilkan dashboard guru - soal terorganisir per difficulty level
     */
    public function dashboard()
    {
        $soalCount = Soal::where('user_id', Auth::id())->count();
        $kelas = Kelas::all();
        
        // Group soal per difficulty level
        $soalMudah = Soal::where('user_id', Auth::id())
                         ->where('difficulty_level', 'mudah')
                         ->with(['kelas', 'kategori'])
                         ->get();
        
        $soalNormal = Soal::where('user_id', Auth::id())
                          ->where('difficulty_level', 'normal')
                          ->with(['kelas', 'kategori'])
                          ->get();
        
        $soalSulit = Soal::where('user_id', Auth::id())
                         ->where('difficulty_level', 'sulit')
                         ->with(['kelas', 'kategori'])
                         ->get();
        
        return view('guru.dashboard', compact('soalCount', 'kelas', 'soalMudah', 'soalNormal', 'soalSulit'));
    }

    /**
     * Tampilkan form buat soal
     */
    public function createSoal()
    {
        $kelas = Kelas::all();

        return view('guru.soal.create', compact('kelas'));
    }

    /**
     * Simpan soal baru
     */
    public function storeSoal(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'difficulty_level' => 'required|in:mudah,normal,sulit',
            'pertanyaan' => 'required|string|min:10',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban_benar' => 'required|in:a,b,c,d',
        ]);

        Soal::create([
            'user_id' => Auth::id(),
            'kelas_id' => $validated['kelas_id'],
            'kategori_id' => null,
            'difficulty_level' => $validated['difficulty_level'],
            'pertanyaan' => $validated['pertanyaan'],
            'pilihan_a' => $validated['pilihan_a'],
            'pilihan_b' => $validated['pilihan_b'],
            'pilihan_c' => $validated['pilihan_c'],
            'pilihan_d' => $validated['pilihan_d'],
            'jawaban_benar' => $validated['jawaban_benar'],
        ]);

        return redirect('/guru/soal')->with('success', 'Soal berhasil ditambahkan');
    }

    /**
     * Tampilkan daftar soal guru
     */
    public function listSoal()
    {
        $soal = Soal::where('user_id', Auth::id())
                    ->with(['kelas', 'kategori'])
                    ->paginate(10);
        
        return view('guru.soal.list', compact('soal'));
    }

    /**
     * Edit soal
     */
    public function editSoal(Soal $soal)
    {
        // Cek apakah soal milik guru login
        if ($soal->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke soal ini');
        }

        $kelas = Kelas::all();

        return view('guru.soal.edit', compact('soal', 'kelas'));
    }

    /**
     * Update soal
     */
    public function updateSoal(Request $request, Soal $soal)
    {
        // Cek apakah soal milik guru login
        if ($soal->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke soal ini');
        }

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'difficulty_level' => 'required|in:mudah,normal,sulit',
            'pertanyaan' => 'required|string|min:10',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'jawaban_benar' => 'required|in:a,b,c,d',
        ]);

        $soal->update($validated);
        return redirect('/guru/soal')->with('success', 'Soal berhasil diperbarui');
    }

    /**
     * Hapus soal
     */
    public function deleteSoal(Soal $soal)
    {
        // Cek apakah soal milik guru login
        if ($soal->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke soal ini');
        }

        $soal->delete();
        return back()->with('success', 'Soal berhasil dihapus');
    }

    /**
     * Tampilkan halaman mulai quiz (pilih kategori kesulitan)
     */
    public function startQuiz()
    {
        $kelas = Kelas::all();
        return view('guru.quiz.start', compact('kelas'));
    }

    /**
     * Create session dengan multiple soal berdasarkan kategori
     */
    public function createSession(Request $request)
    {
        $validated = $request->validate([
            'difficulty_type' => 'required|in:mudah,normal,sulit,campuran',
            'kategori_id' => 'nullable|exists:kategori,id',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        // Tentukan difficulty levels yang akan diambil
        $difficultyLevels = [];
        if ($validated['difficulty_type'] === 'campuran') {
            $difficultyLevels = ['mudah', 'normal', 'sulit'];
        } else {
            $difficultyLevels = [$validated['difficulty_type']];
        }

        // Query soal berdasarkan kategori, kelas, dan kesulitan
        $query = Soal::where('user_id', Auth::id())
                     ->whereIn('difficulty_level', $difficultyLevels);

        if (!empty($validated['kategori_id'] ?? null)) {
            $query->where('kategori_id', $validated['kategori_id']);
        }

        if (!empty($validated['kelas_id'] ?? null)) {
            $query->where('kelas_id', $validated['kelas_id']);
        }

        $soalList = $query->get()->toArray();

        if (empty($soalList)) {
            return back()->with('error', 'Tidak ada soal dengan filter yang dipilih');
        }

        // Jika campuran, random urutan soal
        if ($validated['difficulty_type'] === 'campuran') {
            shuffle($soalList);
        }

        // Buat session baru
        $session = SoalSession::create([
            'user_id' => Auth::id(),
            'session_code' => SoalSession::generateSessionCode(),
            'mulai_at' => now(),
            'status' => 'active',
            'difficulty_type' => $validated['difficulty_type'],
        ]);

        // Simpan soal ke session_items
        foreach ($soalList as $key => $soal) {
            SoalSessionItem::create([
                'session_id' => $session->id,
                'soal_id' => $soal['id'],
                'urutan' => $key + 1,
            ]);
        }

        return redirect()->route('guru.session.display', $session->id)
                        ->with('success', 'Quiz telah dimulai');
    }

    /**
     * Tampilkan soal langsung berdasarkan filter (tanpa membuat session)
     */
    public function createImmediate(Request $request)
    {
        $validated = $request->validate([
            'difficulty_type' => 'required|in:mudah,normal,sulit,campuran',
            'kategori_id' => 'nullable|exists:kategori,id',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $difficultyLevels = [];
        if ($validated['difficulty_type'] === 'campuran') {
            $difficultyLevels = ['mudah', 'normal', 'sulit'];
        } else {
            $difficultyLevels = [$validated['difficulty_type']];
        }

        $query = Soal::where('user_id', Auth::id())
                     ->whereIn('difficulty_level', $difficultyLevels);

        if (!empty($validated['kategori_id'] ?? null)) {
            $query->where('kategori_id', $validated['kategori_id']);
        }

        if (!empty($validated['kelas_id'] ?? null)) {
            $query->where('kelas_id', $validated['kelas_id']);
        }

        $soalList = $query->with(['kelas', 'kategori'])->get();

        if ($soalList->isEmpty()) {
            return back()->with('error', 'Tidak ada soal dengan filter yang dipilih');
        }

        // Shuffle questions so each run presents random order
        $soalList = $soalList->shuffle();

        // Simpan daftar soal dan posisi di session (flow immediate, terpisah dari SoalSession)
        $sessionKey = 'guru_immediate_' . Auth::id();
        session([$sessionKey => [
            'ids' => $soalList->pluck('id')->toArray(),
            'index' => 0,
            'difficulty_type' => $validated['difficulty_type'],
        ]]);

        return redirect()->route('guru.quiz.immediate.show');
    }

    /**
     * Tampilkan satu soal dari flow immediate
     */
    public function showImmediate()
    {
        $sessionKey = 'guru_immediate_' . Auth::id();
        $data = session($sessionKey);

        if (empty($data) || empty($data['ids'])) {
            return redirect()->route('guru.quiz.start')->with('error', 'Tidak ada soal aktif. Silakan pilih filter dan mulai lagi.');
        }

        $ids = $data['ids'];
        $index = $data['index'];

        if (!isset($ids[$index])) {
            // selesai
            session()->forget($sessionKey);
            return view('guru.quiz.immediate_finished');
        }

        $soalId = $ids[$index];
        $soal = Soal::with(['kelas', 'kategori'])->findOrFail($soalId);

        return view('guru.quiz.immediate_one', [
            'soal' => $soal,
            'index' => $index,
            'total' => count($ids),
        ]);
    }

    /**
     * Proses jawaban untuk flow immediate
     */
    public function answerImmediate(Request $request)
    {
        $request->validate([
            'jawaban' => 'required|in:a,b,c,d',
        ]);

        $sessionKey = 'guru_immediate_' . Auth::id();
        $data = session($sessionKey);

        if (empty($data) || empty($data['ids'])) {
            return redirect()->route('guru.quiz.start')->with('error', 'Sesi sudah berakhir atau tidak valid.');
        }

        $ids = $data['ids'];
        $index = $data['index'];

        if (!isset($ids[$index])) {
            session()->forget($sessionKey);
            return redirect()->route('guru.quiz.start')->with('success', 'Semua soal telah diselesaikan');
        }

        $soalId = $ids[$index];
        $soal = Soal::findOrFail($soalId);

        $jawaban = $request->input('jawaban');

        if ($jawaban === $soal->jawaban_benar) {
            // benar -> maju ke soal berikutnya
            $data['index'] = $index + 1;
            session([$sessionKey => $data]);

            // jika sudah melewati terakhir, selesaikan
            if (!isset($ids[$data['index']])) {
                session()->forget($sessionKey);
                return redirect()->route('guru.quiz.start')->with('success', 'Semua soal telah diselesaikan');
            }

            return redirect()->route('guru.quiz.immediate.show')->with('success', 'Benar! Lanjut ke soal berikutnya.');
        }

        // salah -> tetap di soal yang sama
        return redirect()->route('guru.quiz.immediate.show')->with('error', 'Jawaban salah. Coba lagi.');
    }

    /**
     * Exit immediate flow and clear stored immediate session data
     */
    public function exitImmediate(Request $request)
    {
        $sessionKey = 'guru_immediate_' . Auth::id();
        session()->forget($sessionKey);

        return redirect()->route('guru.dashboard')->with('success', 'Flow soal langsung dibatalkan. Silakan pilih kesulitan dan kelas lagi saat ingin memulai.');
    }

    /**
     * Reshuffle remaining questions in immediate flow
     */
    public function reshuffleImmediate(Request $request)
    {
        $sessionKey = 'guru_immediate_' . Auth::id();
        $data = session($sessionKey);

        if (empty($data) || empty($data['ids'])) {
            return redirect()->route('guru.quiz.start')->with('error', 'Tidak ada soal untuk diacak.');
        }

        $ids = $data['ids'];
        $index = $data['index'] ?? 0;

        $before = array_slice($ids, 0, $index);
        $remaining = array_slice($ids, $index);

        shuffle($remaining);

        $newIds = array_merge($before, $remaining);

        session([$sessionKey => [
            'ids' => $newIds,
            'index' => $index,
            'difficulty_type' => $data['difficulty_type'] ?? null,
        ]]);

        return redirect()->route('guru.quiz.immediate.show')->with('success', 'Daftar soal telah diacak ulang');
    }

    /**
     * Tampilkan session quiz (info kode dan link)
     */
    public function displaySession($sessionId)
    {
        $session = SoalSession::with('items.soal')->findOrFail($sessionId);

        if ($session->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke sesi ini');
        }

        $totalSoal = $session->items()->count();

        return view('guru.quiz.display-session', compact('session', 'totalSoal'));
    }

    /**
     * Tampilkan informasi session soal (untuk guru lihat hasil)
     */
    public function sessionInfo($sessionId)
    {
        $session = SoalSession::with(['items.soal', 'jawaban'])->findOrFail($sessionId);

        // Cek apakah session milik guru login
        if ($session->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke sesi ini');
        }

        $soalList = $session->items()->with('soal')->orderBy('urutan')->get();
        $jawaban = $session->jawaban()->with('soal')->get();
        
        // Hitung statistik
        $totalJawab = $jawaban->count();
        $benarJawab = $jawaban->where('is_correct', true)->count();
        $persentase = $totalJawab > 0 ? round(($benarJawab / $totalJawab) * 100, 2) : 0;

        $peringkat = $jawaban->groupBy('identifier')
            ->map(function ($jawabanMurid) {
                $totalDijawab = $jawabanMurid->count();
                $jumlahBenar = $jawabanMurid->where('is_correct', true)->count();

                return [
                    'nama' => $jawabanMurid->first()->nama_murid ?: 'Murid',
                    'total_dijawab' => $totalDijawab,
                    'benar' => $jumlahBenar,
                    'persentase' => $totalDijawab > 0 ? round(($jumlahBenar / $totalDijawab) * 100, 2) : 0,
                ];
            })
            ->sortByDesc(function ($hasil) {
                return [$hasil['benar'], $hasil['persentase'], $hasil['total_dijawab']];
            })
            ->values();

        return view('guru.quiz.session-info', compact('session', 'soalList', 'jawaban', 'totalJawab', 'benarJawab', 'persentase', 'peringkat'));
    }

    /**
     * Akhiri/tutup session soal
     */
    public function endSession($sessionId)
    {
        $session = SoalSession::findOrFail($sessionId);

        // Cek apakah session milik guru login
        if ($session->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke sesi ini');
        }

        $session->update([
            'status' => 'completed',
            'selesai_at' => now(),
        ]);

        return redirect()->route('guru.dashboard')->with('success', 'Sesi soal telah ditutup');
    }

    /**
     * Tampilkan halaman scan/upload soal
     */
    public function showScanUpload()
    {
        $kelas = Kelas::all();

        return view('guru.soal.scan-upload', compact('kelas'));
    }

    /**
     * Proses gambar soal menggunakan OCR (mendukung multiple gambar)
     */
    public function processScanImage(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $imagePaths = [];
        $imageUrls = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('temp-ocr', 'public');
            $imagePaths[] = $path;
            $imageUrls[] = asset('storage/' . $path);
        }

        return view('guru.soal.scan-review', [
            'imageUrls' => $imageUrls,
            'imagePaths' => $imagePaths,
            'kelas_id' => $request->kelas_id,
            'kelas' => Kelas::all(),
        ]);
    }

    /**
     * Tampilkan halaman review OCR
     */
    public function showScanReview()
    {
        return redirect()->route('guru.soal.scan.upload')->with('error', 'Tidak ada gambar yang diupload. Silakan upload gambar terlebih dahulu.');
    }

    /**
     * Simpan hasil scan ke database
     */
    public function saveScanResult(Request $request)
    {
        $validated = $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.pertanyaan' => 'required|string|min:5',
            'questions.*.pilihan_a' => 'required|string',
            'questions.*.pilihan_b' => 'required|string',
            'questions.*.pilihan_c' => 'nullable|string',
            'questions.*.pilihan_d' => 'nullable|string',
            'questions.*.jawaban_benar' => 'required|in:a,b,c,d',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $saved_count = 0;

        foreach ($validated['questions'] as $question) {
            Soal::create([
                'user_id' => Auth::id(),
                'kelas_id' => $validated['kelas_id'],
                'difficulty_level' => 'normal',
                'pertanyaan' => $question['pertanyaan'],
                'pilihan_a' => $question['pilihan_a'],
                'pilihan_b' => $question['pilihan_b'],
                'pilihan_c' => $question['pilihan_c'] ?? '-',
                'pilihan_d' => $question['pilihan_d'] ?? '-',
                'jawaban_benar' => $question['jawaban_benar'],
            ]);
            $saved_count++;
        }

        // Hapus gambar temporary
        if ($request->has('imagePaths') && is_array($request->imagePaths)) {
            foreach ($request->imagePaths as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        return redirect('/guru/soal')->with('success', "$saved_count soal berhasil ditambahkan dari scan!");
    }
}
