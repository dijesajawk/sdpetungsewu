<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Ditutup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
</head>
<body class="quiz-page flex items-center justify-center">
    <div class="quiz-panel max-w-md w-full p-8 text-center">
        <div class="text-6xl mb-4">⏱️</div>
        <h1 class="text-3xl font-bold text-red-600 mb-2">Sesi Ditutup</h1>
        <p class="text-gray-600 mb-6">
            Sesi soal ini telah ditutup oleh guru. Anda tidak bisa lagi menjawab soal.
        </p>

        @if ($session->selesai_at)
            <p class="text-gray-500 text-sm mb-8">
                Sesi ditutup pada: {{ $session->selesai_at->format('d M Y H:i:s') }}
            </p>
        @endif

        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <p class="text-gray-700 font-semibold text-lg">
                Kode Sesi: {{ $session->session_code }}
            </p>
        </div>

        <p class="text-gray-600 text-sm">
            Silakan hubungi guru Anda jika ada pertanyaan.
        </p>
    </div>
</body>
</html>
