<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - {{ $soal->pertanyaan }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}?v={{ filemtime(public_path('assets/css/theme.css')) }}">
    <style>
        @keyframes sqRise { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes sqFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .sq-wrap { animation: sqRise .65s var(--ease-out) both; }
        .sq-mark {
            display: grid; place-items: center; width: 44px; height: 44px; flex-shrink: 0;
            border-radius: 13px; font-size: 19px; color: #fff;
            background: linear-gradient(145deg, #8b6dff, #4f46e5 55%, #22d3ee);
            box-shadow: 0 14px 30px -12px rgba(124, 92, 255, .9), inset 0 1px 0 rgba(255, 255, 255, .3);
            animation: sqFloat 4s ease-in-out infinite;
        }
        .sq-time {
            display: inline-flex; align-items: center; gap: 8px; max-width: 46%;
            padding: 7px 14px; border-radius: 999px; font-size: .8rem; font-weight: 700;
            color: #dfe4ff; background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .16);
        }
        .sq-time span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .sq-chip {
            display: inline-flex; align-items: center; gap: 6px; padding: 5px 13px;
            border-radius: 999px; font-size: .75rem; font-weight: 700;
            background: rgba(255, 255, 255, .07); border: 1px solid rgba(255, 255, 255, .14);
            color: rgba(230, 233, 248, .82);
        }
        .sq-chip--violet { color: #d5cdff; background: rgba(124, 92, 255, .16); border-color: rgba(150, 122, 255, .4); }
        .sq-chip--amber { color: #ffe0a3; background: rgba(255, 197, 61, .14); border-color: rgba(255, 197, 61, .38); }
        .sq-chip--emerald { color: #a7f3d0; background: rgba(16, 185, 129, .16); border-color: rgba(52, 211, 153, .36); }
        .sq-chip--rose { color: #fecdd3; background: rgba(251, 113, 133, .16); border-color: rgba(251, 113, 133, .38); }
        .quiz-action { transition: transform .3s var(--ease-out), box-shadow .3s ease; }
        @media (prefers-reduced-motion: reduce) {
            .sq-wrap, .sq-mark { animation: none; }
            .quiz-action, .sq-answer { transition: none; }
        }
    </style>
</head>
<body class="sq-page">
    <div class="sq-bg"></div>
    <div class="sq-grid"></div>

    <div class="sq-wrap">
        <!-- Header dengan Progress -->
        <div class="sq-card" style="padding: clamp(20px, 4vw, 26px)">
            <div class="sq-head mb-5">
                <div class="flex items-center gap-3.5">
                    <div class="sq-mark"><x-icon name="pencil" /> </div>
                    <div>
                        <p class="sq-eyebrow">Quiz session</p>
                        <h1 class="sq-title text-xl sm:text-2xl">Quiz</h1>
                    </div>
                </div>
                <span class="sq-time" title="{{ $namurid }}"> <span>{{ $namurid }}</span></span>
            </div>

            <!-- Progress Bar -->
            <div>
                <div class="flex justify-between items-center text-sm mb-2.5">
                    <span class="sq-muted">Soal {{ $soalKeberapa }} dari {{ $totalSoal }}</span>
                    <span class="font-extrabold text-cyan-300">{{ $progressPersen }}%</span>
                </div>
                <div class="sq-meter">
                    <span style="width: {{ $progressPersen }}%;"></span>
                </div>
            </div>
        </div>

        <!-- Soal Card -->
        <div class="sq-card">
            <!-- Pertanyaan -->
            <div class="mb-8">
                <p class="sq-eyebrow mb-3">Pertanyaan {{ $soalKeberapa }}</p>
                <h2 class="sq-title text-2xl md:text-3xl mb-5 leading-tight">{{ $soal->pertanyaan }}</h2>
                <div class="flex flex-wrap gap-2">
                    @if($soal->kelas)
                        <span class="sq-chip sq-chip--violet"><x-icon name="school" />  {{ $soal->kelas->nama_kelas }}</span>
                    @endif
                    @if($soal->kategori)
                        <span class="sq-chip"><x-icon name="tag" />  {{ ucfirst($soal->kategori->nama_kategori) }}</span>
                    @endif
                    <span class="sq-chip
                        @if($soal->difficulty_level === 'mudah') sq-chip--emerald
                        @elseif($soal->difficulty_level === 'normal') sq-chip--amber
                        @else sq-chip--rose
                        @endif">
                        @if($soal->difficulty_level === 'mudah') <x-icon name="dot" class="text-emerald-400" />  Mudah
                        @elseif($soal->difficulty_level === 'normal') <x-icon name="dot" class="text-amber-400" />  Normal
                        @else <x-icon name="dot" class="text-rose-400" />  Sulit
                        @endif
                    </span>
                </div>
            </div>

            <!-- Form Jawaban -->
            <form id="quizForm" class="space-y-3.5">
                @csrf
                <input type="hidden" name="soal_id" value="{{ $soal->id }}">
                <input type="hidden" name="nama_murid" value="{{ $namurid }}">

                <!-- Pilihan A -->
                <label class="sq-answer">
                    <input type="radio" name="jawaban_dipilih" value="a" class="h-5 w-5" required
                        {{ $jawabanSebelumnya && $jawabanSebelumnya->jawaban_dipilih === 'a' ? 'checked' : '' }}>
                    <span class="sq-letter">A</span>
                    <span class="flex-1 text-white/85">{{ $soal->pilihan_a }}</span>
                </label>

                <!-- Pilihan B -->
                <label class="sq-answer">
                    <input type="radio" name="jawaban_dipilih" value="b" class="h-5 w-5" required
                        {{ $jawabanSebelumnya && $jawabanSebelumnya->jawaban_dipilih === 'b' ? 'checked' : '' }}>
                    <span class="sq-letter">B</span>
                    <span class="flex-1 text-white/85">{{ $soal->pilihan_b }}</span>
                </label>

                <!-- Pilihan C -->
                <label class="sq-answer">
                    <input type="radio" name="jawaban_dipilih" value="c" class="h-5 w-5" required
                        {{ $jawabanSebelumnya && $jawabanSebelumnya->jawaban_dipilih === 'c' ? 'checked' : '' }}>
                    <span class="sq-letter">C</span>
                    <span class="flex-1 text-white/85">{{ $soal->pilihan_c }}</span>
                </label>

                <!-- Pilihan D -->
                <label class="sq-answer">
                    <input type="radio" name="jawaban_dipilih" value="d" class="h-5 w-5" required
                        {{ $jawabanSebelumnya && $jawabanSebelumnya->jawaban_dipilih === 'd' ? 'checked' : '' }}>
                    <span class="sq-letter">D</span>
                    <span class="flex-1 text-white/85">{{ $soal->pilihan_d }}</span>
                </label>

                <!-- Submit Button -->
                <div class="pt-5 mt-2 border-t border-white/10 flex flex-col-reverse sm:flex-row gap-3">
                    @if ($soalIndex > 0)
                        <a href="{!! route('quiz.start', ['sessionCode' => $session->session_code, 'nama' => $namurid, 'soal' => $soalIndex - 1]) !!}"
                            class="quiz-action btn-soft flex-1">
                            ← Sebelumnya
                        </a>
                    @endif

                    <button
                        type="submit"
                        id="submitBtn"
                        class="quiz-action btn-premium btn-lg flex-1">
                        @if ($soalKeberapa === $totalSoal)
                            Selesai <x-icon name="check" /> 
                        @else
                            Berikutnya →
                        @endif
                    </button>
                </div>
            </form>

            @if ($jawabanSebelumnya)
                <div class="sq-note mt-6">
                    <span class="font-bold">Catatan:</span> Anda sudah pernah menjawab soal ini. Jawaban baru akan menggantikan yang sebelumnya.
                </div>
            @endif
        </div>

        <p class="text-center text-xs sq-faint mt-5">Web Edukasi • SD Negeri 2 Petungsewu, Wagir</p>
    </div>

    <script>
        const baseUrl = '{!! url("/quiz/" . $session->session_code) !!}';
        const namaParam = @json($namurid);
        const currentSoalIndex = {{ $soalIndex }};
        const totalSoalCount = {{ $totalSoal }};

        function getNextUrl(index) {
            const params = new URLSearchParams();
            params.set('nama', namaParam);
            params.set('soal', index);
            return baseUrl + '?' + params.toString();
        }

        document.getElementById('quizForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const radio = document.querySelector('input[name="jawaban_dipilih"]:checked');
            if (!radio) {
                alert('Pilih jawaban terlebih dahulu!');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Menyimpan...';

            try {
                const formData = new FormData(this);
                const token = document.querySelector('input[name="_token"]').value;

                const response = await fetch('{!! route("quiz.submit", ["sessionCode" => $session->session_code]) !!}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    if (currentSoalIndex < totalSoalCount - 1) {
                        window.location.href = getNextUrl(currentSoalIndex + 1);
                    } else {
                        window.location.href = '{!! route("quiz.result", ["sessionCode" => $session->session_code]) !!}';
                    }
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan saat menyimpan jawaban. Silakan coba lagi.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    </script>
</body>
</html>
