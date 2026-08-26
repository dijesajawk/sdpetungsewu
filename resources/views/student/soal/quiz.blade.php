@extends('layouts.app')

@section('title', 'Kuis')

@section('content')
<style>
    .quiz-shell {
        background:
            radial-gradient(600px 300px at 85% -10%, rgba(124,92,255,.18), transparent 60%),
            radial-gradient(500px 260px at 0% 110%, rgba(34,211,238,.15), transparent 60%);
    }
    #resultModal {
        position: fixed; inset: 0; z-index: 100; display: none;
        place-items: center; padding: 1.25rem;
        background: rgba(7, 11, 26, .72); backdrop-filter: blur(10px);
    }
    #resultModal.show { display: grid; animation: fadeIn .3s ease both; }
    @keyframes fadeIn { from { opacity: 0 } to { opacity: 1 } }
    .modal-card {
        background: #fff; border-radius: 30px; max-width: 460px; width: 100%;
        text-align: center; padding: 44px 36px; position: relative; overflow: hidden;
        box-shadow: 0 50px 120px -20px rgba(7,11,26,.6);
        animation: popIn .55s cubic-bezier(.68,-0.45,.27,1.35) both;
    }
    @keyframes popIn {
        0% { opacity: 0; transform: scale(.5) rotate(-8deg); }
        60% { transform: scale(1.04) rotate(2deg); }
        100% { opacity: 1; transform: scale(1) rotate(0); }
    }
    .modal-emoji { font-size: 76px; line-height: 1; display:inline-block; animation: bounceSoft 1s ease-in-out infinite; }
    .timer-ring { transition: stroke-dashoffset 1s linear, stroke .3s ease; }

    .loading-spin { display:inline-block; width:18px; height:18px; border:2.5px solid rgba(255,255,255,.35); border-top-color:#fff; border-radius:50%; animation: spinSlow .7s linear infinite; }
</style>

<div class="quiz-shell play-font space-y-6">

    @if($jawabanSebelumnya)
    <div class="rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 px-6 py-4 flex items-center gap-3 anim-fade-down">
        <span class="text-2xl anim-wiggle inline-block">📋</span>
        <p class="text-sm text-amber-800 font-semibold">
            Jawaban sebelumnya: opsi <b>{{ strtoupper($jawabanSebelumnya->jawaban_dipilih) }}</b>
            @if($jawabanSebelumnya->is_correct)
                <span class="text-emerald-600">— Benar! ✅</span>
            @else
                <span class="text-rose-500">— Salah ❌</span>
            @endif
        </p>
    </div>
    @endif

    <div class="grid lg:grid-cols-[280px_1fr] gap-6 items-start">

        <!-- ===== SIDE INFO ===== -->
        <aside class="premium-card p-6 lg:sticky lg:top-28 reveal-left">
            <div class="flex items-center gap-3 pb-5 border-b border-dashed border-slate-200">
                <img src="{{ asset('images/logo-sekolah.jpg') }}" alt="" class="h-12 w-12 rounded-xl object-cover ring-2 ring-indigo-100">
                <div>
                    <p class="font-bold text-slate-800 leading-tight">{{ $soal->kelas->nama_kelas }}</p>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">{{ $soal->kategori->nama_kategori }}</p>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-full grid place-items-center bg-gradient-to-br from-violet-500 to-cyan-400 text-white font-extrabold shrink-0">
                    {{ strtoupper(substr($soal->guru->name, 0, 1)) }}
                </div>
                <div class="leading-tight">
                    <p class="text-[11px] font-extrabold tracking-wider uppercase text-slate-400">Guru</p>
                    <p class="font-bold text-slate-700 text-sm truncate max-w-[140px]">{{ $soal->guru->name }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 text-white text-center py-5 shadow-[0_16px_40px_-12px_rgba(124,92,255,.55)]">
                <p class="text-[11px] font-extrabold tracking-widest uppercase opacity-70">Nomor Soal</p>
                <p class="font-display font-extrabold text-5xl mt-1">{{ \App\Models\Soal::where('kelas_id', $soal->kelas_id)->where('id', '<=', $soal->id)->count() }}</p>
            </div>

            <a href="{{ route('student.soal.list') }}" class="mt-5 btn-outline-brand w-full !py-2.5 !text-sm">← Pilih Soal Lain</a>
        </aside>

        <!-- ===== QUESTION CARD ===== -->
        <div class="premium-card p-7 md:p-10 reveal">
            <p class="text-sm font-extrabold gradient-text tracking-wide mb-3">✨ Pertanyaan untukmu:</p>
            <h1 class="font-display font-extrabold text-2xl md:text-[32px] leading-snug text-slate-900">{{ $soal->pertanyaan }}</h1>

            <form id="quizForm" method="POST" action="{{ route('student.soal.submit', $soal->id) }}" class="mt-9">
                @csrf
                <input type="hidden" id="jawaban_dipilih" name="jawaban_dipilih" value="">

                <div class="space-y-4">
                    @foreach(['a' => $soal->pilihan_a, 'b' => $soal->pilihan_b, 'c' => $soal->pilihan_c, 'd' => $soal->pilihan_d] as $letter => $text)
                    <button type="button" class="option-row" data-answer="{{ $letter }}" onclick="selectOption('{{ $letter }}')">
                        <span class="option-letter">{{ strtoupper($letter) }}</span>
                        <span>{{ $text }}</span>
                        <span class="ml-auto check-mark hidden text-emerald-500 text-xl font-black">✓</span>
                    </button>
                    @endforeach
                </div>

                <div class="mt-9 flex justify-end">
                    <button type="submit" class="btn-premium !py-3.5 !px-10 text-base" id="submitBtn" disabled>
                        ✓ Jawab Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== RESULT MODAL ===== -->
<div id="resultModal">
    <div class="modal-card">
        <div id="confettiAnchor"></div>
        <span class="modal-emoji" id="resultEmoji">🤔</span>
        <h2 class="font-display font-extrabold text-2xl mt-4" id="resultMessage">Mengirim jawaban...</h2>
        <div class="text-slate-500 mt-3 leading-relaxed text-sm" id="resultDetail"></div>
        <div class="flex flex-wrap justify-center gap-3 mt-8">
            <a href="{{ route('student.soal.list') }}" class="btn-premium btn-gold !text-[#422006] !py-3 !px-7 text-sm">Soal Berikutnya →</a>
            <a href="{{ route('student.result') }}" class="btn-outline-brand !py-3 !px-7 text-sm">📊 Lihat Hasil</a>
        </div>
    </div>
</div>

<script>
let selectedAnswer = null;

function selectOption(answer) {
    document.querySelectorAll('.option-row').forEach(btn => {
        btn.classList.remove('selected');
        btn.querySelector('.check-mark').classList.add('hidden');
    });
    const row = document.querySelector(`[data-answer="${answer}"]`);
    if (!row) return;
    row.classList.add('selected');
    row.querySelector('.check-mark').classList.remove('hidden');
    document.getElementById('jawaban_dipilih').value = answer;
    selectedAnswer = answer;
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = false;
    submitBtn.style.animation = 'none'; void submitBtn.offsetWidth; submitBtn.style.animation = 'anim-pulse-glow 2s ease-in-out infinite';
}

document.getElementById('quizForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<span class="loading-spin"></span> Mengirim...';
    submitBtn.disabled = true;

    fetch('{{ route("student.soal.submit", $soal->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({ jawaban_dipilih: document.getElementById('jawaban_dipilih').value })
    })
    .then(r => r.json())
    .then(data => {
        const emoji = document.getElementById('resultEmoji');
        const message = document.getElementById('resultMessage');
        const detail = document.getElementById('resultDetail');

        // reveal correct/wrong on rows
        document.querySelectorAll('.option-row').forEach(row => {
            if (row.dataset.answer === data.jawaban_benar) { row.classList.add('correct-reveal'); row.classList.remove('selected'); }
            else if (row.dataset.answer === selectedAnswer && !data.is_correct) row.classList.add('wrong-reveal');
        });

        if (data.is_correct) {
            emoji.textContent = '🎉';
            message.textContent = data.message;
            message.className = 'font-display font-extrabold text-2xl mt-4';
            message.style.color = '#059669';
            detail.innerHTML = `<p><b>Jawaban Benar!</b> 🌟 Kamu memilih opsi <b>${selectedAnswer.toUpperCase()}</b>. Hebat!</p>`;
            premiumConfetti(window.innerWidth / 2, window.innerHeight / 2.6, 110);
        } else {
            emoji.textContent = '💪';
            message.textContent = data.message;
            message.className = 'font-display font-extrabold text-2xl mt-4';
            message.style.color = '#f43f5e';
            detail.innerHTML = `
                <p>Kamu memilih opsi <b>${selectedAnswer.toUpperCase()}</b></p>
                <p class="mt-2">Jawaban benar adalah opsi <b style="color:#059669">${data.jawaban_benar.toUpperCase()}</b> ✅</p>
                <p class="mt-3 text-xs text-slate-400">Jangan khawatir, coba lagi di soal berikutnya! 📚</p>`;
        }

        document.getElementById('resultModal').classList.add('show');
        submitBtn.innerHTML = '✓ Jawab Soal';
        submitBtn.disabled = false;
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Terjadi kesalahan saat mengirim jawaban');
        submitBtn.innerHTML = '✓ Jawab Soal';
        submitBtn.disabled = false;
    });
});

@if($jawabanSebelumnya)
window.addEventListener('load', function () {
    selectOption('{{ $jawabanSebelumnya->jawaban_dipilih }}');
});
@endif
</script>
@endsection
