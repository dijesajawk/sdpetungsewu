@extends('layouts.app')

@section('title', 'Hasil Jawaban Murid')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><x-icon name="bar-chart" />  Hasil Jawaban Murid</h1>
            <p class="text-gray-600 mt-1">Sesi: <span class="font-mono font-bold">{{ $session->session_code }}</span></p>
        </div>
        <form action="{{ route('guru.session.end', $session) }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                class="btn-danger btn-sm"
                onclick="return confirm('Yakin ingin menutup sesi ini?')">
                <x-icon name="x" />  Tutup Sesi
            </button>
        </form>
    </div>

    <!-- Info Soal -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4"><x-icon name="file-text" />  Soal</h2>
        <p class="text-gray-700 mb-4 text-lg">{{ $soal->pertanyaan }}</p>
        <div class="space-y-2">
            <div class="flex items-start">
                <span class="font-semibold w-8">A.</span>
                <span class="flex-1 {{ $soal->jawaban_benar === 'a' ? 'text-green-600 font-bold' : '' }}">
                    {{ $soal->pilihan_a }}
                    @if ($soal->jawaban_benar === 'a')
                        <span class="text-green-600 ml-2"><x-icon name="check" />  Jawaban Benar</span>
                    @endif
                </span>
            </div>
            <div class="flex items-start">
                <span class="font-semibold w-8">B.</span>
                <span class="flex-1 {{ $soal->jawaban_benar === 'b' ? 'text-green-600 font-bold' : '' }}">
                    {{ $soal->pilihan_b }}
                    @if ($soal->jawaban_benar === 'b')
                        <span class="text-green-600 ml-2"><x-icon name="check" />  Jawaban Benar</span>
                    @endif
                </span>
            </div>
            <div class="flex items-start">
                <span class="font-semibold w-8">C.</span>
                <span class="flex-1 {{ $soal->jawaban_benar === 'c' ? 'text-green-600 font-bold' : '' }}">
                    {{ $soal->pilihan_c }}
                    @if ($soal->jawaban_benar === 'c')
                        <span class="text-green-600 ml-2"><x-icon name="check" />  Jawaban Benar</span>
                    @endif
                </span>
            </div>
            <div class="flex items-start">
                <span class="font-semibold w-8">D.</span>
                <span class="flex-1 {{ $soal->jawaban_benar === 'd' ? 'text-green-600 font-bold' : '' }}">
                    {{ $soal->pilihan_d }}
                    @if ($soal->jawaban_benar === 'd')
                        <span class="text-green-600 ml-2"><x-icon name="check" />  Jawaban Benar</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
            <p class="text-sm opacity-90 mb-1">Total Jawab</p>
            <p class="text-4xl font-bold">{{ $totalJawab }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
            <p class="text-sm opacity-90 mb-1">Jawab Benar</p>
            <p class="text-4xl font-bold">{{ $benarJawab }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
            <p class="text-sm opacity-90 mb-1">Persentase Benar</p>
            <p class="text-4xl font-bold">{{ $persentase }}%</p>
        </div>
    </div>

    <!-- Daftar Jawaban Murid -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6"> Daftar Jawaban Murid</h2>
        
        @if ($jawaban->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-300">
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Murid</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Jawaban</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Waktu Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jawaban as $key => $j)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-700">{{ $key + 1 }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-gray-800">{{ $j->nama_murid ?? 'Siswa' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="uppercase font-bold">
                                        @if ($j->jawaban_dipilih === 'a')
                                            {{ $j->jawaban_dipilih }}. {{ $soal->pilihan_a }}
                                        @elseif ($j->jawaban_dipilih === 'b')
                                            {{ $j->jawaban_dipilih }}. {{ $soal->pilihan_b }}
                                        @elseif ($j->jawaban_dipilih === 'c')
                                            {{ $j->jawaban_dipilih }}. {{ $soal->pilihan_c }}
                                        @else
                                            {{ $j->jawaban_dipilih }}. {{ $soal->pilihan_d }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
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
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    @if ($j->waktu_selesai)
                                        {{ $j->waktu_selesai->format('d M Y H:i:s') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded text-center">
                <p class="text-blue-700 text-lg">Belum ada murid yang menjawab soal ini.</p>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col gap-3">
        <a href="{{ route('guru.soal.list') }}" class="btn-premium text-center">
            ← Kembali ke Daftar Soal
        </a>
    </div>
</div>
@endsection
