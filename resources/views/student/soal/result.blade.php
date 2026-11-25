@extends('layouts.app')

@section('title', 'Hasil Kuis')

@section('content')
<style>
    .result-hero {
        background:
            radial-gradient(520px 260px at 88% -10%, rgba(251,113,133,.35), transparent 60%),
            radial-gradient(460px 240px at 5% 110%, rgba(124,92,255,.3), transparent 60%),
            linear-gradient(135deg, #2b2160, #14123a);
    }
    .score-ring circle.progress {
        transition: stroke-dashoffset 1.6s cubic-bezier(.22,1,.36,1);
    }
    .answer-row { transition: transform .4s cubic-bezier(.22,1,.36,1), box-shadow .4s ease; }
    .answer-row:hover { transform: translateX(8px); }
</style>

<div class="play-font space-y-9">

    <!-- ===== HERO SCORE ===== -->
    <div class="result-hero relative overflow-hidden rounded-[30px] p-8 md:p-12 text-white anim-fade-up shadow-[0_34px_90px_-26px_rgba(43,33,96,.6)]">
        <span class="absolute -right-8 -top-8 text-[140px] opacity-10 select-none anim-float"><x-icon name="trophy" /> </span>
        <canvas data-particles data-particles-count="20" style="position:absolute;inset:0;"></canvas>

        <div class="relative z-10 grid md:grid-cols-[auto_1fr] gap-9 items-center max-w-3xl mx-auto">
            <!-- Ring -->
            <div class="score-ring relative mx-auto w-[190px] h-[190px]">
                <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                    <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,.12)" stroke-width="11"/>
                    <circle class="progress" cx="60" cy="60" r="52" fill="none"
                        stroke="url(#ringGrad)" stroke-width="11" stroke-linecap="round"
                        stroke-dasharray="{{ 2 * pi() * 52 }}"
                        stroke-dashoffset="{{ 2 * pi() * 52 }}"
                        data-target-offset="{{ round(2 * pi() * 52 * (1 - min($skor,100) / 100)) }}"/>
                    <defs>
                        <linearGradient id="ringGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#ffc53d"/>
                            <stop offset="55%" stop-color="#fb7185"/>
                            <stop offset="100%" stop-color="#7c5cff"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="absolute inset-0 grid place-items-center text-center">
                    <div>
                        <p class="font-display font-extrabold text-5xl"><span data-count="{{ round($skor) }}" data-suffix="%">0%</span></p>
                        <p class="text-xs font-extrabold tracking-widest uppercase opacity-60 mt-1">Nilai Kamu</p>
                    </div>
                </div>
            </div>

            <div class="text-center md:text-left">
                <span class="chip-pill chip-glass reveal"><x-icon name="bar-chart" />  Rekap Hasil Belajar</span>
                <h1 class="font-display font-extrabold text-3xl md:text-4xl mt-4 leading-tight">
                    @if($skor >= 80) Luar Biasa! <x-icon name="star" /> 
                    @elseif($skor >= 60) Bagus Sekali! <x-icon name="thumbs-up" /> 
                    @elseif($skor >= 40) Terus Semangat! <x-icon name="activity" /> 
                    @else Jangan Menyerah! <x-icon name="rocket" /> 
                    @endif
                </h1>
                <p class="text-white/60 mt-2.5">Kamu sudah menyelesaikan <b class="text-white">{{ $totalSoal }} soal</b> — setiap usaha adalah kemajuan!</p>

                <div class="mt-6 flex flex-wrap gap-6 justify-center md:justify-start">
                    <div class="glass-dark rounded-2xl px-5 py-3.5 border border-white/10 text-center">
                        <p class="font-display font-extrabold text-2xl text-emerald-400">{{ $benarJawab }}</p>
                        <p class="text-[11px] font-bold tracking-widest uppercase opacity-60">Benar <x-icon name="check-circle" /> </p>
                    </div>
                    <div class="glass-dark rounded-2xl px-5 py-3.5 border border-white/10 text-center">
                        <p class="font-display font-extrabold text-2xl text-rose-400">{{ $totalSoal - $benarJawab }}</p>
                        <p class="text-[11px] font-bold tracking-widest uppercase opacity-60">Salah <x-icon name="x-circle" /> </p>
                    </div>
                    <div class="glass-dark rounded-2xl px-5 py-3.5 border border-white/10 text-center">
                        <p class="font-display font-extrabold text-2xl gradient-text-gold">{{ $totalSoal }}</p>
                        <p class="text-[11px] font-bold tracking-widest uppercase opacity-60">Total <x-icon name="book-open" /> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTER ===== -->
    @if(count($jawaban) > 0)
    <div class="premium-card p-5 md:p-6 reveal delay-1">
        <form method="GET" action="{{ route('student.result') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="kelas_id" class="block text-xs font-extrabold tracking-wider uppercase text-slate-400 mb-2"><x-icon name="school" />  Kelas</label>
                <select name="kelas_id" id="kelas_id" class="input-premium">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="kategori_id" class="block text-xs font-extrabold tracking-wider uppercase text-slate-400 mb-2"><x-icon name="book-open" />  Kategori</label>
                <select name="kategori_id" id="kategori_id" class="input-premium">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ $kategoriId == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-premium !py-3"><x-icon name="search" />  Filter</button>
        </form>
    </div>

    <!-- ===== RIWAYAT JAWABAN ===== -->
    <section>
        <h2 class="font-display font-bold text-xl text-slate-800 mb-5 flex items-center gap-3 reveal">
            <span class="anim-wiggle inline-block text-2xl"><x-icon name="file-text" /> </span> Riwayat Jawabanmu
        </h2>

        <div class="space-y-4" data-stagger>
            @foreach($jawaban as $j)
            <div class="answer-row premium-card !rounded-[18px] p-5 border-r-8 {{ $j->is_correct ? '!border-emerald-400' : '!border-rose-300' }}">
                <div class="flex items-start justify-between gap-4">
                    <p class="font-bold text-slate-700 leading-snug">{{ Str::limit($j->soal->pertanyaan, 110) }}</p>
                    <span class="text-xl shrink-0">{{ $j->is_correct ? '' : '' }}</span>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 mt-3.5 text-sm">
                    <span class="chip-pill {{ $j->is_correct ? 'bg-emerald-50 !text-emerald-600' : 'bg-rose-50 !text-rose-500' }} !py-1.5 !text-xs">
                        Pilihanmu: <b>{{ strtoupper($j->jawaban_dipilih) }}</b>. {{ $j->soal['pilihan_' . $j->jawaban_dipilih] }}
                    </span>
                    @if(!$j->is_correct)
                        <span class="chip-pill bg-emerald-50 !text-emerald-600 !py-1.5 !text-xs">
                            Benar: <b>{{ strtoupper($j->soal->jawaban_benar) }}</b>. {{ $j->soal['pilihan_' . $j->soal->jawaban_benar] }}
                        </span>
                    @endif
                </div>

                <p class="text-xs text-slate-400 font-semibold mt-3"><x-icon name="bookmark" />  {{ $j->soal->kelas->nama_kelas }} • {{ $j->soal->kategori->nama_kategori }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @else
    <div class="empty-state reveal-scale">
        <span class="empty-emoji"><x-icon name="help-circle" /> </span>
        <h3 class="font-display font-bold text-xl text-slate-800 mt-3">Belum Ada Jawaban</h3>
        <p class="text-slate-400 mt-1">Mulai kerjakan soal untuk melihat hasilnya ya!</p>
    </div>
    @endif

    <!-- ===== ACTIONS ===== -->
    <div class="flex flex-wrap justify-center gap-4 pt-2">
        <a href="{{ route('student.soal.list') }}" class="btn-premium">← Kerjakan Soal Lagi</a>
        <a href="{{ route('student.dashboard') }}" class="btn-outline-brand !text-sm !py-3.5"><x-icon name="home" />  Ke Dashboard</a>
    </div>
</div>

<script>
// animate ring after paint
window.addEventListener('load', function () {
    const ring = document.querySelector('.score-ring circle.progress');
    if (!ring) return;
    setTimeout(() => { ring.style.strokeDashoffset = ring.dataset.targetOffset; }, 350);
});
</script>
@endsection
