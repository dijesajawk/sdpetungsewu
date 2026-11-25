@extends('layouts.app')

@section('title', 'Daftar Soal')

@section('content')
<div class="play-font space-y-8">

    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4 anim-fade-up">
        <div>
            <h1 class="font-display font-extrabold text-3xl md:text-4xl text-slate-900 flex items-center gap-3.5">
                <span class="anim-bounce inline-block text-4xl"><x-icon name="file-text" /> </span> Daftar Soal
            </h1>
            <p class="text-slate-500 mt-2">Pilih soal, jawab, dan kumpulkan bintangmu! <x-icon name="star" /> </p>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn-outline-brand !text-sm">
            ← Kembali ke Dashboard
        </a>
    </div>

    <!-- ===== FILTER BAR ===== -->
    <div class="premium-card p-5 md:p-6 !rounded-[22px] reveal delay-1">
        <form method="GET" action="{{ route('student.soal.list') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="kelas_id" class="block text-xs font-extrabold tracking-wider uppercase text-slate-400 mb-2"><x-icon name="school" />  Kelas</label>
                <select name="kelas_id" id="kelas_id" class="input-premium">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ $selectedKelas == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label for="kategori_id" class="block text-xs font-extrabold tracking-wider uppercase text-slate-400 mb-2"><x-icon name="book-open" />  Kategori</label>
                <select name="kategori_id" id="kategori_id" class="input-premium">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ $selectedKategori == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-premium !py-3"><x-icon name="search" />  Cari Soal</button>
        </form>
    </div>

    @if($soal->count() > 0)
    <!-- ===== GRID SOAL ===== -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6" data-stagger>
        @foreach($soal as $s)
        <div class="premium-card premium-card--gradient-ring card-shine p-6 group" data-tilt data-tilt-max="5">
            <div class="flex items-start justify-between mb-4">
                <span class="chip-pill bg-gradient-to-r from-violet-500 to-indigo-500 text-white !py-1.5 shadow-[0_6px_16px_-4px_rgba(124,92,255,.5)]">Soal #{{ $loop->iteration }}</span>
                <span class="text-2xl group-hover:scale-125 group-hover:rotate-12 transition-transform duration-500 inline-block">
                    @if($s->jawaban && $s->jawaban->where('user_id', auth()->id())->first())
                        @if($s->jawaban->where('user_id', auth()->id())->first()->is_correct) <x-icon name="check-circle" />  @else <x-icon name="x-circle" />  @endif
                    @else
                        <x-icon name="clock" class="text-slate-400" />
                    @endif
                </span>
            </div>

            <p class="font-bold text-slate-700 leading-relaxed min-h-[72px]">{{ Str::limit($s->pertanyaan, 100) }}</p>

            <div class="flex flex-wrap gap-2 mt-4">
                <span class="chip-pill bg-indigo-50 !text-indigo-600 !text-xs"><x-icon name="book-open" />  {{ $s->kelas->nama_kelas }}</span>
                <span class="chip-pill bg-cyan-50 !text-cyan-600 !text-xs"><x-icon name="tag" />  {{ $s->kategori->nama_kategori }}</span>
            </div>

            <a href="{{ route('student.soal.quiz', $s->id) }}"
               class="mt-5 w-full btn-premium !py-3 text-sm group-hover:!shadow-[0_14px_34px_-8px_rgba(124,92,255,.65)]">
                Mulai Kuis <x-icon name="rocket" /> 
            </a>
        </div>
        @endforeach
    </div>

    <!-- PAGINATION -->
    <div class="pagination flex justify-center gap-2 [&_a]:px-4 [&_a]:py-2.5 [&_a]:rounded-xl [&_a]:bg-white [&_a]:font-bold [&_a]:shadow-sm [&_a:hover]:bg-violet-500 [&_a:hover]:text-white [&_a]:transition-all [&_a:hover]:-translate-y-0.5 [&_a:hover]:shadow-lg [&_span.current]:px-4 [&_span.current]:py-2.5 [&_span.current]:rounded-xl [&_span.current]:bg-gradient-to-r [&_span.current]:from-violet-500 [&_span.current]:to-indigo-500 [&_span.current]:text-white [&_span.current]:font-bold [&_span.current]:shadow-md [&_span.dots]:text-slate-400 [&_.text-sm]:text-slate-500">
        {{ $soal->links() }}
    </div>
    @else
    <div class="empty-state reveal-scale play-font">
        <span class="empty-emoji"><x-icon name="help-circle" /> </span>
        <h3 class="font-display font-bold text-xl text-slate-800 mt-3">Belum Ada Soal</h3>
        <p class="text-slate-400 mt-1">Coba ubah filter atau kembali ke dashboard ya!</p>
    </div>
    @endif
</div>
@endsection
