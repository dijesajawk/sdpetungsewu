<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Web Edukasi</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&family=Baloo+2:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">

    <style>
        body { font-family: var(--font-body); background: #f4f5fc; }
        .app-bg {
            position: fixed; inset: 0; z-index: -1; pointer-events: none;
            background:
                radial-gradient(900px 480px at 88% -8%, rgba(124,92,255,.14), transparent 60%),
                radial-gradient(800px 460px at -6% 108%, rgba(34,211,238,.12), transparent 60%);
        }
        .nav-bar {
            border: 1px solid rgba(255,255,255,.09);
            transition: all .45s cubic-bezier(.22,1,.36,1);
        }
        .mobile-panel { max-height: 0; overflow: hidden; transition: max-height .5s cubic-bezier(.22,1,.36,1); }
        .mobile-panel.open { max-height: 420px; }
        .hamburger span { display:block; width:22px; height:2px; background:#fff; margin:5px 0; border-radius:99px; transition:.35s ease; }
        .hamburger.active span:nth-child(1){ transform: translateY(7px) rotate(45deg); }
        .hamburger.active span:nth-child(2){ opacity:0; }
        .hamburger.active span:nth-child(3){ transform: translateY(-7px) rotate(-45deg); }
    </style>
</head>
<body class="text-slate-800 min-h-screen flex flex-col">
    <div class="app-bg"></div>

    <!-- Brand preloader -->
    <div id="brandLoader"><div class="loader-ring"></div></div>

    @auth
        <!-- ===== NAVBAR ===== -->
        <div class="nav-shell nav-light px-4 pt-4">
            <nav class="nav-bar max-w-7xl mx-auto rounded-2xl bg-white/80 backdrop-blur-xl shadow-[0_10px_40px_-16px_rgba(23,23,60,.25)]">
                <div class="flex items-center justify-between gap-4 px-4 sm:px-6 py-3">

                    <!-- Brand -->
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group shrink-0">
                        <span class="logo-badge">
                            <img src="{{ asset('images/logo-sekolah.jpg') }}" alt="Logo">
                        </span>
                        <span class="leading-tight hidden sm:block">
                            <span class="font-display font-bold text-slate-900 text-lg block">Web Edukasi</span>
                            <span class="text-[11px] font-semibold tracking-wider text-indigo-500 uppercase">SDN 2 Petungsewu</span>
                        </span>
                    </a>

                    <!-- Desktop links -->
                    <div class="hidden md:flex items-center gap-7 text-sm text-slate-600">
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="nav-link hover:text-indigo-600">📊 Dashboard</a>
                        @elseif (auth()->user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}" class="nav-link hover:text-indigo-600">🏠 Dashboard</a>
                            <a href="{{ route('student.soal.list') }}" class="nav-link hover:text-indigo-600">📝 Daftar Soal</a>
                            <a href="{{ route('student.result') }}" class="nav-link hover:text-indigo-600">📊 Hasil</a>
                        @else
                            <a href="{{ route('guru.dashboard') }}" class="nav-link hover:text-indigo-600">🏠 Dashboard</a>
                            <a href="{{ route('guru.quiz.start') }}" class="nav-link hover:text-indigo-600">🚀 Mulai Quiz</a>
                            <a href="{{ route('guru.soal.list') }}" class="nav-link hover:text-indigo-600">📋 Soal Saya</a>
                        @endif
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex items-center gap-3 pl-4 border-l border-slate-200">
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
                                <p class="text-sm font-bold text-slate-800">{{ Str::limit(auth()->user()->name, 16) }}</p>
                                <span class="role-pill">{{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('logout', [], false) }}" method="POST">
                            @csrf
                            <button type="submit" title="Keluar"
                                class="w-10 h-10 grid place-items-center rounded-xl bg-rose-50 text-rose-500 border border-rose-100 hover:bg-rose-500 hover:text-white hover:shadow-[0_10px_26px_-8px_rgba(244,63,94,.6)] hover:-translate-y-0.5 active:scale-95 transition-all">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            </button>
                        </form>

                        <button class="hamburger md:hidden p-2 rounded-xl bg-indigo-900/90" onclick="document.getElementById('mobilePanel').classList.toggle('open'); this.classList.toggle('active')" aria-label="Menu">
                            <span></span><span></span><span></span>
                        </button>
                    </div>
                </div>

                <!-- Mobile panel -->
                <div id="mobilePanel" class="mobile-panel md:hidden border-t border-slate-100">
                    <div class="px-5 py-4 flex flex-col gap-1 text-slate-700 font-semibold">
                        <div class="flex items-center gap-3 mb-3 pb-3 border-b border-slate-100">
                            <div class="avatar-ring">
                                @if (auth()->user()->role === 'guru' && auth()->user()->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" class="h-10 w-10" alt="">
                                @else
                                    <div class="h-10 w-10 grid place-items-center bg-gradient-to-br from-violet-500 to-cyan-400 text-white font-extrabold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold">{{ auth()->user()->name }}</p>
                                <span class="role-pill">{{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                        </div>
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="py-2.5 px-3 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition">📊 Dashboard</a>
                        @elseif (auth()->user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}" class="py-2.5 px-3 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition">🏠 Dashboard</a>
                            <a href="{{ route('student.soal.list') }}" class="py-2.5 px-3 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition">📝 Daftar Soal</a>
                            <a href="{{ route('student.result') }}" class="py-2.5 px-3 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition">📊 Hasil</a>
                        @else
                            <a href="{{ route('guru.dashboard') }}" class="py-2.5 px-3 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition">🏠 Dashboard</a>
                            <a href="{{ route('guru.quiz.start') }}" class="py-2.5 px-3 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition">🚀 Mulai Quiz</a>
                            <a href="{{ route('guru.soal.list') }}" class="py-2.5 px-3 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition">📋 Soal Saya</a>
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
            <div class="rounded-2xl bg-gradient-to-r from-indigo-950 via-[#1b1650] to-indigo-900 text-white/70 text-center py-5 px-6 shadow-[0_18px_50px_-20px_rgba(30,27,75,.6)]">
                <p class="text-sm font-medium">🎓 Web Edukasi — SD Negeri 2 Petungsewu, Wagir</p>
                <p class="text-xs mt-1 text-white/40">Belajar • Berkarya • Berprestasi</p>
            </div>
        </div>
    </footer>

    <!-- ===== TOAST ===== -->
    <div id="globalToast" class="fixed right-5 bottom-5 z-[60] hidden">
        <div id="globalToastInner" class="toast-premium">
            <span id="globalToastIcon" class="w-8 h-8 rounded-full grid place-items-center bg-white/20 text-lg shrink-0">✓</span>
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
                text.textContent = message;
                inner.classList.remove('success', 'error');
                inner.classList.add(type);
                icon.textContent = type === 'success' ? '✓' : '!';
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
