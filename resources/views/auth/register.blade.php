@extends('layouts.app')

@section('title', 'Registrasi')

@section('content')
<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-6">
    <div class="w-full max-w-5xl grid lg:grid-cols-2 rounded-[28px] overflow-hidden shadow-[0_40px_100px_-30px_rgba(23,23,60,.45)] anim-zoom-in">

        <!-- ===== LEFT : Brand panel ===== -->
        <div class="relative hidden lg:flex flex-col justify-between p-10 overflow-hidden bg-gradient-to-br from-[#2c3a72] to-[#222c5c] text-white">
            <div class="aurora-blob cyan !opacity-60" style="position:absolute"></div>
            <div class="aurora-blob gold !opacity-30" style="position:absolute; animation-duration:26s"></div>
            <canvas data-particles data-particles-count="26" style="position:absolute;inset:0;"></canvas>

            <div class="relative z-10">
                <a href="{{ url('/') }}" class="flex items-center gap-3 w-fit">
                    <span class="logo-badge"><img src="{{ asset('images/logo-sekolah.png') }}" alt=""></span>
                    <span>
                        <span class="font-display font-bold block">Web Edukasi</span>
                        <span class="text-[11px] tracking-[.18em] uppercase text-cyan-300/80 font-semibold">SDN 2 Petungsewu</span>
                    </span>
                </a>
            </div>

            <div class="relative z-10">
                <h2 class="font-display font-extrabold text-4xl leading-tight reveal-left">
                    Bergabung dengan<br><span class="gradient-text-gold">Guru Hebat! <x-icon name="star" /> </span>
                </h2>
                <p class="text-white/55 mt-4 leading-relaxed max-w-sm">Daftar sebagai guru dan buat soal interaktif untuk siswa Anda. Akun akan diverifikasi oleh admin.</p>

                <div class="mt-8 flex items-center gap-4 glass-dark rounded-2xl px-5 py-4 border border-white/10 reveal-left delay-2 max-w-sm">
                    <span class="text-3xl anim-wiggle"><x-icon name="shield-check" /> </span>
                    <p class="text-sm text-white/70 leading-snug"><b class="text-white">Gratis & Aman.</b> Setiap guru diverifikasi admin demi kualitas konten belajar.</p>
                </div>
            </div>

            <p class="relative z-10 text-xs text-white/35">© {{ date('Y') }} Web Edukasi • Wagir, Malang</p>
        </div>

        <!-- ===== RIGHT : Form ===== -->
        <div class="auth-form-panel bg-white p-8 sm:p-12 flex flex-col justify-center">
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="" class="h-11 w-auto object-contain drop-shadow-sm">
                <span class="font-display font-bold text-slate-900 text-lg">Web Edukasi</span>
            </div>

            <h1 class="font-display font-extrabold text-3xl text-slate-900">Daftar Akun Guru</h1>
            <p class="text-slate-500 mt-2 text-sm">Lengkapi data di bawah untuk membuat akun.</p>

            <form action="/register" method="POST" enctype="multipart/form-data" class="mt-7 space-y-4.5 space-y-4" id="regForm">
                @csrf

                <!-- Foto upload -->
                <div class="flex items-center gap-5 p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 hover:border-indigo-300 transition-colors">
                    <label for="foto" class="cursor-pointer group shrink-0" title="Unggah foto profil">
                        <div id="fotoPreview" class="w-[74px] h-[74px] rounded-2xl grid place-items-center text-3xl bg-gradient-to-br from-violet-100 to-cyan-100 border-2 border-dashed border-indigo-200 group-hover:border-indigo-400 group-hover:scale-105 transition-all overflow-hidden"><x-icon name="camera" /> </div>
                    </label>
                    <div>
                        <p class="text-sm font-bold text-slate-700">Foto Profil</p>
                        <p class="text-xs text-slate-500 mt-0.5">JPG, PNG, GIF — maksimal 2MB</p>
                        <label for="foto" class="inline-block mt-2 text-xs font-extrabold gradient-text cursor-pointer hover:opacity-75 transition">Pilih Foto →</label>
                    </div>
                    <input type="file" id="foto" name="foto" accept="image/*" required class="hidden">
                </div>
                @error('foto') <p class="text-rose-500 text-xs font-semibold -mt-2">{{ $message }}</p> @enderror

                <div>
                    <label for="name" class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-1.5">
                        <x-icon name="user" class="w-[16px] h-[16px]" />
                        Nama Lengkap
                    </label>
                    <div class="input-icon-wrap">
                        <span class="input-icon" aria-hidden="true"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg></span>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Nama Anda" class="input-premium">
                    </div>
                    @error('name') <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5"><x-icon name="mail" />  Email</label>
                    <div class="input-icon-wrap">
                        <span class="input-icon"><x-icon name="mail" class="w-[17px] h-[17px]" /></span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="input-premium">
                    </div>
                    @error('email') <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5"><x-icon name="lock" />  Password</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••" class="input-premium !pl-4">
                        @error('password') <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1.5"><x-icon name="refresh" />  Ulangi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" class="input-premium !pl-4">
                    </div>
                </div>

                <div class="rounded-xl bg-cyan-50 border border-cyan-100 px-4 py-3 flex items-start gap-2.5">
                    <span class="text-lg leading-none mt-0.5 text-cyan-600"><x-icon name="help-circle" /></span>
                    <p class="text-xs text-cyan-900 leading-relaxed">Anda terdaftar sebagai <b>Guru</b>. Akun aktif setelah diverifikasi oleh admin.</p>
                </div>

                <button type="submit" id="regBtn" class="btn-premium w-full !py-3.5 text-base">
                    Buat Akun <x-icon name="sparkles" /> 
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-extrabold gradient-text hover:opacity-80 transition">Login di sini</a>
            </p>
        </div>
    </div>
</div>

<script>
// Live photo preview
document.getElementById('foto').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
        document.getElementById('fotoPreview').innerHTML =
            '<img src="' + ev.target.result + '" class="w-full h-full object-cover">';
    };
    reader.readAsDataURL(file);
});

document.getElementById('regForm').addEventListener('submit', function () {
    const b = document.getElementById('regBtn');
    b.disabled = true;
    b.innerHTML = '<span class="inline-block w-5 h-5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Mendaftarkan...';
});
</script>
@endsection
