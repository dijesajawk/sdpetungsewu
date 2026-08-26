@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<style>
    .guru-hero {
        background:
            radial-gradient(520px 260px at 92% -10%, rgba(124,92,255,.4), transparent 62%),
            radial-gradient(420px 220px at 5% 110%, rgba(34,211,238,.3), transparent 60%),
            linear-gradient(135deg, #141b3c, #0b1230);
    }
    .diff-card { transition: transform .45s cubic-bezier(.22,1,.36,1), box-shadow .45s ease; }
    .diff-card:hover { transform: translateY(-6px); }
</style>

<div class="space-y-9">

    <!-- ===== HERO ===== -->
    <div class="guru-hero relative overflow-hidden rounded-[28px] p-8 md:p-10 text-white anim-fade-up shadow-[0_30px_80px_-24px_rgba(20,27,60,.55)]">
        <span class="absolute -right-8 -bottom-14 text-[170px] opacity-[.06] select-none anim-float">👩‍🏫</span>

        <div class="relative z-10 flex flex-wrap items-center gap-7">
            <div class="avatar-ring shrink-0">
                @if (auth()->user()->foto)
                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}" class="h-[84px] w-[84px]">
                @else
                    <div class="h-[84px] w-[84px] grid place-items-center bg-gradient-to-br from-violet-500 to-cyan-400 font-display font-extrabold text-4xl">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
            </div>
            <div class="flex-1 min-w-[240px]">
                <span class="chip-pill chip-glass">👋 Selamat datang kembali</span>
                <h1 class="font-display font-extrabold text-3xl md:text-4xl mt-3.5">{{ auth()->user()->name }}</h1>
                <p class="text-white/55 mt-2">Semangat mengajar hari ini! ✨</p>
            </div>
            <a href="{{ route('guru.soal.create') }}" data-magnetic class="btn-premium shrink-0">＋ Tambah Soal</a>
        </div>
    </div>

    <!-- ===== STATS + QUICK ACTIONS ===== -->
    <div class="grid lg:grid-cols-[1fr_2fr] gap-6">

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-5" data-stagger>
            <div class="stat-card group !p-5">
                <div class="stat-orb" style="background:#7c5cff"></div>
                <p class="text-2xl">📚</p>
                <p class="font-display font-extrabold text-[42px] leading-none mt-3"><span data-count="{{ $soalCount }}">0</span></p>
                <p class="text-xs font-extrabold tracking-wider text-slate-400 uppercase mt-1.5">Total Soal</p>
            </div>
            <div class="stat-card group !p-5">
                <div class="stat-orb" style="background:#22c55e"></div>
                <p class="text-2xl">🛡️</p>
                <p class="font-display font-extrabold text-lg leading-tight text-emerald-500 mt-4">Terverifikasi ✓</p>
                <p class="text-xs font-extrabold tracking-wider text-slate-400 uppercase mt-1.5">Status Akun</p>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="grid sm:grid-cols-2 gap-5" data-stagger>
            <a href="{{ route('guru.quiz.start') }}" class="group relative overflow-hidden rounded-[22px] p-6 bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-[0_18px_44px_-14px_rgba(16,185,129,.55)] hover:-translate-y-1.5 hover:shadow-[0_28px_60px_-16px_rgba(16,185,129,.65)] transition-all duration-500 flex flex-col justify-between card-shine">
                <span class="absolute -right-4 -top-6 text-7xl opacity-15 group-hover:scale-125 group-hover:rotate-12 transition-transform duration-700">🎯</span>
                <span class="w-11 h-11 grid place-items-center rounded-xl bg-white/20 backdrop-blur text-xl">▶</span>
                <div class="mt-6">
                    <p class="font-display font-bold text-lg">Quiziz — Atur & Mulai</p>
                    <p class="text-white/75 text-sm mt-1">Buat sesi quiz untuk kelas Anda</p>
                </div>
            </a>

            <form action="{{ route('guru.quiz.create_immediate') }}" method="POST" class="contents">
                @csrf
                <input type="hidden" name="difficulty_type" value="campuran">
                <button type="submit" class="group relative overflow-hidden rounded-[22px] p-6 bg-gradient-to-br from-sky-500 to-indigo-600 text-white shadow-[0_18px_44px_-14px_rgba(56,189,248,.55)] hover:-translate-y-1.5 hover:shadow-[0_28px_60px_-16px_rgba(56,189,248,.65)] transition-all duration-500 flex flex-col justify-between card-shine w-full text-left">
                    <span class="absolute -right-4 -top-6 text-7xl opacity-15 group-hover:scale-125 group-hover:-rotate-12 transition-transform duration-700">⚡</span>
                    <span class="w-11 h-11 grid place-items-center rounded-xl bg-white/20 backdrop-blur text-xl">⚡</span>
                    <div class="mt-6">
                        <p class="font-display font-bold text-lg">Jalankan Soal Langsung</p>
                        <p class="text-white/75 text-sm mt-1">Tanpa sesi — tampilkan sekarang</p>
                    </div>
                </button>
            </form>

            <a href="{{ route('guru.soal.list') }}" class="sm:col-span-2 group relative overflow-hidden rounded-[22px] px-6 py-5 bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 text-white shadow-[0_18px_50px_-16px_rgba(124,92,255,.6)] hover:-translate-y-1 hover:shadow-[0_28px_66px_-16px_rgba(124,92,255,.7)] transition-all duration-500 flex items-center justify-between card-shine">
                <span class="flex items-center gap-4">
                    <span class="text-2xl anim-wiggle inline-block">📋</span>
                    <span class="font-display font-bold text-base md:text-lg">Lihat Semua Soal Saya →</span>
                </span>
                <span class="opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-500 text-xl">✨</span>
            </a>
        </div>
    </div>

    <!-- ===== SOAL PER TINGKAT KESULITAN ===== -->
    <div class="grid lg:grid-cols-3 gap-6" data-stagger>

        {{-- MUDAH --}}
        <section class="diff-easy diff-card premium-card p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 grid place-items-center rounded-xl text-lg" style="background:var(--accent-soft); color:var(--accent)">🟢</span>
                    <h2 class="font-display font-bold text-slate-900">Mudah</h2>
                </div>
                <span class="chip-pill !py-1 !px-3.5 text-sm" style="background:var(--accent-soft); color:var(--accent); font-weight:800">{{ $soalMudah->count() }}</span>
            </div>
            <input type="text" id="searchMudah" onkeyup="filterSoal('mudah')" placeholder="🔍 Cari soal mudah..."
                class="input-premium !pl-4 !py-2.5 !text-sm mb-4">

            <div id="mudahList" class="space-y-3 flex-1 overflow-y-auto max-h-[380px] pr-1 nice-scroll">
                @if ($soalMudah->count() > 0)
                    @foreach ($soalMudah as $soal)
                        <div class="soal-item soal-mudah group rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50/60 to-white p-4 hover:border-emerald-300 hover:shadow-md hover:translate-x-1 transition-all duration-500 cursor-default">
                            <p class="font-semibold text-sm text-slate-700 leading-snug">{{ Str::limit($soal->pertanyaan, 80) }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="chip-pill bg-indigo-50 !text-indigo-600 !text-[11px] !py-1">🏫 {{ $soal->kelas->nama_kelas }}</span>
                                <span class="chip-pill bg-amber-50 !text-amber-600 !text-[11px] !py-1">🏷️ {{ ucfirst($soal->kategori->nama_kategori ?? 'Tanpa kategori') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state !py-8">
                        <span class="empty-emoji !text-2xl">📝</span>
                        <p class="text-sm text-slate-400 mt-2">Belum ada soal mudah.</p>
                    </div>
                @endif
            </div>
        </section>

        {{-- NORMAL --}}
        <section class="diff-normal diff-card premium-card p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 grid place-items-center rounded-xl text-lg" style="background:var(--accent-soft); color:var(--accent)">🟡</span>
                    <h2 class="font-display font-bold text-slate-900">Normal</h2>
                </div>
                <span class="chip-pill !py-1 !px-3.5 text-sm" style="background:var(--accent-soft); color:var(--accent); font-weight:800">{{ $soalNormal->count() }}</span>
            </div>
            <input type="text" id="searchNormal" onkeyup="filterSoal('normal')" placeholder="🔍 Cari soal normal..."
                class="input-premium !pl-4 !py-2.5 !text-sm mb-4">

            <div id="normalList" class="space-y-3 flex-1 overflow-y-auto max-h-[380px] pr-1 nice-scroll">
                @if ($soalNormal->count() > 0)
                    @foreach ($soalNormal as $soal)
                        <div class="soal-item soal-normal group rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50/60 to-white p-4 hover:border-amber-300 hover:shadow-md hover:translate-x-1 transition-all duration-500 cursor-default">
                            <p class="font-semibold text-sm text-slate-700 leading-snug">{{ Str::limit($soal->pertanyaan, 80) }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="chip-pill bg-indigo-50 !text-indigo-600 !text-[11px] !py-1">🏫 {{ $soal->kelas->nama_kelas }}</span>
                                <span class="chip-pill bg-amber-50 !text-amber-600 !text-[11px] !py-1">🏷️ {{ ucfirst($soal->kategori->nama_kategori ?? 'Tanpa kategori') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state !py-8">
                        <span class="empty-emoji !text-2xl">📝</span>
                        <p class="text-sm text-slate-400 mt-2">Belum ada soal normal.</p>
                    </div>
                @endif
            </div>
        </section>

        {{-- SULIT --}}
        <section class="diff-hard diff-card premium-card p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 grid place-items-center rounded-xl text-lg" style="background:var(--accent-soft); color:var(--accent)">🔴</span>
                    <h2 class="font-display font-bold text-slate-900">Sulit</h2>
                </div>
                <span class="chip-pill !py-1 !px-3.5 text-sm" style="background:var(--accent-soft); color:var(--accent); font-weight:800">{{ $soalSulit->count() }}</span>
            </div>
            <input type="text" id="searchSulit" onkeyup="filterSoal('sulit')" placeholder="🔍 Cari soal sulit..."
                class="input-premium !pl-4 !py-2.5 !text-sm mb-4">

            <div id="sulitList" class="space-y-3 flex-1 overflow-y-auto max-h-[380px] pr-1 nice-scroll">
                @if ($soalSulit->count() > 0)
                    @foreach ($soalSulit as $soal)
                        <div class="soal-item soal-sulit group rounded-xl border border-rose-100 bg-gradient-to-br from-rose-50/60 to-white p-4 hover:border-rose-300 hover:shadow-md hover:translate-x-1 transition-all duration-500 cursor-default">
                            <p class="font-semibold text-sm text-slate-700 leading-snug">{{ Str::limit($soal->pertanyaan, 80) }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="chip-pill bg-indigo-50 !text-indigo-600 !text-[11px] !py-1">🏫 {{ $soal->kelas->nama_kelas }}</span>
                                <span class="chip-pill bg-amber-50 !text-amber-600 !text-[11px] !py-1">🏷️ {{ ucfirst($soal->kategori->nama_kategori ?? 'Tanpa kategori') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state !py-8">
                        <span class="empty-emoji !text-2xl">📝</span>
                        <p class="text-sm text-slate-400 mt-2">Belum ada soal sulit.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<script>
function filterSoal(difficulty) {
    const searchInput = document.getElementById('search' + difficulty.charAt(0).toUpperCase() + difficulty.slice(1)).value.toLowerCase();
    const soalList = document.getElementById(difficulty + 'List');
    if (!soalList) return;
    const items = soalList.querySelectorAll('.soal-item');
    let shown = 0;
    items.forEach(item => {
        const match = item.textContent.toLowerCase().includes(searchInput);
        item.style.display = match ? '' : 'none';
        if (match) { item.style.animation = 'fadeUp .35s var(--ease-out) both'; item.style.animationDelay = (shown * 40) + 'ms'; shown++; }
    });
}
</script>
@endsection
