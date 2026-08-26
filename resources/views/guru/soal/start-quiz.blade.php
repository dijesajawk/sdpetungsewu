@extends('layouts.app')

@section('title', 'Mulai Quiz - ' . $soal->pertanyaan)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-2">✓ Sesi Soal Dibuka</h1>
        <p class="text-lg opacity-90">Soal siap dijawab oleh murid-murid Anda</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 space-y-6">
        <!-- Info Soal -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">📝 Info Soal</h2>
            <div class="space-y-3 text-gray-700">
                <p>
                    <span class="font-semibold">Pertanyaan:</span><br>
                    <span class="text-lg">{{ $soal->pertanyaan }}</span>
                </p>
                <p>
                    <span class="font-semibold">Kelas:</span> 
                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                        {{ $soal->kelas->nama_kelas }}
                    </span>
                </p>
                <p>
                    <span class="font-semibold">Kategori:</span> 
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                        {{ ucfirst($soal->kategori->nama_kategori ?? 'Tanpa kategori') }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Kode Akses -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">🔑 Kode Akses Murid</h2>
            <div class="bg-gray-100 rounded-lg p-6 border-2 border-gray-300 text-center">
                <p class="text-gray-600 text-sm mb-2">Bagikan kode ini ke murid Anda:</p>
                <p class="text-5xl font-bold text-blue-600 tracking-widest select-all">
                    {{ $session->session_code }}
                </p>
            </div>
        </div>

        <!-- Link Akses -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">🔗 Link Akses (QR/Copy)</h2>
            <div class="bg-gray-100 rounded-lg p-6 space-y-4">
                <p class="text-gray-600 text-sm">Atau bagikan link ini langsung ke murid:</p>
                <input type="text" 
                    id="quizLink" 
                    value="{{ route('quiz.name-input', ['sessionCode' => $session->session_code]) }}" 
                    class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 font-mono text-sm"
                    readonly>
                <button onclick="copyToClipboard(event)" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-semibold">
                    📋 Salin Link
                </button>
            </div>
        </div>

        <!-- Instruksi -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded">
            <h3 class="font-bold text-blue-900 mb-3 text-lg">📋 Instruksi untuk Murid:</h3>
            <ol class="list-decimal list-inside space-y-2 text-blue-800">
                <li>Buka link akses atau masukkan kode <strong>{{ $session->session_code }}</strong></li>
                <li>Masukkan nama lengkap murid</li>
                <li>Baca soal dengan teliti</li>
                <li>Pilih jawaban yang paling tepat</li>
                <li>Lihat hasil jawaban</li>
            </ol>
        </div>

        <!-- Status Session -->
        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded">
            <p class="text-green-800">
                <span class="font-bold">Status:</span> 
                <span class="inline-block bg-green-200 text-green-800 px-3 py-1 rounded-full font-semibold">
                    🟢 AKTIF
                </span>
            </p>
            <p class="text-green-700 text-sm mt-2">Sesi dibuka pada: {{ $session->mulai_at->format('d M Y H:i:s') }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3 pt-6 border-t">
            <a href="{{ route('guru.session.info', $session) }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition text-center font-semibold">
                📊 Lihat Hasil Jawaban Murid
            </a>
            <form action="{{ route('guru.session.end', $session) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition font-semibold"
                    onclick="return confirm('Yakin ingin menutup sesi soal ini?')">
                    ✕ Tutup Sesi
                </button>
            </form>
            <a href="{{ route('guru.soal.list') }}" 
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition text-center font-semibold">
                ← Kembali ke Daftar Soal
            </a>
        </div>
    </div>
</div>

<script>
function copyToClipboard(e) {
    const link = document.getElementById('quizLink');
    link.select();
    document.execCommand('copy');
    
    // Show feedback
    const button = e.target;
    const originalText = button.textContent;
    button.textContent = '✓ Disalin!';
    setTimeout(() => {
        button.textContent = originalText;
    }, 2000);
}
</script>
@endsection
