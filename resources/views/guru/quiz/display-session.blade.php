@extends('layouts.app')

@section('title', 'Session Quiz Aktif')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <style>
        @keyframes heroFloat { 0%, 100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-8px) rotate(1deg); } }
        @keyframes livePulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, .5); } 50% { box-shadow: 0 0 0 10px rgba(52, 211, 153, 0); } }
        @keyframes codeShine { from { transform: translateX(-130%) rotate(18deg); } to { transform: translateX(250%) rotate(18deg); } }
        .quiz-hero-float { animation: heroFloat 4s ease-in-out infinite; }
        .quiz-live-pulse { animation: livePulse 2s ease-out infinite; }
        .quiz-code-shine { position: relative; overflow: hidden; }
        .quiz-code-shine::after { content: ''; position: absolute; inset: -60% auto -60% -20%; width: 25%; background: rgba(255,255,255,.35); transform: translateX(-130%) rotate(18deg); animation: codeShine 3.5s ease-in-out infinite; }
        @media (prefers-reduced-motion: reduce) { .quiz-hero-float, .quiz-live-pulse, .quiz-code-shine::after { animation: none; } }
    </style>
    @if($session->status === 'active')
        <div class="relative overflow-hidden bg-slate-950 text-white rounded-2xl p-8 md:p-10 shadow-xl">
            <div class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-cyan-400/20 blur-3xl"></div>
            <div class="relative flex items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 text-emerald-300 text-xs font-bold uppercase tracking-[.22em] mb-4"><span class="quiz-live-pulse h-2.5 w-2.5 rounded-full bg-emerald-400"></span> Live now</div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-2">Session Quiz Dibuka</h1>
                    <p class="text-slate-300">Peserta bisa masuk dan menjawab sekarang.</p>
                </div>
                <div class="quiz-hero-float text-6xl md:text-7xl"><x-icon name="rocket" /> </div>
            </div>
        </div>
    @else
        <div class="bg-slate-800 text-white rounded-2xl p-8 shadow-xl">
            <h1 class="text-3xl font-bold mb-2"><x-icon name="x" />  Session Quiz Ditutup</h1>
            <p class="text-slate-300">Sesi telah ditutup pada {{ optional($session->selesai_at)->format('d M Y H:i:s') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-8 space-y-6">
        <!-- Info Quiz -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4"><x-icon name="clipboard-list" />  Informasi Quiz</h2>
            <div class="space-y-3 text-gray-700">
                <p>
                    <span class="font-semibold">Total Soal:</span> 
                    <span class="text-lg font-bold text-blue-600">{{ $totalSoal }}</span>
                </p>
                <p>
                    <span class="font-semibold">Tingkat Kesulitan:</span> 
                    <span class="inline-block px-3 py-1 rounded-full font-semibold
                        @if($session->difficulty_type === 'mudah') bg-green-100 text-green-800
                        @elseif($session->difficulty_type === 'normal') bg-yellow-100 text-yellow-800
                        @elseif($session->difficulty_type === 'sulit') bg-red-100 text-red-800
                        @else bg-purple-100 text-purple-800
                        @endif">
                        @if($session->difficulty_type === 'mudah') <x-icon name="dot" class="text-emerald-400" />  Mudah
                        @elseif($session->difficulty_type === 'normal') <x-icon name="dot" class="text-amber-400" />  Normal
                        @elseif($session->difficulty_type === 'sulit') <x-icon name="dot" class="text-rose-400" />  Sulit
                        @else <x-icon name="shuffle" />  Campuran (Random)
                        @endif
                    </span>
                </p>
            </div>
        </div>

        <!-- Kode Akses -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4"><x-icon name="key" />  Kode Akses Murid</h2>
            <div class="quiz-code-shine bg-gradient-to-br from-cyan-50 to-blue-100 rounded-2xl p-6 border-2 border-cyan-200 text-center shadow-inner">
                <p class="text-gray-600 text-sm mb-2">Bagikan kode ini ke murid Anda:</p>
                <p class="text-5xl font-bold text-blue-600 tracking-widest select-all">
                    {{ $session->session_code }}
                </p>
            </div>
        </div>

        <!-- Link Akses -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4"><x-icon name="link" />  Link Akses</h2>
            <div class="bg-gray-100 rounded-lg p-6 space-y-4">
                <p class="text-gray-600 text-sm">Atau bagikan link ini langsung:</p>
                <input type="text" 
                    id="quizLink" 
                    value="{{ route('quiz.name-input', ['sessionCode' => $session->session_code]) }}" 
                    class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 font-mono text-sm"
                    readonly>
                <button onclick="copyToClipboard(event)" class="btn-premium btn-sm w-full">
                    <x-icon name="clipboard-list" />  Salin Link
                </button>
            </div>
        </div>

        <!-- Instruksi -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded">
            <h3 class="font-bold text-blue-900 mb-3 text-lg"><x-icon name="clipboard-list" />  Instruksi untuk Murid:</h3>
            <ol class="list-decimal list-inside space-y-2 text-blue-800">
                <li>Buka link akses atau masukkan kode <strong>{{ $session->session_code }}</strong></li>
                <li>Masukkan nama lengkap</li>
                <li>Jawab soal satu per satu</li>
                <li>Lihat hasil setiap soal</li>
                <li>Lanjut ke soal berikutnya atau selesai</li>
            </ol>
        </div>

        <!-- Status Session -->
        @if($session->status === 'active')
            <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded">
                <p class="text-green-800">
                    <span class="font-bold">Status:</span> 
                    <span class="inline-block bg-green-200 text-green-800 px-3 py-1 rounded-full font-semibold">
                        <x-icon name="dot" class="text-emerald-400" />  AKTIF
                    </span>
                </p>
                <p class="text-green-700 text-sm mt-2">Quiz dimulai: {{ $session->mulai_at->format('d M Y H:i:s') }}</p>
            </div>
        @else
            <div class="bg-gray-50 border-l-4 border-gray-300 p-6 rounded">
                <p class="text-gray-800">
                    <span class="font-bold">Status:</span> 
                    <span class="inline-block bg-gray-200 text-gray-800 px-3 py-1 rounded-full font-semibold">
                         SELESAI
                    </span>
                </p>
                <p class="text-gray-700 text-sm mt-2">Selesai: {{ optional($session->selesai_at)->format('d M Y H:i:s') }}</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3 pt-6 border-t">
            <a href="{{ route('guru.session.info', $session) }}" 
                class="btn-premium text-center">
                <x-icon name="bar-chart" />  Lihat Hasil Jawaban Murid (Real-time)
            </a>
            <form action="{{ route('guru.session.end', $session) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    class="btn-danger w-full"
                    onclick="return confirm('Yakin ingin menutup session quiz ini?')">
                    <x-icon name="x" />  Tutup Session
                </button>
            </form>
            <a href="{{ route('guru.dashboard') }}" 
                class="btn-soft text-center">
                ← Kembali ke Dashboard
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
    button.textContent = ' Disalin!';
    setTimeout(() => {
        button.textContent = originalText;
    }, 2000);
}
</script>
@endsection
