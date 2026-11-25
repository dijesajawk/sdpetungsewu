@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    .admin-hero {
        position: relative;
        background:
            radial-gradient(520px 280px at 92% -12%, rgba(139,120,255,.34), transparent 64%),
            radial-gradient(420px 240px at 6% 112%, rgba(34,211,238,.22), transparent 62%),
            linear-gradient(135deg, #303d76 0%, #273362 55%, #202a54 100%);
        border: 1px solid rgba(255,255,255,.10);
    }
    .admin-hero::after {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,.055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.055) 1px, transparent 1px);
        background-size: 44px 44px;
        -webkit-mask-image: radial-gradient(620px 320px at 88% 8%, #000, transparent 78%);
        mask-image: radial-gradient(620px 320px at 88% 8%, #000, transparent 78%);
    }
</style>

<div class="space-y-9">

    <!-- ===== HERO HEADER ===== -->
    <div class="admin-hero relative overflow-hidden rounded-[28px] p-8 md:p-10 text-white anim-fade-up shadow-[0_30px_80px_-24px_rgba(20,27,60,.55)]">
        <span class="pointer-events-none absolute -right-24 -top-28 h-72 w-72 rounded-full bg-violet-500/25 blur-3xl"></span>
        <span class="pointer-events-none absolute -bottom-28 right-40 h-56 w-56 rounded-full bg-cyan-400/15 blur-3xl"></span>
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-6">
            <div>
                <span class="chip-pill chip-glass reveal"><x-icon name="shield-check" /> Panel Admin</span>
                <h1 class="font-display font-extrabold text-3xl md:text-4xl mt-4">Dashboard <span class="gradient-text">Admin</span></h1>
                <p class="text-white/55 mt-2.5">Kelola verifikasi guru dan pantau aktivitas platform.</p>
            </div>
            <div class="glass-dark rounded-2xl px-6 py-4 border border-white/10 text-center">
                <p class="text-[11px] uppercase tracking-widest text-white/50 font-bold">Total Terdaftar</p>
                <p class="font-display font-extrabold text-3xl gradient-text"><span data-count="{{ count($guruPending) + count($guruVerified) }}">{{ count($guruPending) + count($guruVerified) }}</span></p>
            </div>
        </div>
    </div>

    <!-- ===== STAT ROW ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" data-stagger>
        <a href="#pending" class="stat-card group block">
            <div class="stat-orb" style="background:#ffc53d"></div>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl grid place-items-center text-2xl text-amber-400 bg-amber-100 border border-amber-200 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-500"><x-icon name="clock" /></div>
                <div>
                    <p class="text-xs font-extrabold tracking-wider text-slate-400 uppercase">Menunggu Verifikasi</p>
                    <p class="font-display font-extrabold text-4xl text-slate-900 mt-1">{{ count($guruPending) }} <span class="text-base font-bold text-amber-500">guru</span></p>
                </div>
            </div>
        </a>

        <div class="stat-card group">
            <div class="stat-orb" style="background:#22c55e"></div>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl grid place-items-center text-2xl bg-emerald-100 border border-emerald-200 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500"><x-icon name="check-circle" /> </div>
                <div>
                    <p class="text-xs font-extrabold tracking-wider text-slate-400 uppercase">Guru Terverifikasi</p>
                    <p class="font-display font-extrabold text-4xl text-slate-900 mt-1"><span data-count="{{ count($guruVerified) }}">{{ count($guruVerified) }}</span> <span class="text-base font-bold text-emerald-500">guru</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== PENDING ===== -->
    <section id="pending">
        <div class="premium-card premium-card--gradient-ring p-6 md:p-8 !rounded-[24px]">
            <div class="flex items-center gap-3.5 mb-6">
                <span class="w-11 h-11 grid place-items-center rounded-xl bg-amber-100 border border-amber-200 text-xl anim-wiggle"><x-icon name="bell" /> </span>
                <div>
                    <h2 class="font-display font-bold text-xl text-slate-900">Menunggu Verifikasi</h2>
                    <p class="text-sm text-slate-400">Tinjau dan setujui pendaftaran guru baru</p>
                </div>
            </div>

            @if (count($guruPending) > 0)
                <div class="hidden md:block overflow-x-auto">
                    <table class="modern-table min-w-[720px]">
                        <thead>
                            <tr>
                                <th>Guru</th><th>Email</th><th>Terdaftar</th><th class="!text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody data-stagger>
                            @foreach ($guruPending as $guru)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3.5">
                                            @if ($guru->foto)
                                                <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->name }}" class="h-11 w-11 rounded-full object-cover ring-2 ring-amber-200">
                                            @else
                                                <div class="h-11 w-11 rounded-full grid place-items-center bg-gradient-to-br from-violet-500 to-cyan-400 text-white font-extrabold ring-2 ring-violet-100">{{ strtoupper(substr($guru->name, 0, 1)) }}</div>
                                            @endif
                                            <span class="font-bold text-slate-800">{{ $guru->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-slate-500">{{ $guru->email }}</td>
                                    <td><span class="chip-pill bg-slate-100 !py-1.5 text-slate-600">{{ $guru->created_at->format('d M Y') }}</span></td>
                                    <td>
                                        <div class="flex justify-end gap-2">
                                            <form action="{{ route('admin.verify', $guru, false) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn-premium !py-2 !px-5 !text-sm" onclick="this.closest('tr').style.transition='all .4s'; this.closest('tr').style.opacity='.35'"><x-icon name="check" />  Verifikasi</button>
                                            </form>
                                            <form action="{{ route('admin.reject', $guru, false) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menolak guru ini?')"><x-icon name="x" />  Tolak</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden space-y-3">
                    @foreach ($guruPending as $guru)
                        <article class="rounded-2xl border border-amber-100 bg-amber-50/40 p-4">
                            <div class="flex items-center gap-3">
                                @if ($guru->foto)
                                    <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->name }}" class="h-12 w-12 rounded-full object-cover ring-2 ring-amber-200">
                                @else
                                    <div class="h-12 w-12 rounded-full grid place-items-center bg-gradient-to-br from-violet-500 to-cyan-400 text-white font-extrabold">{{ strtoupper(substr($guru->name, 0, 1)) }}</div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 truncate">{{ $guru->name }}</p>
                                    <p class="text-sm text-slate-500 truncate">{{ $guru->email }}</p>
                                    <p class="text-xs text-slate-400 mt-1">Terdaftar {{ $guru->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-4">
                                <form action="{{ route('admin.verify', $guru, false) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-premium !py-2.5 !px-3 !text-xs w-full"><x-icon name="check" />  Verifikasi</button>
                                </form>
                                <form action="{{ route('admin.reject', $guru, false) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-danger btn-xs w-full"><x-icon name="x" />  Tolak</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <span class="empty-emoji"><x-icon name="sparkles" /> </span>
                    <h3 class="font-display font-bold text-lg text-slate-800 mt-3">Semua guru sudah diverifikasi!</h3>
                    <p class="text-sm text-slate-400 mt-1">Tidak ada antrean verifikasi saat ini.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- ===== VERIFIED ===== -->
    <section class="reveal delay-2">
        <div class="premium-card p-6 md:p-8 !rounded-[24px]">
            <div class="flex items-center gap-3.5 mb-7">
                <span class="w-11 h-11 grid place-items-center rounded-xl bg-emerald-100 border border-emerald-200 text-xl anim-bounce"><x-icon name="check-circle" /> </span>
                <div>
                    <h2 class="font-display font-bold text-xl text-slate-900">Guru Terverifikasi</h2>
                    <p class="text-sm text-slate-400">Guru yang sudah aktif mengelola soal</p>
                </div>
            </div>

            @if (count($guruVerified) > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5" data-stagger>
                    @foreach ($guruVerified as $guru)
                        <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-gradient-to-br from-slate-50 to-white p-5 hover:border-emerald-300 hover:shadow-[0_18px_44px_-16px_rgba(16,185,129,.3)] transition-all duration-500 hover:-translate-y-1.5">
                            <div class="absolute top-4 right-4 w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_0_4px_rgba(52,211,153,.2)]"></div>
                            <div class="flex items-center gap-4">
                                @if ($guru->foto)
                                    <img src="{{ asset('storage/' . $guru->foto) }}" alt="" class="h-[52px] w-[52px] rounded-full object-cover ring-2 ring-emerald-100 group-hover:scale-105 group-hover:rotate-3 transition-transform duration-500">
                                @else
                                    <div class="h-[52px] w-[52px] rounded-full grid place-items-center bg-gradient-to-br from-emerald-400 to-cyan-500 text-white font-extrabold text-lg ring-2 ring-emerald-100 group-hover:scale-105 group-hover:-rotate-6 transition-transform duration-500">{{ strtoupper(substr($guru->name, 0, 1)) }}</div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 truncate">{{ $guru->name }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $guru->email }}</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-dashed border-slate-200 flex items-center justify-between text-xs">
                                <span class="text-slate-400 font-semibold">Diverifikasi {{ $guru->updated_at->format('d M Y') }}</span>
                                <span class="chip-pill bg-emerald-50 text-emerald-600 !py-1 !px-3 !text-[10px]">AKTIF <x-icon name="check" /> </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <span class="empty-emoji"><x-icon name="inbox" /> </span>
                    <h3 class="font-display font-bold text-lg text-slate-800 mt-3">Belum ada guru terverifikasi</h3>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
