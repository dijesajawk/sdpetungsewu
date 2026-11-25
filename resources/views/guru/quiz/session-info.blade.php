@extends('layouts.app')

@section('title', 'Hasil Jawaban Murid')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><x-icon name="bar-chart" />  Hasil Jawaban Murid</h1>
            <p class="text-gray-600 mt-1">Session: <span class="font-mono font-bold">{{ $session->session_code }}</span></p>
        </div>
        <form action="{{ route('guru.session.end', $session) }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                class="btn-danger btn-sm"
                onclick="return confirm('Yakin ingin menutup session ini?')">
                <x-icon name="x" />  Tutup Session
            </button>
        </form>
    </div>

    <!-- Statistik Keseluruhan -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
            <p class="text-sm opacity-90 mb-1">Total Soal</p>
            <p class="text-4xl font-bold">{{ $soalList->count() }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
            <p class="text-sm opacity-90 mb-1">Total Jawab</p>
            <p class="text-4xl font-bold">{{ $totalJawab }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
            <p class="text-sm opacity-90 mb-1">Jawab Benar</p>
            <p class="text-4xl font-bold">{{ $benarJawab }}</p>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6">
            <p class="text-sm opacity-90 mb-1">Persentase Benar</p>
            <p class="text-4xl font-bold">{{ $persentase }}%</p>
        </div>
    </div>

    <!-- Peringkat Murid -->
    @if ($peringkat->count() > 0)
        @php
            $topPeringkat = $peringkat->take(5);
            $puncakBenar = max(1, $topPeringkat->max('benar'));
            $podium = $topPeringkat->take(3);
        @endphp
        <style>
            @keyframes rankRise { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
            @keyframes barGrow { from { width: 0; } }
            .rank-rise { animation: rankRise .65s cubic-bezier(.22,1,.36,1) both; }
            .rank-bar { animation: barGrow 1s cubic-bezier(.22,1,.36,1) both; }
            .podium-step { min-height: 92px; }
            @media (min-width: 768px) { .podium-second { margin-top: 28px; } .podium-third { margin-top: 48px; } }
            @media (prefers-reduced-motion: reduce) { .rank-rise, .rank-bar { animation: none; } }
        </style>
        <div class="bg-slate-950 text-white rounded-2xl shadow-xl p-6 md:p-8 overflow-hidden relative">
            <div class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-cyan-400/10 blur-3xl"></div>
            <div class="relative flex flex-col md:flex-row md:items-end md:justify-between gap-2 mb-8">
                <div>
                    <p class="text-cyan-300 text-xs font-bold uppercase tracking-[.25em]">Live scoreboard</p>
                    <h2 class="text-2xl md:text-3xl font-bold mt-1"><x-icon name="trophy" />  Peringkat Murid</h2>
                </div>
                <p class="text-slate-400 text-sm">5 peserta terbaik dari {{ $peringkat->count() }} peserta</p>
            </div>

            <div class="relative grid grid-cols-3 items-end gap-2 md:gap-6 max-w-3xl mx-auto mb-10">
                @foreach ($podium as $hasil)
                    <div class="rank-rise text-center {{ $loop->iteration === 1 ? 'order-2' : ($loop->iteration === 2 ? 'order-1 podium-second' : 'order-3 podium-third') }}" style="animation-delay: {{ $loop->index * 120 }}ms">
                        <div class="text-3xl mb-2">{{ $loop->iteration === 1 ? '' : ($loop->iteration === 2 ? '' : '') }}</div>
                        <p class="font-bold truncate" title="{{ $hasil['nama'] }}">{{ $hasil['nama'] }}</p>
                        <p class="text-cyan-300 text-sm font-semibold mb-2">{{ $hasil['benar'] }} benar</p>
                        <div class="podium-step rounded-t-xl flex items-center justify-center {{ $loop->iteration === 1 ? 'bg-amber-400 text-amber-950' : ($loop->iteration === 2 ? 'bg-slate-300 text-slate-800' : 'bg-orange-500 text-orange-950') }}">
                            <span class="text-4xl font-black">{{ $loop->iteration }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="relative border-t border-white/10 pt-6 space-y-4">
                @foreach ($topPeringkat as $hasil)
                    <div class="rank-rise" style="animation-delay: {{ $loop->index * 80 }}ms">
                        <div class="flex items-center gap-3 mb-1.5 text-sm">
                            <span class="w-6 text-slate-400 font-bold">{{ $loop->iteration }}</span>
                            <span class="font-semibold flex-1 truncate">{{ $hasil['nama'] }}</span>
                            <span class="text-slate-400">{{ $hasil['persentase'] }}%</span>
                            <span class="text-cyan-300 font-bold w-16 text-right">{{ $hasil['benar'] }} benar</span>
                        </div>
                        <div class="ml-9 h-2.5 rounded-full bg-white/10 overflow-hidden">
                            <div class="rank-bar h-full rounded-full bg-gradient-to-r from-cyan-400 to-blue-500" style="width: {{ ($hasil['benar'] / $puncakBenar) * 100 }}%; animation-delay: {{ 250 + $loop->index * 80 }}ms"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Daftar Soal dan Jawaban Murid -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6"><x-icon name="clipboard-list" />  Soal dan Jawaban Murid</h2>
        
        @if ($soalList->count() > 0)
            <div class="space-y-6">
                @foreach ($soalList as $item)
                    @php
                        $jawabanSoalIni = $jawaban->where('soal_id', $item->soal_id)->groupBy('identifier');
                    @endphp
                    <div class="border border-gray-300 rounded-lg p-6">
                        <!-- Header Soal -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-800">Soal {{ $item->urutan }}: {{ Str::limit($item->soal->pertanyaan, 100) }}</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                        {{ $item->soal->kelas->nama_kelas ?? 'N/A' }}
                                    </span>
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                        {{ ucfirst($item->soal->kategori->nama_kategori ?? 'N/A') }}
                                    </span>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                        @if($item->soal->difficulty_level === 'mudah') bg-green-100 text-green-800
                                        @elseif($item->soal->difficulty_level === 'normal') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        @if($item->soal->difficulty_level === 'mudah') <x-icon name="dot" class="text-emerald-400" />  Mudah
                                        @elseif($item->soal->difficulty_level === 'normal') <x-icon name="dot" class="text-amber-400" />  Normal
                                        @else <x-icon name="dot" class="text-rose-400" />  Sulit
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilihan Jawaban -->
                        <div class="bg-gray-50 p-4 rounded mb-4 space-y-2">
                            <div class="flex items-start">
                                <span class="font-semibold w-8">A.</span>
                                <span class="flex-1 {{ $item->soal->jawaban_benar === 'a' ? 'text-green-600 font-bold' : '' }}">
                                    {{ $item->soal->pilihan_a }}
                                    @if ($item->soal->jawaban_benar === 'a')
                                        <span class="text-green-600 ml-2"><x-icon name="check" />  Jawaban Benar</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-start">
                                <span class="font-semibold w-8">B.</span>
                                <span class="flex-1 {{ $item->soal->jawaban_benar === 'b' ? 'text-green-600 font-bold' : '' }}">
                                    {{ $item->soal->pilihan_b }}
                                    @if ($item->soal->jawaban_benar === 'b')
                                        <span class="text-green-600 ml-2"><x-icon name="check" />  Jawaban Benar</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-start">
                                <span class="font-semibold w-8">C.</span>
                                <span class="flex-1 {{ $item->soal->jawaban_benar === 'c' ? 'text-green-600 font-bold' : '' }}">
                                    {{ $item->soal->pilihan_c }}
                                    @if ($item->soal->jawaban_benar === 'c')
                                        <span class="text-green-600 ml-2"><x-icon name="check" />  Jawaban Benar</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-start">
                                <span class="font-semibold w-8">D.</span>
                                <span class="flex-1 {{ $item->soal->jawaban_benar === 'd' ? 'text-green-600 font-bold' : '' }}">
                                    {{ $item->soal->pilihan_d }}
                                    @if ($item->soal->jawaban_benar === 'd')
                                        <span class="text-green-600 ml-2"><x-icon name="check" />  Jawaban Benar</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Jawaban Murid -->
                        @if ($jawabanSoalIni->count() > 0)
                            <div>
                                <p class="font-semibold text-gray-800 mb-3">Jawaban Murid:</p>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="bg-gray-100 border-b">
                                                <th class="px-3 py-2 text-left">No</th>
                                                <th class="px-3 py-2 text-left">Nama Murid</th>
                                                <th class="px-3 py-2 text-left">Jawaban</th>
                                                <th class="px-3 py-2 text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jawabanSoalIni as $key => $jawabanPerMurid)
                                                @php $j = $jawabanPerMurid->first(); @endphp
                                                <tr class="border-b hover:bg-gray-50">
                                                    <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                                    <td class="px-3 py-2 font-semibold">{{ $j->nama_murid }}</td>
                                                    <td class="px-3 py-2">
                                                        <strong>{{ strtoupper($j->jawaban_dipilih) }}.</strong>
                                                        @if ($j->jawaban_dipilih === 'a')
                                                            {{ $item->soal->pilihan_a }}
                                                        @elseif ($j->jawaban_dipilih === 'b')
                                                            {{ $item->soal->pilihan_b }}
                                                        @elseif ($j->jawaban_dipilih === 'c')
                                                            {{ $item->soal->pilihan_c }}
                                                        @else
                                                            {{ $item->soal->pilihan_d }}
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        @if ($j->is_correct)
                                                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">
                                                                <x-icon name="check" />  Benar
                                                            </span>
                                                        @else
                                                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">
                                                                <x-icon name="x" />  Salah
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded text-yellow-800">
                                <p>Belum ada murid yang menjawab soal ini.</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded text-center">
                <p class="text-blue-700 text-lg">Tidak ada soal dalam session ini.</p>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col gap-3">
        <a href="{{ route('guru.dashboard') }}" class="btn-premium text-center">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
