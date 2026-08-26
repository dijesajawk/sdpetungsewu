@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-6">
    <div class="w-full max-w-5xl grid lg:grid-cols-2 rounded-[28px] overflow-hidden shadow-[0_40px_100px_-30px_rgba(23,23,60,.45)] anim-zoom-in">

        <!-- ===== LEFT : Brand panel ===== -->
        <div class="relative hidden lg:flex flex-col justify-between p-10 overflow-hidden bg-[#0b1230] text-white">
            <div class="aurora-blob violet !opacity-60" style="position:absolute"></div>
            <div class="aurora-blob cyan !opacity-50" style="position:absolute; animation-duration:24s"></div>
            <canvas data-particles data-particles-count="26" style="position:absolute;inset:0;"></canvas>

            <div class="relative z-10">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group w-fit">
                    <span class="logo-badge"><img src="{{ asset('images/logo-sekolah.jpg') }}" alt=""></span>
                    <span>
                        <span class="font-display font-bold block">Web Edukasi</span>
                        <span class="text-[11px] tracking-[.18em] uppercase text-cyan-300/80 font-semibold">SDN 2 Petungsewu</span>
                    </span>
                </a>
            </div>

            <div class="relative z-10">
                <h2 class="font-display font-extrabold text-4xl leading-tight reveal-left">
                    Selamat Datang<br><span class="gradient-text">Kembali! 👋</span>
                </h2>
                <p class="text-white/55 mt-4 leading-relaxed max-w-sm">Masuk untuk mengelola soal, menjalankan quiz interaktif, dan memantau perkembangan belajar.</p>

                <div class="mt-8 space-y-3.5 max-w-sm">
                    @foreach([['🚀','Quiz real-time di kelas'],['📊','Rekap nilai otomatis'],['🛡️','Aman & terverifikasi']] as [$ic,$tx])
                        <div class="flex items-center gap-3 glass-dark rounded-xl px-4 py-3 reveal-left delay-2 border border-white/10">
                            <span class="w-9 h-9 grid place-items-center rounded-lg bg-white/10 text-lg">{{ $ic }}</span>
                            <span class="text-sm font-semibold text-white/80">{{ $tx }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="relative z-10 text-xs text-white/35">© {{ date('Y') }} Web Edukasi • Wagir, Malang</p>
        </div>

        <!-- ===== RIGHT : Form ===== -->
        <div class="bg-white p-8 sm:p-12 flex flex-col justify-center">
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <img src="{{ asset('images/logo-sekolah.jpg') }}" alt="" class="h-11 w-11 rounded-xl object-cover ring-2 ring-indigo-100">
                <span class="font-display font-bold text-slate-900 text-lg">Web Edukasi</span>
            </div>

            <h1 class="font-display font-extrabold text-3xl text-slate-900">Masuk Akun</h1>
            <p class="text-slate-500 mt-2 text-sm">Silakan login menggunakan email dan password Anda.</p>

            <form action="{{ url('/login') }}" method="POST" class="mt-8 space-y-5" id="loginForm">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">📧 Email</label>
                    <div class="input-icon-wrap">
                        <span class="input-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="m2 7 10 6 10-6"/></svg></span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                            class="input-premium" autocomplete="email">
                    </div>
                    @error('email') <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">🔒 Password</label>
                    <div class="input-icon-wrap">
                        <span class="input-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="input-premium pr-16" autocomplete="current-password">
                        <button type="button" onclick="togglePw('password', this)" title="Lihat password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition p-1 text-xs font-bold">LIHAT</button>
                    </div>
                    @error('password') <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                </div>

                <button type="submit" id="loginBtn" class="btn-premium w-full !py-3.5 text-base">
                    Masuk Sekarang →
                </button>
            </form>

            <div class="flex items-center gap-4 my-7">
                <span class="h-px bg-slate-200 flex-1"></span>
                <span class="text-xs text-slate-400 font-bold tracking-wider">ATAU</span>
                <span class="h-px bg-slate-200 flex-1"></span>
            </div>

            <p class="text-center text-sm text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-extrabold gradient-text hover:opacity-80 transition">Daftar sebagai Guru</a>
            </p>
        </div>
    </div>
</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    btn.textContent = show ? 'SEMBUNYI' : 'LIHAT';
}
document.getElementById('loginForm').addEventListener('submit', function () {
    const b = document.getElementById('loginBtn');
    b.disabled = true;
    b.innerHTML = '<span class="inline-block w-5 h-5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Memproses...';
});
</script>
@endsection
