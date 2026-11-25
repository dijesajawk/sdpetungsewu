@extends('layouts.app')

@section('title', 'Selesai')

@section('content')
<div class="max-w-3xl mx-auto text-center">
    <h1 class="text-2xl font-bold mb-4">Semua Soal Selesai</h1>
    <p class="text-gray-600 mb-6">Anda telah menyelesaikan semua soal pada filter yang dipilih.</p>
    <a href="{{ route('guru.quiz.start') }}" class="btn-premium btn-sm">Mulai Lagi</a>
    <a href="{{ route('guru.dashboard') }}" class="btn-soft btn-sm ml-3">Kembali ke Dashboard</a>
</div>
@endsection
