<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Web Edukasi</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&family=Baloo+2:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}?v={{ filemtime(public_path('assets/css/theme.css')) }}">

    <style>
        body { font-family: var(--font-body); }
        .app-bg {
            position: fixed; inset: 0; z-index: -1; pointer-events: none;
            background:
                radial-gradient(900px 480px at 88% -8%, rgba(124,92,255,.22), transparent 62%),
                radial-gradient(800px 460px at -6% 108%, rgba(34,211,238,.16), transparent 62%),
                linear-gradient(180deg, #151d3f 0%, #1a2448 55%, #151d3f 100%);
        }
        .app-aurora { opacity: .8; }
        .app-aurora .aurora-blob { opacity: .36; }
        .nav-bar {
            border: 1px solid rgba(255,255,255,.09);
            transition: all .45s cubic-bezier(.22,1,.36,1);
        }
        .mobile-panel { max-height: 0; overflow: hidden; transition: max-height .5s cubic-bezier(.22,1,.36,1); }
        .mobile-panel.open { max-height: 460px; }
        .hamburger span { display:block; width:22px; height:2px; background:#fff; margin:5px 0; border-radius:99px; transition:.35s ease; }
        .hamburger.active span:nth-child(1){ transform: translateY(7px) rotate(45deg); }
        .hamburger.active span:nth-child(2){ opacity:0; }
        .hamburger.active span:nth-child(3){ transform: translateY(-7px) rotate(-45deg); }
    </style>
</head>
<body class="@yield('body-class', 'app-dark text-slate-100') min-h-screen flex flex-col overflow-x-hidden">
    <div class="app-bg"></div>
    <div class="aurora-field app-aurora">
        <div class="aurora-blob violet"></div>
        <div class="aurora-blob cyan"></div>
        <div class="aurora-blob gold"></div>
    </div>
    <div class="grid-overlay"></div>

    <!-- Brand preloader -->
    <div id="brandLoader"><div class="loader-ring"></div></div>

    @auth
        <!-- ===== NAVBAR ===== -->
        <div class="nav-shell px-4 pt-4">
            <nav class="nav-bar max-w-7xl mx-auto rounded-2xl glass-dark shadow-[0_18px_50px_-24px_rgba(0,0,0,.75)]">
                <div class="flex items-center justify-between gap-4 px-4 sm:px-6 py-3">

                    <!-- Brand -->
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group shrink-0">
                        <span class="logo-badge">
                            <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo">
                        </span>
                        <span class="leading-tight hidden sm:block">
                            <span class="font-display font-bold text-white text-lg block">Web Edukasi</span>
                            <span class="text-[11px] font-semibold tracking-[.16em] text-cyan-300/80 uppercase">SDN 2 Petungsewu</span>
                        </span>
                    </a>

                    <!-- Desktop links -->
                    <div class="hidden md:flex items-center gap-7 text-sm text-white/60">
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="nav-link hover:text-white"><x-icon name="bar-chart" />  Dashboard</a>
                        @elseif (auth()->user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}" class="nav-link hover:text-white"><x-icon name="home" />  Dashboard</a>
                            <a href="{{ route('student.soal.list') }}" class="nav-link hover:text-white"><x-icon name="file-text" />  Daftar Soal</a>
                            <a href="{{ route('student.result') }}" class="nav-link hover:text-white"><x-icon name="bar-chart" />  Hasil</a>
                        @else
                            <a href="{{ route('guru.dashboard') }}" class="nav-link hover:text-white"><x-icon name="home" />  Dashboard</a>
                            <a href="{{ route('guru.quiz.start') }}" class="nav-link hover:text-white"><x-icon name="rocket" />  Mulai Quiz</a>
                            <a href="{{ route('guru.soal.list') }}" class="nav-link hover:text-white"><x-icon name="clipboard-list" />  Soal Saya</a>
                        @endif
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex items-center gap-3 pl-4 border-l border-white/10">
                            <div class="avatar-ring">
                                @if (auth()->user()->role === 'guru' && auth()->user()->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}" class="h-9 w-9">
                                @else
                                    <div class="h-9 w-9 grid place-items-center bg-gradient-to-br from-violet-500 to-cyan-400 text-white font-extrabold text-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="leading-tight">
                                <p class="text-sm font-bold text-white">{{ Str::limit(auth()->user()->name, 16) }}</p>
                                <span class="role-pill">{{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('logout', [], false) }}" method="POST">
                            @csrf
                            <button type="submit" title="Keluar"
                                class="w-10 h-10 grid place-items-center rounded-xl bg-rose-500/10 text-rose-300 border border-rose-400/25 hover:bg-rose-500 hover:text-white hover:shadow-[0_10px_26px_-8px_rgba(244,63,94,.6)] hover:-translate-y-0.5 active:scale-95 transition-all">
                                <x-icon name="logout" class="w-[18px] h-[18px]" />
                            </button>
                        </form>

                        <button class="hamburger md:hidden p-2 rounded-xl bg-white/10 border border-white/10" onclick="document.getElementById('mobilePanel').classList.toggle('open'); this.classList.toggle('active')" aria-label="Menu">
                            <span></span><span></span><span></span>
                        </button>
                    </div>
                </div>

                <!-- Mobile panel -->
                <div id="mobilePanel" class="mobile-panel md:hidden border-t border-white/10">
                    <div class="px-5 py-4 flex flex-col gap-1 text-white/80 font-semibold">
                        <div class="flex items-center gap-3 mb-3 pb-3 border-b border-white/10">
                            <div class="avatar-ring">
                                @if (auth()->user()->role === 'guru' && auth()->user()->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" class="h-10 w-10" alt="">
                                @else
                                    <div class="h-10 w-10 grid place-items-center bg-gradient-to-br from-violet-500 to-cyan-400 text-white font-extrabold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                                <span class="role-pill">{{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                        </div>
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="py-2.5 px-3 rounded-xl hover:bg-white/10 hover:text-white transition"><x-icon name="bar-chart" />  Dashboard</a>
                        @elseif (auth()->user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}" class="py-2.5 px-3 rounded-xl hover:bg-white/10 hover:text-white transition"><x-icon name="home" />  Dashboard</a>
                            <a href="{{ route('student.soal.list') }}" class="py-2.5 px-3 rounded-xl hover:bg-white/10 hover:text-white transition"><x-icon name="file-text" />  Daftar Soal</a>
                            <a href="{{ route('student.result') }}" class="py-2.5 px-3 rounded-xl hover:bg-white/10 hover:text-white transition"><x-icon name="bar-chart" />  Hasil</a>
                        @else
                            <a href="{{ route('guru.dashboard') }}" class="py-2.5 px-3 rounded-xl hover:bg-white/10 hover:text-white transition"><x-icon name="home" />  Dashboard</a>
                            <a href="{{ route('guru.quiz.start') }}" class="py-2.5 px-3 rounded-xl hover:bg-white/10 hover:text-white transition"><x-icon name="rocket" />  Mulai Quiz</a>
                            <a href="{{ route('guru.soal.list') }}" class="py-2.5 px-3 rounded-xl hover:bg-white/10 hover:text-white transition"><x-icon name="clipboard-list" />  Soal Saya</a>
                        @endif
                    </div>
                </div>
            </nav>
        </div>
    @endauth

    <!-- ===== CONTENT ===== -->
    <main class="page-enter flex-1 w-full max-w-7xl mx-auto px-4 py-8 relative z-10">
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="relative z-10 mt-8">
        <div class="max-w-7xl mx-auto px-4 pb-8">
            <div class="rounded-2xl glass-dark text-center py-5 px-6">
                <p class="text-sm font-medium text-white/70"><x-icon name="graduation-cap" />  Web Edukasi — SD Negeri 2 Petungsewu, Wagir</p>
                <p class="text-xs mt-1 text-cyan-300/50 tracking-wide">Belajar • Berkarya • Berprestasi</p>
            </div>
        </div>
    </footer>

    <!-- ===== TOAST ===== -->
    <div id="globalToast" class="fixed right-5 bottom-5 z-[60] hidden">
        <div id="globalToastInner" class="toast-premium">
            <span id="globalToastIcon" class="w-8 h-8 rounded-full grid place-items-center bg-white/20 text-lg shrink-0"><x-icon name="check" /> </span>
            <span id="globalToastText" class="text-sm font-semibold leading-snug pr-2"></span>
            <span class="toast-progress"></span>
        </div>
    </div>

    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMsg = @json(session('success'));
            const errorMsg = @json(session('error'));
            const errors = @json($errors->all());
            const toast = document.getElementById('globalToast');
            const inner = document.getElementById('globalToastInner');
            const icon = document.getElementById('globalToastIcon');
            const text = document.getElementById('globalToastText');

            function showToast(message, type = 'success') {
                if (!toast || !inner) return;
                text.innerHTML = message;
                inner.classList.remove('success', 'error');
                inner.classList.add(type);
                icon.textContent = type === 'success' ? '' : '!';
                // restart progress animation
                const bar = inner.querySelector('.toast-progress');
                bar.style.animation = 'none'; void bar.offsetWidth; bar.style.animation = '';
                toast.classList.remove('hidden');
                setTimeout(() => {
                    inner.classList.add('hide');
                    setTimeout(() => { toast.classList.add('hidden'); inner.classList.remove('hide'); }, 400);
                }, 3400);
            }

            if (successMsg) showToast(successMsg, 'success');
            else if (errorMsg) showToast(errorMsg, 'error');
            else if (errors && errors.length > 0) showToast(errors[0], 'error');
        });
    </script>
</body>
</html>
