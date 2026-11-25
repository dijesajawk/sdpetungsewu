<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Quiz</title>
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
            border-radius: 24px; font-size: 40px;
            background: linear-gradient(150deg, rgba(124, 92, 255, .3), rgba(34, 211, 238, .18));
            border: 1px solid rgba(255, 255, 255, .14);
            box-shadow: 0 22px 46px -18px rgba(124, 92, 255, .8), inset 0 1px 0 rgba(255, 255, 255, .2);
        }
        .sq-detail p { color: rgba(230, 233, 248, .72); }
        .sq-detail strong { color: #fff; }
        @media (prefers-reduced-motion: reduce) { .sq-wrap { animation: none; } }
    </style>
</head>
<body class="sq-page">
    <div class="sq-bg"></div>
    <div class="sq-grid"></div>

    <div class="sq-wrap">
        <!-- Header Hasil -->
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

            <div class="sq-medal mb-5">
                @if ($skorFinal >= 80)
                    <x-icon name="sparkles" /> 
                @elseif ($skorFinal >= 60)
                    <x-icon name="thumbs-up" /> 
                @else
                    <x-icon name="smile" /> 
                @endif
            </div>

            <h1 class="sq-title text-3xl md:text-4xl mb-2
                @if ($skorFinal >= 80)
                    text-emerald-300
                @elseif ($skorFinal >= 60)
                    text-amber-300
                @else
                    text-cyan-300
                @endif">
                @if ($skorFinal >= 80)
                    Sempurna!
                @elseif ($skorFinal >= 60)
                    Bagus!
                @else
                    Tetap Semangat!
                @endif
            </h1>
            <p class="sq-muted text-sm sm:text-base">Quiz Selesai</p>
        </div>

        <!-- Score Card -->
        <div class="sq-card">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 mb-6">
                <div class="sq-stat">
                    <b class="text-cyan-300">{{ $totalSoal }}</b>
                    <span>Total Soal</span>
                </div>
                <div class="sq-stat">
                    <b class="text-violet-300">{{ $totalJawab }}</b>
                    <span>Terjawab</span>
                </div>
                <div class="sq-stat">
                    <b class="text-emerald-300">{{ $benarJawab }}</b>
                    <span>Benar</span>
                </div>
                <div class="sq-stat">
                    <b class="text-amber-300">{{ $skorFinal }}%</b>
                    <span>Skor</span>
                </div>
            </div>

            <!-- Progress Bar Skor -->
            <div class="mb-6">
                <div class="flex justify-between items-center text-sm mb-2.5">
                    <span class="sq-muted">Tingkat Pencapaian</span>
                    <span class="font-extrabold text-cyan-300">{{ $skorFinal }}%</span>
                </div>
                <div class="sq-meter">
                    <span style="width: {{ $skorFinal }}%;"></span>
                </div>
            </div>

            <hr class="sq-divider">

            <div class="text-center sq-muted text-sm">
                <p>Nama: <span class="font-bold text-white">{{ $namurid }}</span></p>
                <p class="text-xs mt-2 sq-faint">Kode Sesi: <span class="font-mono tracking-widest text-cyan-200">{{ $session->session_code }}</span></p>
            </div>
        </div>

        <!-- Detail Jawaban -->
        @if ($jawabanList->count() > 0)
            <div class="sq-card">
                <h2 class="sq-title text-xl sm:text-2xl mb-5"><x-icon name="clipboard-list" />  Detail Jawaban</h2>

                <div>
                    @foreach ($jawabanList as $key => $jawaban)
                        <div class="sq-detail sq-detail--{{ $jawaban->is_correct ? 'ok' : 'no' }}">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="sq-title text-base">Soal {{ $key + 1 }}</span>
                                    @if ($jawaban->is_correct)
                                        <span class="sq-tag sq-tag--ok"><x-icon name="check" />  Benar</span>
                                    @else
                                        <span class="sq-tag sq-tag--no"><x-icon name="x" />  Salah</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm leading-relaxed">{{ Str::limit($jawaban->soal->pertanyaan, 100) }}</p>

                            <div class="text-sm mt-3 space-y-1.5">
                                <p><strong>Jawaban Anda:</strong> {{ strtoupper($jawaban->jawaban_dipilih) }}.
                                    @if ($jawaban->jawaban_dipilih === 'a')
                                        {{ $jawaban->soal->pilihan_a }}
                                    @elseif ($jawaban->jawaban_dipilih === 'b')
                                        {{ $jawaban->soal->pilihan_b }}
                                    @elseif ($jawaban->jawaban_dipilih === 'c')
                                        {{ $jawaban->soal->pilihan_c }}
                                    @else
                                        {{ $jawaban->soal->pilihan_d }}
                                    @endif
                                </p>
                                @if (!$jawaban->is_correct)
                                    <p><strong class="text-emerald-300">Jawaban Benar:</strong> {{ strtoupper($jawaban->soal->jawaban_benar) }}.
                                        @if ($jawaban->soal->jawaban_benar === 'a')
                                            {{ $jawaban->soal->pilihan_a }}
                                        @elseif ($jawaban->soal->jawaban_benar === 'b')
                                            {{ $jawaban->soal->pilihan_b }}
                                        @elseif ($jawaban->soal->jawaban_benar === 'c')
                                            {{ $jawaban->soal->pilihan_c }}
                                        @else
                                            {{ $jawaban->soal->pilihan_d }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Action Info -->
        <div class="sq-card text-center" style="padding: clamp(20px, 4vw, 26px)">
            <p class="text-sm font-bold text-white/85">
                Quiz Anda telah selesai dan hasilnya telah tersimpan.
            </p>
            <p class="text-xs sq-faint mt-2">
                Silakan hubungi guru Anda jika ada pertanyaan tentang jawaban Anda.
            </p>
        </div>

        <p class="text-center text-xs sq-faint mt-5">Web Edukasi • SD Negeri 2 Petungsewu, Wagir</p>
    </div>
</body>
</html>
