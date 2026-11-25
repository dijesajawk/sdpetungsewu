<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Edukasi — SD Negeri 2 Petungsewu</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}?v={{ filemtime(public_path('assets/css/theme.css')) }}">
    <style>
        .landing-page {
            font-family: var(--font-body);
            background:
                radial-gradient(1100px 620px at 85% -12%, rgba(124,92,255,.22), transparent 62%),
                radial-gradient(950px 560px at -10% 108%, rgba(34,211,238,.18), transparent 62%),
                linear-gradient(180deg, #151d3f 0%, #1a2448 48%, #151d3f 100%) !important;
            color: #e8ebfa; overflow-x: hidden;
        }
        .landing-page .glass-dark {
            background: rgba(43, 54, 104, .5);
            border-color: rgba(255,255,255,.12);
        }
        .landing-page .nav-shell.scrolled .nav-bar {
            background: rgba(35, 45, 88, .9);
            border-color: rgba(255,255,255,.14);
            box-shadow: 0 14px 40px -20px rgba(9, 13, 32, .65);
        }
        .hero-title { font-size: clamp(2.6rem, 7vw, 5rem); line-height: 1.05; letter-spacing: -0.03em; }
        .word-swap { display:inline-block; position:relative; }
        .word-swap span { display:inline-block; transition: all .55s cubic-bezier(.68,-0.4,.27,1.35); }
        .word-swap .leaving { opacity:0; transform: translateY(-90%) rotate(6deg); position:absolute; left:0; top:0; }
        .step-line { background: linear-gradient(90deg, rgba(124,92,255,.6), rgba(34,211,238,.6)); height:2px; }
        .cta-banner {
            background:
                radial-gradient(600px 300px at 15% 20%, rgba(124,92,255,.3), transparent 60%),
                radial-gradient(500px 260px at 85% 80%, rgba(34,211,238,.24), transparent 60%),
                linear-gradient(135deg, #283362, #1d2751);
        }
        @keyframes ringPulse { 0%,100% { transform: scale(1); opacity:.5 } 50% { transform: scale(1.12); opacity:.9 } }
        .pulse-dot { animation: ringPulse 2s ease-in-out infinite; }
        @media (max-width: 640px) {
            .nav-bar { padding-inline: .75rem !important; gap: .5rem !important; }
            .nav-bar .btn-premium, .nav-bar .btn-outline-light { padding-inline: .75rem !important; font-size: .75rem !important; }
        }
    </style>
</head>
<body class="landing-page relative min-h-screen">

    <!-- Background layers -->
    <div class="aurora-field">
        <div class="aurora-blob violet"></div>
        <div class="aurora-blob cyan"></div>
        <div class="aurora-blob gold"></div>
    </div>
    <div class="grid-overlay"></div>
    <canvas data-particles data-particles-count="60" style="position:fixed;inset:0;z-index:1;"></canvas>

    <!-- ===== NAVBAR ===== -->
    <div class="nav-shell relative z-30 px-4 pt-4">
        <nav class="nav-bar max-w-6xl mx-auto rounded-2xl glass-dark px-5 sm:px-7 py-3.5 flex items-center justify-between gap-4 transition-all duration-500">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <span class="logo-badge"><img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo"></span>
                <span class="leading-tight">
                    <span class="font-display font-bold text-white text-lg block">Web Edukasi</span>
                    <span class="text-[11px] font-semibold tracking-[.18em] text-cyan-300/80 uppercase">SDN 2 Petungsewu</span>
                </span>
            </a>
            <div class="hidden sm:flex items-center gap-7 text-sm text-white/70 font-semibold">
                <a href="#fitur" class="nav-link hover:text-white">Fitur</a>
                <a href="#cara" class="nav-link hover:text-white">Cara Kerja</a>
                <a href="#mulai" class="nav-link hover:text-white">Mulai</a>
            </div>
            <div class="flex items-center gap-2.5">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-premium !py-2.5 !px-6 text-sm">Dashboard Admin <x-icon name="zap" /> </a>
                    @elseif (auth()->user()->role === 'student')
                        <a href="{{ route('student.dashboard') }}" class="btn-premium !py-2.5 !px-6 text-sm">Dashboard Siswa <x-icon name="graduation-cap" /> </a>
                    @else
                        <a href="{{ route('guru.dashboard') }}" class="btn-premium !py-2.5 !px-6 text-sm">Dashboard Guru <x-icon name="zap" /> </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-outline-light !py-2.5 !px-6 text-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn-premium !py-2.5 !px-6 text-sm">Daftar Gratis <x-icon name="sparkles" /> </a>
                @endauth
            </div>
        </nav>
    </div>

    <!-- ===== HERO ===== -->
    <header data-spotlight class="relative z-10 max-w-6xl mx-auto px-4 pt-16 pb-10 md:pt-24 md:pb-16">
        <div class="grid lg:grid-cols-[1.15fr_.85fr] gap-14 items-center">

            <!-- Left : copy -->
            <div>
                <div class="chip-pill chip-glass anim-fade-down reveal delay-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 pulse-dot"></span>
                    Platform Belajar Interaktif SD
                </div>

                <h1 class="hero-title font-display font-extrabold mt-6 anim-fade-up delay-2 text-white">
                    Belajar Jadi
                    <span class="block mt-1">
                        <span class="gradient-text">Lebih</span>
                        <span class="word-swap gradient-text-gold font-display"><span id="swapWord">Seru!</span></span>
                    </span>
                </h1>

                <p class="mt-6 text-lg text-white/60 leading-relaxed max-w-xl anim-fade-up delay-3">
                    Kuis interaktif, bank soal per kategori & kelas, dan pengalaman belajar
                    yang menyenangkan untuk siswa — semuanya dalam satu platform elegan.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-4 anim-fade-up delay-4">
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" data-magnetic class="btn-premium text-base">Masuk Dashboard →</a>
                        @elseif (auth()->user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}" data-magnetic class="btn-premium text-base">Masuk Dashboard →</a>
                            <a href="{{ route('student.soal.list') }}" class="btn-outline-light text-base"><x-icon name="file-text" />  Lihat Soal</a>
                        @else
                            <a href="{{ route('guru.dashboard') }}" data-magnetic class="btn-premium text-base">Masuk Dashboard →</a>
                            <a href="{{ route('guru.soal.create') }}" class="btn-outline-light text-base">＋ Buat Soal</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" data-magnetic class="btn-premium text-base">Mulai Sekarang <x-icon name="rocket" /> </a>
                        <a href="{{ route('register') }}" class="btn-outline-light text-base">Daftar sebagai Guru</a>
                    @endauth
                </div>

                <!-- trust row -->
                <div class="mt-12 flex items-center gap-5 anim-fade-up delay-5">
                    <div class="flex -space-x-3">
                        @foreach(['user', 'user', 'graduation-cap', 'user'] as $face)
                            <span class="w-11 h-11 grid place-items-center rounded-full bg-[#2b3668] border-2 border-indigo-300/40 text-lg text-indigo-200 shadow-lg"><x-icon name="{{ $face }}" /></span>
                        @endforeach
                    </div>
                    <div class="text-sm">
                        <p class="font-bold text-white">Guru & Siswa bersama</p>
                        <p class="text-white/50">dalam satu ruang belajar digital</p>
                    </div>
                </div>
            </div>

            <!-- Right : hero visual -->
            <div class="relative hidden lg:block" data-parallax="0.06">
                <div class="relative mx-auto w-[340px] h-[340px]">
                    <!-- orbiting chips -->
                    <span class="absolute z-10 -top-6 -left-16 chip-pill chip-glass anim-float text-base"><x-icon name="book-open" />  Matematika</span>
                    <span class="absolute z-10 top-24 -right-24 chip-pill chip-glass anim-float-x delay-2 text-base" style="animation-delay:.8s"><x-icon name="flask" />  IPA</span>
                    <span class="absolute z-10 -bottom-9 left-4 chip-pill chip-glass anim-float delay-4 text-base" style="animation-delay:1.4s"><x-icon name="book-open" />  Bahasa</span>
                    <span class="absolute z-10 bottom-20 -left-24 chip-pill chip-glass anim-float delay-5 text-base" style="animation-delay:2s"> IPS</span>

                    <!-- center card -->
                    <div class="absolute inset-0 z-[1]">
                        @include('partials.orb-card')
                    </div>

                    <!-- floating score bubble -->
                    <div class="absolute z-10 -bottom-10 right-2 glass-dark rounded-2xl px-5 py-4 anim-float delay-3 shadow-2xl border border-white/10">
                        <p class="text-[11px] uppercase tracking-wider text-white/50 font-bold">Skor Kuis</p>
                        <p class="font-display font-extrabold text-2xl gradient-text-gold"><span data-count="98" data-suffix="%">0%</span></p>
                    </div>
                    <div class="absolute z-10 -top-10 right-6 glass-dark rounded-2xl px-5 py-4 anim-float-x shadow-2xl border border-white/10">
                        <p class="text-[11px] uppercase tracking-wider text-white/50 font-bold">Soal Aktif</p>
                        <p class="font-display font-extrabold text-2xl gradient-text"><span data-count="120" data-suffix="+">0+</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- scroll hint -->
        <div class="hidden md:flex justify-center mt-20">
            <a href="#fitur" class="scroll-mouse opacity-70 hover:opacity-100 transition"><i></i></a>
        </div>
    </header>

    <!-- ===== MARQUEE ===== -->
    <section class="relative z-10 py-6 border-y border-white/8 bg-white/[.03]">
        <div class="marquee">
            <div class="marquee-track font-display text-lg text-white/35 font-semibold whitespace-nowrap">
                @foreach([['sparkles','Kuis Interaktif'],['sparkles','Bank Soal'],['target','Multi Tingkat Kesulitan'],['shield-check','Verifikasi Guru'],['graduation-cap','Tanpa Login untuk Siswa'],['bar-chart','Rekap Nilai Otomatis']] as [$ic,$item])
                    <span class="hover:text-cyan-300 transition-colors"><x-icon name="{{ $ic }}" /> {{ $item }}</span>
                    <span class="hover:text-violet-300 transition-colors"><x-icon name="{{ $ic }}" /> {{ $item }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== FITUR ===== -->
    <section id="fitur" class="relative z-10 max-w-6xl mx-auto px-4 py-24">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <p class="chip-pill chip-glass reveal"><x-icon name="sparkles" />  Fitur Unggulan</p>
            <h2 class="font-display font-extrabold text-3xl md:text-5xl text-white mt-5 reveal delay-1">
                Semua yang Dibutuhkan <span class="gradient-text">Kelas Digital</span>
            </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-7" data-stagger>
            <div class="premium-card premium-card--gradient-ring card-shine !bg-[#232e5e]/85 !border-white/10 backdrop-blur-xl p-8 group">
                <div class="w-16 h-16 rounded-2xl grid place-items-center text-3xl bg-gradient-to-br from-violet-500/30 to-violet-500/5 border border-violet-400/30 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-500"><x-icon name="book-open" /> </div>
                <h3 class="font-display font-bold text-xl text-white mt-6">Belajar Terstruktur</h3>
                <p class="text-white/60 mt-3 leading-relaxed text-[15px]">Akses soal latihan dari guru, dikelompokkan rapi per kelas dan kategori mata pelajaran.</p>
                <div class="mt-6 h-1 w-12 rounded-full bg-gradient-to-r from-violet-500 to-transparent group-hover:w-full transition-all duration-700"></div>
            </div>

            <div class="premium-card premium-card--gradient-ring card-shine !bg-[#232e5e]/85 !border-white/10 backdrop-blur-xl p-8 group" style="transition-delay:.08s">
                <div class="w-16 h-16 rounded-2xl grid place-items-center text-3xl text-cyan-200 bg-gradient-to-br from-cyan-400/30 to-cyan-400/5 border border-cyan-300/30 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500"><x-icon name="target" /></div>
                <h3 class="font-display font-bold text-xl text-white mt-6">Kuis Fleksibel</h3>
                <p class="text-white/60 mt-3 leading-relaxed text-[15px]">Guru membuat soal mudah, normal, atau sulit — lalu menjalankannya langsung di kelas lewat sesi quiz.</p>
                <div class="mt-6 h-1 w-12 rounded-full bg-gradient-to-r from-cyan-400 to-transparent group-hover:w-full transition-all duration-700"></div>
            </div>

            <div class="premium-card premium-card--gradient-ring card-shine !bg-[#232e5e]/85 !border-white/10 backdrop-blur-xl p-8 group" style="transition-delay:.16s">
                <div class="w-16 h-16 rounded-2xl grid place-items-center text-3xl bg-gradient-to-br from-amber-400/30 to-amber-400/5 border border-amber-300/30 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-500"><x-icon name="shield-check" /> </div>
                <h3 class="font-display font-bold text-xl text-white mt-6">Aman & Terverifikasi</h3>
                <p class="text-white/60 mt-3 leading-relaxed text-[15px]">Setiap guru diverifikasi oleh admin sehingga konten belajar selalu terjaga kualitas dan keamanannya.</p>
                <div class="mt-6 h-1 w-12 rounded-full bg-gradient-to-r from-amber-400 to-transparent group-hover:w-full transition-all duration-700"></div>
            </div>
        </div>
    </section>

    <!-- ===== CARA KERJA ===== -->
    <section id="cara" class="relative z-10 max-w-5xl mx-auto px-4 pb-24">
        <div class="text-center mb-16">
            <p class="chip-pill chip-glass reveal"><x-icon name="compass" />  Alur Sederhana</p>
            <h2 class="font-display font-extrabold text-3xl md:text-5xl text-white mt-5 reveal delay-1">Tiga Langkah <span class="gradient-text-gold">Mudah</span></h2>
        </div>

        <div class="relative grid md:grid-cols-3 gap-10" data-stagger>
            <div class="hidden md:block absolute top-9 left-[16%] right-[16%] step-line"></div>
            @foreach([
                ['01','file-text','Guru Membuat Soal','Soal dibuat lengkap dengan tingkat kesulitan, kategori, dan kelas tujuan.'],
                ['02','rocket','Quiz Dijalankan','Guru memulai sesi quiz — siswa bergabung cepat tanpa ribet.'],
                ['03','trophy','Hasil Langsung Muncul','Nilai dan rekap jawaban terhitung otomatis, real-time.'],
            ] as [$num,$icon,$title,$desc])
                <div class="relative text-center reveal-scale">
                    <div class="relative mx-auto w-[72px] h-[72px] rounded-3xl grid place-items-center text-3xl text-cyan-200 bg-[#28325f] border border-white/12 shadow-[0_16px_44px_-12px_rgba(124,92,255,.45)] anim-pulse-glow"><x-icon name="{{ $icon }}" /></div>
                    <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-3 font-display font-extrabold text-xs tracking-widest text-cyan-300/70 bg-[#141b38] px-2">{{ $num }}</span>
                    <h3 class="font-display font-bold text-lg text-white mt-5">{{ $title }}</h3>
                    <p class="text-white/50 text-sm mt-2.5 leading-relaxed px-2">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ===== CTA BANNER ===== -->
    <section id="mulai" class="relative z-10 max-w-5xl mx-auto px-4 pb-28">
        <div class="cta-banner relative overflow-hidden rounded-[32px] border border-white/10 px-8 py-14 md:py-16 text-center shadow-[0_50px_120px_-30px_rgba(79,70,229,.5)] reveal-scale">
            <span class="absolute -top-10 -right-8 text-[140px] opacity-[.07] select-none anim-float"><x-icon name="graduation-cap" /> </span>
            <span class="absolute -bottom-12 -left-6 text-[130px] opacity-[.07] select-none anim-float-x"><x-icon name="pencil" /> </span>

            <h2 class="font-display font-extrabold text-3xl md:text-5xl text-white leading-tight">
                Siap Mengubah Cara<br><span class="gradient-text">Belajar di Kelasmu?</span>
            </h2>
            <p class="text-white/55 mt-5 max-w-md mx-auto">Bergabung sekarang — guru bisa langsung mengelola soal, siswa bisa mulai berlatih.</p>

            <div class="mt-9 flex flex-wrap justify-center gap-4">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" data-magnetic class="btn-premium text-base">Buka Dashboard <x-icon name="zap" /> </a>
                    @elseif (auth()->user()->role === 'student')
                        <a href="{{ route('student.dashboard') }}" data-magnetic class="btn-premium text-base">Buka Dashboard <x-icon name="graduation-cap" /> </a>
                        <a href="{{ route('student.soal.list') }}" class="btn-outline-light text-base"><x-icon name="file-text" />  Lihat Soal</a>
                    @else
                        <a href="{{ route('guru.dashboard') }}" data-magnetic class="btn-premium text-base">Buka Dashboard <x-icon name="zap" /> </a>
                        <a href="{{ route('guru.soal.list') }}" class="btn-outline-light text-base">Lihat Soal Saya</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" data-magnetic class="btn-premium text-base">Login Sekarang</a>
                    <a href="{{ route('register') }}" class="btn-outline-light text-base">Daftar Guru</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="relative z-10 border-t border-white/8 py-10 text-center">
        <div class="flex items-center justify-center gap-3 mb-4">
            <img src="{{ asset('images/logo-sekolah.png') }}" alt="" class="h-9 w-auto object-contain">
            <span class="font-display font-bold text-white">Web Edukasi</span>
        </div>
        <p class="text-white/40 text-sm">© {{ date('Y') }} SD Negeri 2 Petungsewu — Wagir, Kabupaten Malang</p>
        <p class="text-white/25 text-xs mt-1 tracking-wide">Dibuat dengan <x-icon name="heart" />  untuk pendidikan Indonesia</p>
    </footer>

    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script>
        // Rotating hero word
        (function () {
            const words = ['Seru!', 'Mudah!', 'Interaktif!', 'Modern!'];
            let i = 0;
            setInterval(() => {
                const el = document.getElementById('swapWord');
                if (!el || !el.parentElement) return;
                i = (i + 1) % words.length;
                const clone = document.createElement('span');
                clone.textContent = words[i];
                clone.className = 'entering';
                clone.style.cssText = 'opacity:0;transform:translateY(90%) rotate(-6deg);position:absolute;left:0;top:0;';
                el.parentElement.appendChild(clone);
                requestAnimationFrame(() => requestAnimationFrame(() => {
                    clone.style.opacity = '1';
                    clone.style.transform = 'translateY(0) rotate(0)';
                }));
                if (!el.classList.contains('leaving')) {
                    el.classList.add('leaving');
                    setTimeout(() => { el.remove(); clone.id = 'swapWord'; }, 550);
                } else {
                    document.querySelectorAll('.word-swap .leaving').forEach(n => n.remove());
                }
            }, 2400);
        })();
    </script>
</body>
</html>
