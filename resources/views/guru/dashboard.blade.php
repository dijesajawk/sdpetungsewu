@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<style>
    .guru-hero {
        position: relative;
        background:
            radial-gradient(520px 280px at 94% -12%, rgba(139,120,255,.34), transparent 64%),
            radial-gradient(420px 240px at 4% 112%, rgba(34,211,238,.22), transparent 62%),
            linear-gradient(135deg, #303d76 0%, #273362 55%, #202a54 100%);
        border: 1px solid rgba(255,255,255,.10);
    }
    .guru-hero::after {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,.055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.055) 1px, transparent 1px);
        background-size: 44px 44px;
        -webkit-mask-image: radial-gradient(620px 320px at 90% 8%, #000, transparent 78%);
        mask-image: radial-gradient(620px 320px at 90% 8%, #000, transparent 78%);
    }
    .guru-hero .hero-action { box-shadow: 0 16px 38px -12px rgba(124,92,255,.85), inset 0 1px 0 rgba(255,255,255,.25); }
    .diff-card { transition: transform .45s cubic-bezier(.22,1,.36,1), box-shadow .45s ease; }
    .diff-card:hover { transform: translateY(-6px); }
</style>

<div class="space-y-9">

    <!-- ===== HERO ===== -->
    <div class="guru-hero relative overflow-hidden rounded-[28px] p-8 md:p-10 text-white anim-fade-up shadow-[0_30px_80px_-24px_rgba(20,27,60,.55)]">
        <span class="pointer-events-none absolute -right-24 -top-28 h-72 w-72 rounded-full bg-violet-500/25 blur-3xl"></span>
        <span class="pointer-events-none absolute -bottom-28 right-40 h-56 w-56 rounded-full bg-cyan-400/15 blur-3xl"></span>

        <div class="relative z-10 grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
            <div class="guru-hero-identity flex items-center gap-5 lg:gap-6">
                <div class="guru-identity-avatar shrink-0">
                    @if (auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}" class="h-[84px] w-[84px]">
                    @else
                        <div class="h-[84px] w-[84px] grid place-items-center bg-gradient-to-br from-violet-500 to-cyan-400 font-display font-extrabold text-4xl">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    @endif
                </div>

                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="chip-pill chip-glass"><x-icon name="user" />  Selamat datang kembali</span>
                        <span class="role-pill role-pill--active"><x-icon name="award" class="!w-3.5 !h-3.5" />  GURU</span>
                    </div>

                    <h1 class="font-display font-extrabold text-3xl md:text-4xl mt-3 leading-tight text-white">{{ auth()->user()->name }}</h1>
                    <p class="text-white/60 mt-2">Semangat mengajar hari ini! <x-icon name="sparkles" /> </p>

                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <a href="{{ route('guru.soal.create') }}" data-magnetic class="btn-premium hero-action">＋ Tambah Soal</a>
                        <form action="{{ route('logout', [], false) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin keluar dari akun ini?');">
                            @csrf
                            <button type="submit" class="btn-ghost btn-ghost--danger text-sm flex items-center gap-2 font-semibold">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="w-[300px] max-w-full justify-self-center lg:justify-self-end">
                @include('partials.orb-card', ['size' => 'sm'])
            </div>
        </div>
    </div>

    <!-- ===== STATS + QUICK ACTIONS ===== -->
    <div class="grid lg:grid-cols-[1fr_2fr] gap-6">

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-5" data-stagger>
            <div class="stat-card group !p-6 flex flex-col justify-center">
                <div class="stat-orb" style="background:#7c5cff"></div>
                <span class="w-11 h-11 grid place-items-center rounded-xl bg-white/[.06] border border-white/10 text-xl"><x-icon name="book-open" /> </span>
                <p class="font-display font-extrabold text-[42px] leading-none mt-4"><span data-count="{{ $soalCount }}">0</span></p>
                <p class="text-[11px] font-extrabold tracking-[.14em] text-white/45 uppercase mt-2">Total Soal</p>
            </div>
            <div class="stat-card group !p-6 flex flex-col justify-center">
                <div class="stat-orb" style="background:#22c55e"></div>
                <span class="w-11 h-11 grid place-items-center rounded-xl bg-emerald-400/10 border border-emerald-400/25 text-xl"><x-icon name="shield-check" /> </span>
                <p class="font-display font-extrabold text-lg leading-tight text-emerald-400 mt-4 whitespace-nowrap">Terverifikasi <x-icon name="check" /> </p>
                <p class="text-[11px] font-extrabold tracking-[.14em] text-white/45 uppercase mt-2">Status Akun</p>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="grid sm:grid-cols-2 gap-5" data-stagger>
            <a href="{{ route('guru.quiz.start') }}" class="group relative overflow-hidden rounded-[22px] p-6 bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-[0_18px_44px_-14px_rgba(16,185,129,.55)] hover:-translate-y-1.5 hover:shadow-[0_28px_60px_-16px_rgba(16,185,129,.65)] transition-all duration-500 flex flex-col justify-between card-shine">
                <span class="absolute -right-4 -top-6 text-7xl opacity-15 group-hover:scale-125 group-hover:rotate-12 transition-transform duration-700"></span>
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
                    <span class="absolute -right-4 -top-6 text-7xl opacity-15 group-hover:scale-125 group-hover:-rotate-12 transition-transform duration-700"><x-icon name="zap" /> </span>
                    <span class="w-11 h-11 grid place-items-center rounded-xl bg-white/20 backdrop-blur text-xl"><x-icon name="zap" /> </span>
                    <div class="mt-6">
                        <p class="font-display font-bold text-lg">Jalankan Soal Langsung</p>
                        <p class="text-white/75 text-sm mt-1">Tanpa sesi — tampilkan sekarang</p>
                    </div>
                </button>
            </form>

            <a href="{{ route('guru.soal.list') }}" class="sm:col-span-2 group relative overflow-hidden rounded-[22px] px-6 py-5 bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 text-white shadow-[0_18px_50px_-16px_rgba(124,92,255,.6)] hover:-translate-y-1 hover:shadow-[0_28px_66px_-16px_rgba(124,92,255,.7)] transition-all duration-500 flex items-center justify-between card-shine">
                <span class="flex items-center gap-4">
                    <span class="text-2xl anim-wiggle inline-block"><x-icon name="clipboard-list" /> </span>
                    <span class="font-display font-bold text-base md:text-lg">Lihat Semua Soal Saya →</span>
                </span>
                <span class="opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-500 text-xl"><x-icon name="sparkles" /> </span>
            </a>
        </div>
    </div>

    <!-- ===== SOAL PER TINGKAT KESULITAN ===== -->
    <div class="grid lg:grid-cols-3 gap-6" data-stagger>

        {{-- MUDAH --}}
        <section class="diff-easy sep-card sep-card--mudah premium-card p-6 flex flex-col">
            <div class="sep-head">
                <div class="flex items-center gap-3">
                    <span class="sep-dot"></span>
                    <svg class="sep-badge" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-2a3 3 0 0 1 2 2.83 12.4 12.4 0 0 0 6.29-3.77 2 2 0 0 1 1.46-.56A3 3 0 0 1 17 17V9a3 3 0 0 1 1.54-.87 2 2 0 0 0 1.46-.56A12.4 12.4 0 0 0 18 5.17 3 3 0 0 1 20 3H7a2 2 0 0 0-2 1.67z"/><path d="M9 21h6M12 18v3"/></svg>
                    <h2 class="font-display font-bold text-slate-900">Mudah</h2>
                </div>
            </div>
            <div class="sep-meta">
                <span class="sep-count">{{ $soalMudah->count() }}</span>
                <span class="sep-label">soal dalam level ini</span>
            </div>
            <input type="text" id="searchMudah" onkeyup="filterSoal('mudah')" placeholder=" Cari soal mudah..."
                class="input-premium !pl-4 !py-2.5 !text-sm mb-4">

            <div id="mudahList" class="space-y-3 flex-1 overflow-y-auto max-h-[420px] pr-1 nice-scroll scroll-fade">
                @if ($soalMudah->count() > 0)
                    @foreach ($soalMudah as $soal)
                        <div class="soal-item soal-mudah group rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50/60 to-white p-4 hover:border-emerald-300 hover:shadow-md hover:translate-x-1 transition-all duration-500 cursor-default">
                            <p class="font-semibold text-sm text-slate-700 leading-snug">{{ Str::limit($soal->pertanyaan, 80) }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="chip-pill bg-indigo-50 !text-indigo-600 !text-[11px] !py-1"><x-icon name="school" />  {{ $soal->kelas->nama_kelas }}</span>
                                <span class="chip-pill bg-amber-50 !text-amber-600 !text-[11px] !py-1"><x-icon name="tag" />  {{ ucfirst($soal->kategori->nama_kategori ?? 'Tanpa kategori') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state !py-8">
                        <span class="empty-emoji !text-2xl"><x-icon name="file-text" /> </span>
                        <p class="text-sm text-slate-400 mt-2">Belum ada soal mudah.</p>
                    </div>
                @endif
            </div>
        </section>

        {{-- NORMAL --}}
        <section class="diff-normal sep-card sep-card--normal premium-card p-6 flex flex-col">
            <div class="sep-head">
                <div class="flex items-center gap-3">
                    <span class="sep-dot"></span>
                    <svg class="sep-badge" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8v8M8 12h8"/><circle cx="12" cy="12" r="9"/></svg>
                    <h2 class="font-display font-bold text-slate-900">Normal</h2>
                </div>
            </div>
            <div class="sep-meta">
                <span class="sep-count">{{ $soalNormal->count() }}</span>
                <span class="sep-label">soal dalam level ini</span>
            </div>
            <input type="text" id="searchNormal" onkeyup="filterSoal('normal')" placeholder=" Cari soal normal..."
                class="input-premium !pl-4 !py-2.5 !text-sm mb-4">

            <div id="normalList" class="space-y-3 flex-1 overflow-y-auto max-h-[420px] pr-1 nice-scroll scroll-fade">
                @if ($soalNormal->count() > 0)
                    @foreach ($soalNormal as $soal)
                        <div class="soal-item soal-normal group rounded-xl border border-amber-100 bg-gradient-to-br from-amber-50/60 to-white p-4 hover:border-amber-300 hover:shadow-md hover:translate-x-1 transition-all duration-500 cursor-default">
                            <p class="font-semibold text-sm text-slate-700 leading-snug">{{ Str::limit($soal->pertanyaan, 80) }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="chip-pill bg-indigo-50 !text-indigo-600 !text-[11px] !py-1"><x-icon name="school" />  {{ $soal->kelas->nama_kelas }}</span>
                                <span class="chip-pill bg-amber-50 !text-amber-600 !text-[11px] !py-1"><x-icon name="tag" />  {{ ucfirst($soal->kategori->nama_kategori ?? 'Tanpa kategori') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state !py-8">
                        <span class="empty-emoji !text-2xl"><x-icon name="file-text" /> </span>
                        <p class="text-sm text-slate-400 mt-2">Belum ada soal normal.</p>
                    </div>
                @endif
            </div>
        </section>

        {{-- SULIT --}}
        <section class="diff-hard sep-card sep-card--sulit premium-card p-6 flex flex-col">
            <div class="sep-head">
                <div class="flex items-center gap-3">
                    <span class="sep-dot"></span>
                    <svg class="sep-badge" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2 2 20h20L12 2z"/><path d="M12 9v5M12 17h.01"/></svg>
                    <h2 class="font-display font-bold text-slate-900">Sulit</h2>
                </div>
            </div>
            <div class="sep-meta">
                <span class="sep-count">{{ $soalSulit->count() }}</span>
                <span class="sep-label">soal dalam level ini</span>
            </div>
            <input type="text" id="searchSulit" onkeyup="filterSoal('sulit')" placeholder=" Cari soal sulit..."
                class="input-premium !pl-4 !py-2.5 !text-sm mb-4">

            <div id="sulitList" class="space-y-3 flex-1 overflow-y-auto max-h-[420px] pr-1 nice-scroll scroll-fade">
                @if ($soalSulit->count() > 0)
                    @foreach ($soalSulit as $soal)
                        <div class="soal-item soal-sulit group rounded-xl border border-rose-100 bg-gradient-to-br from-rose-50/60 to-white p-4 hover:border-rose-300 hover:shadow-md hover:translate-x-1 transition-all duration-500 cursor-default">
                            <p class="font-semibold text-sm text-slate-700 leading-snug">{{ Str::limit($soal->pertanyaan, 80) }}</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="chip-pill bg-indigo-50 !text-indigo-600 !text-[11px] !py-1"><x-icon name="school" />  {{ $soal->kelas->nama_kelas }}</span>
                                <span class="chip-pill bg-amber-50 !text-amber-600 !text-[11px] !py-1"><x-icon name="tag" />  {{ ucfirst($soal->kategori->nama_kategori ?? 'Tanpa kategori') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state !py-8">
                        <span class="empty-emoji !text-2xl"><x-icon name="file-text" /> </span>
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
