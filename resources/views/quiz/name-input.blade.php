<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukkan Nama - Quiz</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}?v={{ filemtime(public_path('assets/css/theme.css')) }}">
    <style>
        @keyframes sqRise { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes sqFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
        .sq-wrap { animation: sqRise .7s var(--ease-out) both; }
        .sq-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 66px; height: 66px; border-radius: 20px; font-size: 28px;
            background: linear-gradient(145deg, #8b6dff, #4f46e5 55%, #22d3ee);
            box-shadow: 0 18px 38px -14px rgba(124, 92, 255, .85), inset 0 1px 0 rgba(255, 255, 255, .35);
            animation: sqFloat 4.5s ease-in-out infinite;
        }
        .sq-info {
            display: flex; align-items: center; gap: 14px; padding: 15px 18px; border-radius: 16px;
            border: 1px solid rgba(124, 92, 255, .32);
            background: linear-gradient(135deg, rgba(124, 92, 255, .16), rgba(34, 211, 238, .1));
        }
        .sq-info-icon {
            display: grid; place-items: center; width: 42px; height: 42px; flex-shrink: 0;
            border-radius: 12px; font-size: 19px;
            background: rgba(255, 255, 255, .08); border: 1px solid rgba(255, 255, 255, .14);
        }
        .login-submit { position: relative; overflow: hidden; }
        .login-submit::after {
            content: ''; position: absolute; inset: -60% auto -60% -20%; width: 24%;
            background: rgba(255, 255, 255, .3); transform: translateX(-120%) rotate(18deg);
            animation: sqSweep 3.8s ease-in-out infinite;
        }
        @keyframes sqSweep { from { transform: translateX(-120%) rotate(18deg); } to { transform: translateX(280%) rotate(18deg); } }
        @media (prefers-reduced-motion: reduce) {
            .sq-wrap, .sq-icon, .login-submit::after { animation: none; }
        }
    </style>
</head>
<body class="sq-page">
    <div class="sq-bg"></div>
    <div class="sq-grid"></div>

    <main class="sq-wrap sq-wrap--sm">
        <div class="sq-card">
            <!-- Brand -->
            <div class="flex justify-center mb-7">
                <span class="sq-brand">
                    <span class="logo-badge"><img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SDN 2 Petungsewu"></span>
                    <span>
                        <b>Web Edukasi</b>
                        <small>SDN 2 Petungsewu</small>
                    </span>
                </span>
            </div>

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="sq-icon mx-auto mb-5"><x-icon name="pencil" /> </div>
                <p class="sq-eyebrow mb-3">Quiz session</p>
                <h1 class="sq-title text-3xl sm:text-4xl mb-3">Siap bermain?</h1>
                <p class="sq-muted text-sm sm:text-base">Masukkan nama Anda untuk memulai tantangan.</p>
            </div>

            <!-- Form -->
            <form action="{{ route('quiz.start', ['sessionCode' => $sessionCode]) }}" method="GET" class="relative space-y-6">
                <!-- Info Soal -->
                <div class="sq-info">
                    <span class="sq-info-icon"><x-icon name="zap" /> </span>
                    <div>
                        <p class="sq-eyebrow" style="color:#a5b4fc">Jumlah soal</p>
                        <p class="font-bold text-white mt-1 text-sm">{{ $totalSoal }} soal siap dijawab</p>
                    </div>
                </div>

                <!-- Input Nama -->
                <div>
                    <label for="nama" class="block text-sm font-bold text-white/80 mb-2">Nama lengkap</label>
                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        class="sq-input"
                        placeholder="Masukkan nama Anda"
                        required
                        autofocus>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="login-submit btn-premium btn-lg w-full">
                    <span class="relative z-10">Mulai Quiz <span class="ml-2">→</span></span>
                </button>
            </form>

            <!-- Footer Info -->
            <div class="mt-7 pt-5 border-t border-white/10 text-center">
                <p class="text-xs sq-faint">
                    Kode sesi <span class="font-mono font-bold text-cyan-200 tracking-widest">{{ $sessionCode }}</span>
                </p>
            </div>
        </div>
    </main>
</body>
</html>
