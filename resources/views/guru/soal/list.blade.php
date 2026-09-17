@extends('layouts.app')

@section('title', 'Daftar Soal')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Soal Saya</h1>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('guru.soal.upload.file') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition flex items-center justify-center gap-2">
                📄 Upload File
            </a>
            <a href="{{ route('guru.soal.scan.upload') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition flex items-center justify-center gap-2">
                📸 Scan Soal
            </a>
            <a href="{{ route('guru.soal.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                + Tambah Soal
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">🔍 Filter Soal</h2>
        <form action="{{ route('guru.soal.list') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Mata Pelajaran Filter -->
            <div>
                <label for="mata_pelajaran" class="block text-gray-600 text-sm font-medium mb-1">Mata Pelajaran</label>
                <select id="mata_pelajaran" name="mata_pelajaran" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Mata Pelajaran</option>
                    @foreach ($mataPelajaran as $mapel)
                        <option value="{{ $mapel }}" {{ request('mata_pelajaran') == $mapel ? 'selected' : '' }}>
                            {{ $mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Difficulty Level Filter -->
            <div>
                <label for="difficulty_level" class="block text-gray-600 text-sm font-medium mb-1">Tingkat Kesulitan</label>
                <select id="difficulty_level" name="difficulty_level"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Tingkat</option>
                    <option value="mudah" {{ request('difficulty_level') == 'mudah' ? 'selected' : '' }}>🟢 Mudah</option>
                    <option value="normal" {{ request('difficulty_level') == 'normal' ? 'selected' : '' }}>🟡 Normal</option>
                    <option value="sulit" {{ request('difficulty_level') == 'sulit' ? 'selected' : '' }}>🔴 Sulit</option>
                </select>
            </div>

            <!-- Kelas Filter -->
            <div>
                <label for="kelas_id" class="block text-gray-600 text-sm font-medium mb-1">Kelas</label>
                <select id="kelas_id" name="kelas_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('guru.soal.list') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg text-center transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Active Filter Indicators -->
    @if(request('mata_pelajaran') || request('difficulty_level') || request('kelas_id'))
        <div class="flex flex-wrap gap-2 items-center">
            <span class="text-gray-600 text-sm font-medium">Filter aktif:</span>
            @if(request('mata_pelajaran'))
                <span class="inline-flex items-center bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                    📚 {{ request('mata_pelajaran') }}
                    <a href="{{ route('guru.soal.list', array_merge(request()->except('mata_pelajaran'), ['difficulty_level' => request('difficulty_level'), 'kelas_id' => request('kelas_id')])) }}" 
                        class="ml-2 text-purple-600 hover:text-purple-800">✕</a>
                </span>
            @endif
            @if(request('difficulty_level'))
                <span class="inline-flex items-center bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                    {{ ucfirst(request('difficulty_level')) }}
                    <a href="{{ route('guru.soal.list', array_merge(request()->except('difficulty_level'), ['mata_pelajaran' => request('mata_pelajaran'), 'kelas_id' => request('kelas_id')])) }}" 
                        class="ml-2 text-yellow-600 hover:text-yellow-800">✕</a>
                </span>
            @endif
            @if(request('kelas_id'))
                @php
                    $selectedKelas = $kelas->firstWhere('id', request('kelas_id'));
                @endphp
                <span class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                    {{ $selectedKelas ? $selectedKelas->nama_kelas : 'Kelas ' . request('kelas_id') }}
                    <a href="{{ route('guru.soal.list', array_merge(request()->except('kelas_id'), ['mata_pelajaran' => request('mata_pelajaran'), 'difficulty_level' => request('difficulty_level')])) }}" 
                        class="ml-2 text-blue-600 hover:text-blue-800">✕</a>
                </span>
            @endif
        </div>
    @endif

    <!-- Result Count -->
    <div class="text-gray-600 text-sm">
        Menampilkan {{ $soal->count() }} dari {{ $soal->total() }} soal
    </div>

    @if ($soal->count() > 0)
        <div class="grid grid-cols-1 gap-6">
            @foreach ($soal as $s)
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $s->pertanyaan }}</h3>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($s->mata_pelajaran)
                                    <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        📚 {{ $s->mata_pelajaran }}
                                    </span>
                                @endif
                                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                    {{ $s->kelas->nama_kelas }}
                                </span>
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                    {{ ucfirst($s->difficulty_level ?? 'Tanpa level') }}
                                </span>
                            </div>
                            
                            <!-- Pilihan Jawaban -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-start">
                                    <span class="font-semibold w-8">A.</span>
                                    <span class="flex-1 {{ $s->jawaban_benar === 'a' ? 'text-green-600 font-bold' : '' }}">
                                        {{ $s->pilihan_a }}
                                        @if ($s->jawaban_benar === 'a')
                                            <span class="text-green-600">✓</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-start">
                                    <span class="font-semibold w-8">B.</span>
                                    <span class="flex-1 {{ $s->jawaban_benar === 'b' ? 'text-green-600 font-bold' : '' }}">
                                        {{ $s->pilihan_b }}
                                        @if ($s->jawaban_benar === 'b')
                                            <span class="text-green-600">✓</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-start">
                                    <span class="font-semibold w-8">C.</span>
                                    <span class="flex-1 {{ $s->jawaban_benar === 'c' ? 'text-green-600 font-bold' : '' }}">
                                        {{ $s->pilihan_c }}
                                        @if ($s->jawaban_benar === 'c')
                                            <span class="text-green-600">✓</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-start">
                                    <span class="font-semibold w-8">D.</span>
                                    <span class="flex-1 {{ $s->jawaban_benar === 'd' ? 'text-green-600 font-bold' : '' }}">
                                        {{ $s->pilihan_d }}
                                        @if ($s->jawaban_benar === 'd')
                                            <span class="text-green-600">✓</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <p class="text-gray-500 text-sm">Dibuat: {{ $s->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2 md:ml-4 md:justify-start md:items-end md:self-start">
                            <a href="{{ route('guru.quiz.start') }}" 
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition text-sm text-center font-semibold">
                                ▶ Jalankan
                            </a>
                            <a href="{{ route('guru.soal.edit', $s) }}" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition text-sm text-center">
                                Edit
                            </a>
                            <form action="{{ route('guru.soal.delete', $s, false) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition text-sm w-full md:w-auto"
                                    onclick="return confirm('Yakin ingin menghapus soal ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $soal->links('pagination::tailwind') }}
        </div>
    @else
        <div class="bg-blue-50 border-l-4 border-blue-500 p-8 rounded-lg text-center">
            <p class="text-blue-700 text-lg">Anda belum membuat soal apapun.</p>
            <a href="{{ route('guru.soal.create') }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                Buat Soal Sekarang
            </a>
        </div>
    @endif
</div>
@endsection
