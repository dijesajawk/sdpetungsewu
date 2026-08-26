@extends('layouts.app')

@section('title', 'Soal Langsung')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Soal Langsung</h1>
            <p class="text-sm text-gray-600">Tipe: {{ ucfirst($difficulty_type) }}</p>
        </div>
        <a href="{{ route('guru.quiz.start') }}" class="text-blue-600 hover:underline">← Kembali</a>
    </div>

    <form action="#" method="POST">
        @csrf

        @foreach ($soalList as $index => $soal)
            <div class="bg-white rounded-xl p-6 shadow mb-4">
                <div class="flex items-start gap-4">
                    <div class="text-sm text-gray-500">{{ $index + 1 }}.</div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800 mb-2">{!! nl2br(e($soal->pertanyaan)) !!}</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="p-3 border rounded">A. {{ $soal->pilihan_a }}</label>
                            <label class="p-3 border rounded">B. {{ $soal->pilihan_b }}</label>
                            <label class="p-3 border rounded">C. {{ $soal->pilihan_c }}</label>
                            <label class="p-3 border rounded">D. {{ $soal->pilihan_d }}</label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <a href="{{ route('guru.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Selesai</a>
        </div>
    </form>
</div>
@endsection
