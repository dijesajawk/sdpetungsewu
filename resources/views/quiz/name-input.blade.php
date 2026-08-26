<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukkan Nama - Quiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <style>
        :root { --ink: #09112e; --blue: #2879f5; --cyan: #22d3ee; }
        @keyframes loginRise { from { opacity: 0; transform: translateY(26px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes loginDrift { 0%, 100% { transform: translate3d(0, 0, 0) rotate(0); } 50% { transform: translate3d(18px, -12px, 0) rotate(3deg); } }
        @keyframes loginSweep { from { transform: translateX(-120%) rotate(18deg); } to { transform: translateX(280%) rotate(18deg); } }
        @keyframes loginPulse { 0%, 100% { opacity: .45; transform: scale(1); } 50% { opacity: .9; transform: scale(1.08); } }
        * { box-sizing: border-box; }
        body.login-page { min-height: 100vh; margin: 0; overflow-x: hidden; font-family: 'Plus Jakarta Sans', sans-serif; background: #eef4ff; color: var(--ink); }
        .login-page::before { content: ''; position: fixed; inset: 0; pointer-events: none; opacity: .28; background-image: linear-gradient(rgba(40,121,245,.08) 1px, transparent 1px), linear-gradient(90deg, rgba(40,121,245,.08) 1px, transparent 1px); background-size: 42px 42px; mask-image: linear-gradient(to bottom, black, transparent 76%); }
        .login-page::after { content: ''; position: fixed; width: 620px; height: 620px; right: -210px; top: -250px; pointer-events: none; border-radius: 50%; background: radial-gradient(circle, rgba(34,211,238,.27), transparent 66%); animation: loginPulse 7s ease-in-out infinite; }
        .login-shell { position: relative; z-index: 1; width: min(100% - 32px, 470px); padding: 24px 0; animation: loginRise .7s cubic-bezier(.22,1,.36,1) both; }
        .login-panel { position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,.9); border-radius: 28px; padding: clamp(28px, 7vw, 48px); background: rgba(255,255,255,.82); box-shadow: 0 28px 70px -24px rgba(28,66,140,.35), 0 8px 25px rgba(28,66,140,.08); backdrop-filter: blur(18px); }
        .login-panel::before { content: ''; position: absolute; width: 210px; height: 210px; left: -120px; bottom: -125px; border-radius: 50%; background: rgba(40,121,245,.12); animation: loginDrift 9s ease-in-out infinite; }
        .login-brand { font-family: 'Sora', sans-serif; letter-spacing: -.04em; }
        .login-icon { display: inline-flex; align-items: center; justify-content: center; width: 68px; height: 68px; border-radius: 21px; color: white; background: linear-gradient(145deg, #2879f5, #5548e8); box-shadow: 0 14px 28px rgba(40,121,245,.3); animation: loginDrift 4s ease-in-out infinite; }
        .login-info { border: 1px solid #cfe0ff; border-radius: 16px; background: linear-gradient(135deg, #eff6ff, #e7faff); }
        .login-submit { position: relative; overflow: hidden; box-shadow: 0 14px 26px rgba(40,121,245,.28); transition: transform .25s ease, box-shadow .25s ease; }
        .login-submit::after { content: ''; position: absolute; inset: -60% auto -60% -20%; width: 24%; background: rgba(255,255,255,.3); transform: translateX(-120%) rotate(18deg); animation: loginSweep 3.8s ease-in-out infinite; }
        .login-submit:hover { transform: translateY(-2px); box-shadow: 0 18px 32px rgba(40,121,245,.36); }
        @media (prefers-reduced-motion: reduce) { .login-page::after, .login-shell, .login-panel::before, .login-icon, .login-submit::after { animation: none; } }
    </style>
</head>
<body class="login-page flex items-center justify-center">
    <main class="login-shell">
    <div class="login-panel">
        <!-- Header -->
        <div class="relative text-center mb-8">
            <div class="login-icon mx-auto mb-5 text-3xl">✎</div>
            <p class="text-xs font-bold uppercase tracking-[.28em] text-blue-600 mb-3">Quiz session</p>
            <h1 class="login-brand text-4xl font-extrabold text-slate-900 mb-3">Siap bermain?</h1>
            <p class="text-slate-500">Masukkan nama Anda untuk memulai tantangan.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('quiz.start', ['sessionCode' => $sessionCode]) }}" method="GET" class="relative space-y-6">
            <!-- Info Soal -->
            <div class="login-info p-4 flex items-center gap-4">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-xl shadow-sm">⚡</span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-700">Jumlah soal</p>
                    <p class="text-slate-800 font-bold mt-1">{{ $totalSoal }} soal siap dijawab</p>
                </div>
            </div>

            <!-- Input Nama -->
            <div>
                <label for="nama" class="block text-slate-700 font-bold mb-2">Nama lengkap</label>
                <input 
                    type="text" 
                    name="nama" 
                    id="nama" 
                    class="w-full px-4 py-4 border-2 border-slate-200 rounded-xl bg-white/80 text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition"
                    placeholder="Masukkan nama Anda"
                    required
                    autofocus>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="login-submit w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-4 rounded-xl transition">
                <span class="relative z-10">Mulai Quiz <span class="ml-2">→</span></span>
            </button>
        </form>

        <!-- Footer Info -->
        <div class="mt-7 pt-5 border-t border-slate-200 text-center">
            <p class="text-xs text-slate-500">
                Kode sesi <span class="font-mono font-bold text-slate-700 tracking-widest">{{ $sessionCode }}</span>
            </p>
        </div>
    </div>
    </main>
</body>
</html>
