@extends('layouts.app')

@section('title', 'Selesai')

@section('content')
<div class="max-w-3xl mx-auto text-center">
    <h1 class="text-2xl font-bold mb-4">Semua Soal Selesai</h1>
    <p class="text-gray-600 mb-6">Anda telah menyelesaikan semua soal pada filter yang dipilih.</p>
    <a href="{{ route('guru.quiz.start') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Mulai Lagi</a>
    <a href="{{ route('guru.dashboard') }}" class="ml-3 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Kembali ke Dashboard</a>
</div>
@endsection
