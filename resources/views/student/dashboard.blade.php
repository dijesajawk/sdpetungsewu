@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<style>
    .student-hero {
        background:
            radial-gradient(500px 260px at 90% -10%, rgba(168,85,247,.45), transparent 62%),
            radial-gradient(420px 220px at 5% 110%, rgba(34,211,238,.35), transparent 60%),
            linear-gradient(135deg, #3b2a86, #1e1b4b);
    }
</style>

<div class="play-font space-y-9">

    <!-- ===== HERO ===== -->
    <div class="student-hero relative overflow-hidden rounded-[30px] p-8 md:p-12 text-white anim-fade-up shadow-[0_34px_90px_-26px_rgba(59,42,134,.6)]">
        <span class="absolute -right-6 top-6 text-[120px] opacity-15 select-none anim-float">🎓</span>
        <span class="absolute left-[42%] -bottom-10 text-[110px] opacity-10 select-none anim-float-x">⭐</span>
        <canvas data-particles data-particles-count="22" style="position:absolute;inset:0;"></canvas>

        <div class="relative z-10 text-center max-w-2xl mx-auto">
            <div class="text-6xl anim-wiggle inline-block">👋</div>
            <h1 class="font-display font-extrabold text-3xl md:text-5xl mt-4">
                Halo, <span class="gradient-text-gold">{{ auth()->user()->name }}</span>!
            </h1>
            <p class="text-white/70 mt-3 text-lg">Yuk semangat belajar hari ini — petualangan pengetahuan menantimu! 🚀</p>

            <div class="mt-7 flex flex-wrap justify-center gap-3.5">
                <a href="{{ route('student.soal.list') }}" data-magnetic class="btn-premium btn-gold !text-[#422006]">➡️ Lihat Semua Soal</a>
                <a href="{{ route('student.result') }}" class="btn-outline-light">📊 Lihat Hasil</a>
            </div>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6" data-stagger>
        <div class="play-card p-7 text-center group" data-tilt data-tilt-max="6">
            <div class="w-16 h-16 mx-auto rounded-2xl grid place-items-center text-3xl bg-violet-100 border border-violet-200 group-hover:rotate-6 group-hover:scale-110 transition-transform duration-500">📝</div>
            <p class="font-display font-extrabold text-5xl gradient-text mt-4"><span data-count="{{ $totalSoalJawab }}">0</span></p>
            <p class="font-bold text-slate-500 mt-1.5 tracking-wide">Soal Terjawab</p>
        </div>

        <div class="play-card p-7 text-center group" data-tilt data-tilt-max="6" style="transition-delay:.08s">
            <div class="w-16 h-16 mx-auto rounded-2xl grid place-items-center text-3xl bg-emerald-100 border border-emerald-200 group-hover:-rotate-6 group-hover:scale-110 transition-transform duration-500">✅</div>
            <p class="font-display font-extrabold text-5xl mt-4" style="background:linear-gradient(135deg,#10b981,#059669);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent"><span data-count="{{ $benarJawab }}">0</span></p>
            <p class="font-bold text-slate-500 mt-1.5 tracking-wide">Jawaban Benar</p>
        </div>

        <div class="play-card p-7 text-center group" data-tilt data-tilt-max="6" style="transition-delay:.16s">
            <div class="w-16 h-16 mx-auto rounded-2xl grid place-items-center text-3xl bg-amber-100 border border-amber-200 group-hover:rotate-6 group-hover:scale-110 transition-transform duration-500">🏆</div>
            <p class="font-display font-extrabold text-5xl mt-4" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent"><span data-count="{{ round($skorRataRata) }}" data-suffix="%">0%</span></p>
            <p class="font-bold text-slate-500 mt-1.5 tracking-wide">Nilai Rata-rata</p>
        </div>
    </div>

    <!-- ===== KATEGORI ===== -->
    @if($kategori->count() > 0)
    <section>
        <div class="flex items-center justify-between mb-6 reveal">
            <h2 class="font-display font-bold text-2xl text-slate-800 flex items-center gap-3">
                <span class="anim-wiggle inline-block text-3xl">📚</span> Pilih Kategori
            </h2>
            <a href="{{ route('student.soal.list') }}" class="text-sm font-extrabold gradient-text hover:opacity-70 transition">Lihat semua →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5" data-stagger>
            @foreach($kategori as $k)
            <a href="{{ route('student.soal.list', ['kategori_id' => $k->id]) }}" class="play-card p-6 text-center group card-shine">
                <div class="w-full aspect-square rounded-2xl grid place-items-center bg-gradient-to-br from-violet-100 via-indigo-50 to-cyan-100 group-hover:from-violet-200 group-hover:to-cyan-200 transition-colors duration-500 text-5xl group-hover:scale-105 group-hover:-rotate-3 transition-transform">
                    📖
                </div>
                <p class="font-bold text-slate-700 mt-4 leading-snug">{{ $k->nama_kategori }}</p>
                <span class="inline-block mt-3 px-5 py-2 rounded-full text-sm font-bold text-white bg-gradient-to-r from-violet-500 to-indigo-500 shadow-[0_8px_20px_-6px_rgba(124,92,255,.5)] group-hover:shadow-[0_12px_28px_-6px_rgba(124,92,255,.65)] group-hover:-translate-y-0.5 transition-all">Mulai →</span>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- ===== KELAS ===== -->
    @if($kelas->count() > 0)
    <section>
        <div class="flex items-center justify-between mb-6 reveal">
            <h2 class="font-display font-bold text-2xl text-slate-800 flex items-center gap-3">
                <span class="anim-bounce inline-block text-3xl">🏫</span> Pilih Kelas
            </h2>
            <a href="{{ route('student.soal.list') }}" class="text-sm font-extrabold gradient-text hover:opacity-70 transition">Lihat semua →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5" data-stagger>
            @foreach($kelas as $k)
            <a href="{{ route('student.soal.list', ['kelas_id' => $k->id]) }}" class="play-card p-6 text-center group card-shine">
                <div class="w-full aspect-square rounded-2xl grid place-items-center bg-gradient-to-br from-cyan-100 via-sky-50 to-emerald-100 group-hover:from-cyan-200 group-hover:to-emerald-200 transition-colors duration-500 text-5xl group-hover:scale-105 group-hover:rotate-3 transition-transform">
                    👨‍🎓
                </div>
                <p class="font-bold text-slate-700 mt-4 leading-snug">{{ $k->nama_kelas }}</p>
                <span class="inline-block mt-3 px-5 py-2 rounded-full text-sm font-bold text-white bg-gradient-to-r from-cyan-500 to-emerald-500 shadow-[0_8px_20px_-6px_rgba(34,211,238,.5)] group-hover:shadow-[0_12px_28px_-6px_rgba(34,211,238,.65)] group-hover:-translate-y-0.5 transition-all">Pilih →</span>
            </a>
            @endforeach
        </div>
    </section>
    @else
    <div class="empty-state reveal-scale">
        <span class="empty-emoji">📝</span>
        <h3 class="font-display font-bold text-xl text-slate-800 mt-3">Belum Ada Soal</h3>
        <p class="text-slate-400 mt-1">Tunggu guru untuk menambahkan soal latihan ya! 😊</p>
    </div>
    @endif
</div>
@endsection
