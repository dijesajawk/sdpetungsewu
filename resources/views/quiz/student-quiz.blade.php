<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - {{ $soal->pertanyaan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <style>
        @keyframes quizEnter { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes quizFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
        @keyframes progressGlow { 0%, 100% { box-shadow: 0 0 0 rgba(34,211,238,0); } 50% { box-shadow: 0 0 18px rgba(34,211,238,.45); } }
        body.quiz-modern { min-height: 100vh; margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(145deg, #eef4ff 0%, #f7faff 48%, #e9fbff 100%); color: #0f172a; }
        .quiz-modern::before { content: ''; position: fixed; inset: 0; pointer-events: none; opacity: .3; background-image: linear-gradient(rgba(40,121,245,.07) 1px, transparent 1px), linear-gradient(90deg, rgba(40,121,245,.07) 1px, transparent 1px); background-size: 44px 44px; mask-image: linear-gradient(to bottom, black, transparent 78%); }
        .quiz-wrap { position: relative; z-index: 1; width: min(100% - 32px, 760px); padding: 28px 0 40px; animation: quizEnter .65s cubic-bezier(.22,1,.36,1) both; }
        .quiz-surface { border: 1px solid rgba(255,255,255,.9); background: rgba(255,255,255,.8); box-shadow: 0 25px 60px -25px rgba(32,69,140,.28); backdrop-filter: blur(18px); }
        .quiz-title { font-family: 'Sora', sans-serif; letter-spacing: -.04em; }
        .quiz-float { animation: quizFloat 4s ease-in-out infinite; }
        .quiz-progress { animation: progressGlow 3s ease-in-out infinite; }
        .quiz-answer { border: 2px solid #e2e8f0; border-radius: 16px; background: rgba(255,255,255,.72); transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease, background .25s ease; }
        .quiz-answer:hover { transform: translateX(4px); border-color: #93c5fd; box-shadow: 0 8px 20px rgba(40,121,245,.1); }
        .quiz-answer:has(input:checked) { border-color: #2879f5; background: linear-gradient(135deg, #eff6ff, #ecfeff); box-shadow: 0 10px 25px rgba(40,121,245,.16); }
        .quiz-answer:has(input:checked) .answer-letter { color: white; background: linear-gradient(145deg, #2879f5, #5548e8); }
        .answer-letter { display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; flex-shrink: 0; border-radius: 11px; color: #2563eb; background: #dbeafe; font-weight: 800; transition: all .25s ease; }
        .quiz-action { transition: transform .25s ease, box-shadow .25s ease; }
        .quiz-action:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(30,64,175,.18); }
        @media (prefers-reduced-motion: reduce) { .quiz-wrap, .quiz-float, .quiz-progress { animation: none; } .quiz-answer, .quiz-action { transition: none; } }
    </style>
</head>
<body class="quiz-modern">
    <div class="quiz-wrap mx-auto">
        <!-- Header dengan Progress -->
        <div class="quiz-surface rounded-2xl p-5 md:p-6 mb-6">
            <div class="flex justify-between items-center mb-5">
                <div class="flex items-center gap-3">
                    <div class="quiz-float flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-xl text-white">✎</div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[.22em] text-blue-600">Quiz session</p>
                        <h1 class="quiz-title text-2xl font-bold text-slate-900">Quiz</h1>
                    </div>
                </div>
                <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold text-sm max-w-[42%] truncate" title="{{ $namurid }}">
                    {{ $namurid }}
                </span>
            </div>
            
            <!-- Progress Bar -->
            <div class="mb-2">
                <div class="flex justify-between text-sm text-slate-500 mb-2">
                    <span>Soal {{ $soalKeberapa }} dari {{ $totalSoal }}</span>
                    <span class="font-bold text-blue-600">{{ $progressPersen }}%</span>
                </div>
                <div class="h-3 bg-slate-200 rounded-full overflow-hidden">
                    <div class="quiz-progress h-full bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-400 rounded-full transition-all duration-300" 
                        style="width: {{ $progressPersen }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Soal Card -->
        <div class="quiz-surface rounded-2xl p-6 md:p-9 mb-6">
            <!-- Pertanyaan -->
            <div class="mb-8">
                <p class="text-xs font-bold uppercase tracking-[.2em] text-slate-400 mb-3">Pertanyaan {{ $soalKeberapa }}</p>
                <h2 class="quiz-title text-2xl md:text-3xl font-bold text-slate-900 mb-5 leading-tight">{{ $soal->pertanyaan }}</h2>
                <div class="flex flex-wrap gap-2">
                    @if($soal->kelas)
                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $soal->kelas->nama_kelas }}
                    </span>
                    @endif
                    @if($soal->kategori)
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ ucfirst($soal->kategori->nama_kategori) }}
                    </span>
                    @endif
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                        @if($soal->difficulty_level === 'mudah') bg-green-100 text-green-800
                        @elseif($soal->difficulty_level === 'normal') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        @if($soal->difficulty_level === 'mudah') Mudah
                        @elseif($soal->difficulty_level === 'normal') Normal
                        @else Sulit
                        @endif
                    </span>
                </div>
            </div>

            <!-- Form Jawaban -->
            <form id="quizForm" class="space-y-4">
                @csrf
                <input type="hidden" name="soal_id" value="{{ $soal->id }}">
                <input type="hidden" name="nama_murid" value="{{ $namurid }}">

                <!-- Pilihan A -->
                <label class="quiz-answer flex items-center gap-4 p-4 cursor-pointer">
                    <input type="radio" name="jawaban_dipilih" value="a" class="h-5 w-5 accent-blue-600" required 
                        {{ $jawabanSebelumnya && $jawabanSebelumnya->jawaban_dipilih === 'a' ? 'checked' : '' }}>
                    <span class="answer-letter">A</span>
                    <span class="flex-1 text-slate-700">{{ $soal->pilihan_a }}</span>
                    </span>
                </label>

                <!-- Pilihan B -->
                <label class="quiz-answer flex items-center gap-4 p-4 cursor-pointer">
                    <input type="radio" name="jawaban_dipilih" value="b" class="h-5 w-5 accent-blue-600" required
                        {{ $jawabanSebelumnya && $jawabanSebelumnya->jawaban_dipilih === 'b' ? 'checked' : '' }}>
                    <span class="answer-letter">B</span>
                    <span class="flex-1 text-slate-700">{{ $soal->pilihan_b }}</span>
                    </span>
                </label>

                <!-- Pilihan C -->
                <label class="quiz-answer flex items-center gap-4 p-4 cursor-pointer">
                    <input type="radio" name="jawaban_dipilih" value="c" class="h-5 w-5 accent-blue-600" required
                        {{ $jawabanSebelumnya && $jawabanSebelumnya->jawaban_dipilih === 'c' ? 'checked' : '' }}>
                    <span class="answer-letter">C</span>
                    <span class="flex-1 text-slate-700">{{ $soal->pilihan_c }}</span>
                    </span>
                </label>

                <!-- Pilihan D -->
                <label class="quiz-answer flex items-center gap-4 p-4 cursor-pointer">
                    <input type="radio" name="jawaban_dipilih" value="d" class="h-5 w-5 accent-blue-600" required
                        {{ $jawabanSebelumnya && $jawabanSebelumnya->jawaban_dipilih === 'd' ? 'checked' : '' }}>
                    <span class="answer-letter">D</span>
                    <span class="flex-1 text-slate-700">{{ $soal->pilihan_d }}</span>
                    </span>
                </label>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-slate-200 flex flex-col-reverse sm:flex-row gap-3">
                    @if ($soalIndex > 0)
                        <a href="{!! route('quiz.start', ['sessionCode' => $session->session_code, 'nama' => $namurid, 'soal' => $soalIndex - 1]) !!}"
                            class="quiz-action flex-1 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 py-3 text-center font-semibold">
                            ← Sebelumnya
                        </a>
                    @endif
                    
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="quiz-action flex-1 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white py-3 text-lg font-bold">
                        @if ($soalKeberapa === $totalSoal)
                            Selesai ✓
                        @else
                            Berikutnya →
                        @endif
                    </button>
                </div>
            </form>

            @if ($jawabanSebelumnya)
                <div class="mt-6 bg-amber-50 border border-amber-200 p-4 rounded-xl">
                    <p class="text-amber-800 text-sm">
                        <span class="font-bold">Catatan:</span> Anda sudah pernah menjawab soal ini. Jawaban baru akan menggantikan yang sebelumnya.
                    </p>
                </div>
            @endif
        </div>
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
