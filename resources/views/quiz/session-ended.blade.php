<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Ditutup</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}?v={{ filemtime(public_path('assets/css/theme.css')) }}">
    <style>
        @keyframes sqRise { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }
        .sq-wrap { animation: sqRise .65s var(--ease-out) both; }
        .sq-medal {
            display: inline-grid; place-items: center; width: 82px; height: 82px; margin: 0 auto;
            border-radius: 24px; font-size: 38px;
            background: linear-gradient(150deg, rgba(251, 113, 133, .26), rgba(124, 92, 255, .2));
            border: 1px solid rgba(251, 113, 133, .38);
            box-shadow: 0 22px 46px -18px rgba(251, 113, 133, .7), inset 0 1px 0 rgba(255, 255, 255, .2);
        }
        .sq-code {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 12px 22px; border-radius: 14px;
            background: rgba(255, 255, 255, .05); border: 1px solid rgba(255, 255, 255, .14);
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            letter-spacing: .3em; font-weight: 800; color: #a5f3fc; font-size: 1.05rem;
        }
        @media (prefers-reduced-motion: reduce) { .sq-wrap { animation: none; } }
    </style>
</head>
<body class="sq-page">
    <div class="sq-bg"></div>
    <div class="sq-grid"></div>

    <div class="sq-wrap sq-wrap--sm">
        <div class="sq-card text-center">
            <div class="flex justify-center mb-6">
                <span class="sq-brand">
                    <span class="logo-badge"><img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SDN 2 Petungsewu"></span>
                    <span>
                        <b>Web Edukasi</b>
                        <small>SDN 2 Petungsewu</small>
                    </span>
                </span>
            </div>

            <div class="sq-medal mb-5"><x-icon name="clock" /> </div>
            <h1 class="sq-title text-3xl mb-2 text-rose-300">Sesi Ditutup</h1>
            <p class="sq-muted text-sm sm:text-base leading-relaxed mb-6">
                Sesi soal ini telah ditutup oleh guru. Anda tidak bisa lagi menjawab soal.
            </p>

            @if ($session->selesai_at)
                <p class="sq-faint text-xs mb-7">
                    Sesi ditutup pada: {{ $session->selesai_at->format('d M Y H:i:s') }}
                </p>
            @endif

            <div class="mb-6">
                <p class="sq-eyebrow mb-3">Kode Sesi</p>
                <span class="sq-code">{{ $session->session_code }}</span>
            </div>

            <hr class="sq-divider">

            <p class="sq-faint text-xs">
                Silakan hubungi guru Anda jika ada pertanyaan.
            </p>
        </div>

        <p class="text-center text-xs sq-faint mt-5">Web Edukasi • SD Negeri 2 Petungsewu, Wagir</p>
    </div>
</body>
</html>
