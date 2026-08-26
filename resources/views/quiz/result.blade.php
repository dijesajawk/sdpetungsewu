<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Quiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
</head>
<body class="quiz-page">
    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Header Hasil -->
        <div class="quiz-panel p-8 text-center">
            <div class="text-6xl mb-4">
                @if ($skorFinal >= 80)
                    🎉
                @elseif ($skorFinal >= 60)
                    👍
                @else
                    😊
                @endif
            </div>
            <h1 class="text-3xl font-bold mb-2 
                @if ($skorFinal >= 80)
                    text-green-600
                @elseif ($skorFinal >= 60)
                    text-yellow-600
                @else
                    text-blue-600
                @endif">
                @if ($skorFinal >= 80)
                    Sempurna!
                @elseif ($skorFinal >= 60)
                    Bagus!
                @else
                    Tetap Semangat!
                @endif
            </h1>
            <p class="text-gray-600 mb-6">Quiz Selesai</p>
        </div>

        <!-- Score Card -->
        <div class="quiz-panel p-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="text-center">
                    <p class="text-gray-600 text-sm mb-1">Total Soal</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalSoal }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm mb-1">Terjawab</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalJawab }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm mb-1">Benar</p>
                    <p class="text-3xl font-bold text-green-600">{{ $benarJawab }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm mb-1">Skor</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $skorFinal }}%</p>
                </div>
            </div>

            <!-- Progress Bar Skor -->
            <div class="mb-6">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Tingkat Pencapaian</span>
                    <span>{{ $skorFinal }}%</span>
                </div>
                <div class="h-4 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-500" 
                        style="width: {{ $skorFinal }}%;"></div>
                </div>
            </div>

            <div class="text-center text-gray-600">
                <p class="text-sm">Nama: <span class="font-bold">{{ $namurid }}</span></p>
                <p class="text-xs mt-2">Kode Sesi: {{ $session->session_code }}</p>
            </div>
        </div>

        <!-- Detail Jawaban -->
        @if ($jawabanList->count() > 0)
            <div class="quiz-panel p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">📋 Detail Jawaban</h2>
                
                <div class="space-y-4">
                    @foreach ($jawabanList as $key => $jawaban)
                        <div class="border {{ $jawaban->is_correct ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }} p-4 rounded-lg">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold">Soal {{ $key + 1 }}</span>
                                        @if ($jawaban->is_correct)
                                            <span class="inline-block bg-green-200 text-green-800 px-3 py-1 rounded-full text-xs font-bold">✓ Benar</span>
                                        @else
                                            <span class="inline-block bg-red-200 text-red-800 px-3 py-1 rounded-full text-xs font-bold">✕ Salah</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-700 mt-2">{{ Str::limit($jawaban->soal->pertanyaan, 100) }}</p>
                                </div>
                            </div>
                            
                            <div class="text-sm mt-3 space-y-1">
                                <p><strong>Jawaban Anda:</strong> {{ strtoupper($jawaban->jawaban_dipilih) }}. 
                                    @if ($jawaban->jawaban_dipilih === 'a')
                                        {{ $jawaban->soal->pilihan_a }}
                                    @elseif ($jawaban->jawaban_dipilih === 'b')
                                        {{ $jawaban->soal->pilihan_b }}
                                    @elseif ($jawaban->jawaban_dipilih === 'c')
                                        {{ $jawaban->soal->pilihan_c }}
                                    @else
                                        {{ $jawaban->soal->pilihan_d }}
                                    @endif
                                </p>
                                @if (!$jawaban->is_correct)
                                    <p><strong class="text-green-700">Jawaban Benar:</strong> {{ strtoupper($jawaban->soal->jawaban_benar) }}. 
                                        @if ($jawaban->soal->jawaban_benar === 'a')
                                            {{ $jawaban->soal->pilihan_a }}
                                        @elseif ($jawaban->soal->jawaban_benar === 'b')
                                            {{ $jawaban->soal->pilihan_b }}
                                        @elseif ($jawaban->soal->jawaban_benar === 'c')
                                            {{ $jawaban->soal->pilihan_c }}
                                        @else
                                            {{ $jawaban->soal->pilihan_d }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg text-center">
            <p class="text-blue-800 mb-4">
                Quiz Anda telah selesai dan hasilnya telah tersimpan.
            </p>
            <p class="text-sm text-blue-700">
                Silakan hubungi guru Anda jika ada pertanyaan tentang jawaban Anda.
            </p>
        </div>
    </div>
</body>
</html>
