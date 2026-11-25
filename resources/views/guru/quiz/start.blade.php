@extends('layouts.app')

@section('title', 'Mulai Soal')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-blue-600 font-semibold">Guru</p>
            <h1 class="text-3xl font-bold text-gray-800">▶ Mulai Soal</h1>
        </div>
        <a href="{{ route('guru.dashboard') }}" class="btn-soft btn-xs inline-flex items-center justify-center">
            ← Kembali ke Dashboard
        </a>
    </div>

    <form action="{{ route('guru.quiz.create') }}" method="POST" class="space-y-8">
        @csrf

        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800"><x-icon name="bar-chart" />  Pilih Tingkat Kesulitan</h2>
                <p class="text-sm text-gray-600 mt-1">Pilih salah satu level soal atau gabungkan semuanya.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="difficulty-card flex items-center gap-4 p-5 border-2 border-gray-200 rounded-2xl cursor-pointer transition hover:border-green-400 hover:bg-green-50">
                    <input type="radio" name="difficulty_type" value="mudah" class="w-5 h-5 accent-green-600" required {{ old('difficulty_type', 'normal') === 'mudah' ? 'checked' : '' }}>
                    <span class="flex-1">
                        <span class="block font-bold text-green-700 text-lg"><x-icon name="dot" class="text-emerald-400" />  Mudah</span>
                        <span class="text-sm text-gray-600">Hanya soal dengan tingkat kesulitan mudah</span>
                    </span>
                </label>

                <label class="difficulty-card flex items-center gap-4 p-5 border-2 border-gray-200 rounded-2xl cursor-pointer transition hover:border-yellow-400 hover:bg-yellow-50">
                    <input type="radio" name="difficulty_type" value="normal" class="w-5 h-5 accent-yellow-600" required {{ old('difficulty_type', 'normal') === 'normal' ? 'checked' : '' }}>
                    <span class="flex-1">
                        <span class="block font-bold text-yellow-700 text-lg"><x-icon name="dot" class="text-amber-400" />  Normal</span>
                        <span class="text-sm text-gray-600">Hanya soal dengan tingkat kesulitan normal</span>
                    </span>
                </label>

                <label class="difficulty-card flex items-center gap-4 p-5 border-2 border-gray-200 rounded-2xl cursor-pointer transition hover:border-red-400 hover:bg-red-50">
                    <input type="radio" name="difficulty_type" value="sulit" class="w-5 h-5 accent-red-600" required {{ old('difficulty_type', 'normal') === 'sulit' ? 'checked' : '' }}>
                    <span class="flex-1">
                        <span class="block font-bold text-red-700 text-lg"><x-icon name="dot" class="text-rose-400" />  Sulit</span>
                        <span class="text-sm text-gray-600">Hanya soal dengan tingkat kesulitan sulit</span>
                    </span>
                </label>

                <label class="difficulty-card flex items-center gap-4 p-5 border-2 border-gray-200 rounded-2xl cursor-pointer transition hover:border-purple-400 hover:bg-purple-50">
                    <input type="radio" name="difficulty_type" value="campuran" class="w-5 h-5 accent-purple-600" required {{ old('difficulty_type', 'normal') === 'campuran' ? 'checked' : '' }}>
                    <span class="flex-1">
                        <span class="block font-bold text-purple-700 text-lg"><x-icon name="shuffle" />  Campuran</span>
                        <span class="text-sm text-gray-600">Gabungan mudah, normal, dan sulit dengan urutan acak</span>
                    </span>
                </label>
            </div>

            @error('difficulty_type')
                <span class="text-red-500 text-sm block mt-3">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <label for="kelas_id" class="block text-lg font-bold text-gray-800 mb-3"><x-icon name="school" />  Pilih Kelas</label>
                <div class="relative">
                    <select id="kelas_id" name="kelas_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 appearance-none pr-10">
                        <option value="" {{ old('kelas_id', '') === '' ? 'selected' : '' }}>-- Semua Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}" {{ (string)old('kelas_id') === (string)$k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <x-icon name="chevron-down" class="w-4 h-4 text-gray-500" />
                    </span>
                </div>
                <p class="text-sm text-gray-600 mt-3">Biarkan kosong untuk memakai semua kelas, atau pilih kelas tertentu.</p>
                @error('kelas_id')
                    <span class="text-red-500 text-sm block mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Kategori dihapus: gunakan tingkat kesulitan dan kelas sebagai filter -->
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-xl p-6">
            <h3 class="font-bold text-blue-900 mb-2"><x-icon name="lightbulb" />  Informasi</h3>
            <ul class="list-disc list-inside text-blue-800 space-y-1 text-sm">
                <li>Semua soal yang sesuai filter akan dimasukkan ke sesi</li>
                <li>Murid akan menjawab soal satu per satu</li>
                <li>Anda bisa melihat hasil jawaban murid setelah sesi dimulai</li>
                <li>Pilih "Campuran" untuk mengacak semua tingkat kesulitan</li>
            </ul>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row pt-2">
            <button type="submit" formaction="{{ route('guru.quiz.create') }}" class="btn-premium flex-1">
                ▶ Mulai Session (Buat Session)
            </button>

            <button type="submit" formaction="{{ route('guru.quiz.create_immediate') }}" class="btn-success flex-1">
                <x-icon name="search" />  Tampilkan Langsung (Tanpa Session)
            </button>

            <a href="{{ route('guru.dashboard') }}" class="btn-soft flex-1 text-center">
                Batal
            </a>
        </div>
    </form>
</div>

<style>
    .difficulty-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }

    .difficulty-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }

    .difficulty-card input {
        margin-top: 2px;
    }
</style>
@endsection
