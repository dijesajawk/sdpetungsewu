@extends('layouts.app')

@section('title', 'Review Hasil Ekstraksi File')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-2 text-gray-800"><x-icon name="clipboard-list" />  Review Hasil Ekstraksi</h1>
    <p class="text-gray-600 mb-8">Periksa dan edit hasil ekstraksi dari file <strong>{{ $file_name }}</strong> sebelum menyimpan</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Panel (Left) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-4 sticky top-4">
                <h3 class="font-semibold text-gray-800 mb-4"><x-icon name="file-text" />  Informasi File</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">File:</span>
                        <span class="font-semibold text-gray-800">{{ $file_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kelas:</span>
                        <span class="font-semibold text-gray-800">
                            @foreach($kelas as $k)
                                @if($k->id == $kelas_id)
                                    {{ $k->nama_kelas }}
                                @endif
                            @endforeach
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mapel:</span>
                        <span class="font-semibold text-gray-800">{{ $mata_pelajaran }}</span>
                    </div>
                </div>
                
                <!-- Raw Text Preview -->
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-800 mb-2"><x-icon name="file-text" />  Teks Hasil Ekstrak:</h4>
                    <div class="bg-gray-50 border border-gray-200 rounded p-3 text-xs max-h-60 overflow-y-auto text-gray-700">
                        <pre id="rawTextPreview">{{ $extractedText }}</pre>
                    </div>
                    <button type="button" onclick="document.getElementById('rawTextPreview').classList.toggle('max-h-60')" 
                        class="btn-soft btn-xs mt-2">
                        Toggle panjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Form & Processing -->
        <div class="lg:col-span-2">
            <!-- Loading -->
            <div id="loadingSpinner" class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="inline-block">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                </div>
                <p class="text-gray-700 mt-4 font-semibold">Memproses teks...</p>
                <p class="text-gray-500 text-sm mt-1">Menganalisis format soal</p>
            </div>

            <!-- Form -->
            <form id="reviewForm" action="{{ route('guru.soal.upload.save') }}" method="POST" class="hidden space-y-6">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                <input type="hidden" name="mata_pelajaran" value="{{ $mata_pelajaran }}">

                <div id="questionsContainer"></div>

                <div id="actionButtons" class="hidden bg-white rounded-lg shadow-lg p-6 space-y-4">
                    <div id="questionSummary" class="text-center p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 font-semibold"></div>
                    <div class="flex gap-4">
                        <button type="submit" class="btn-success flex-1">
                            <x-icon name="save" />  Simpan Semua Soal
                        </button>
                        <a href="{{ route('guru.soal.upload.file') }}" class="btn-soft flex-1 text-center">
                            Upload Ulang
                        </a>
                    </div>
                </div>
            </form>

            <!-- Error Message -->
            <div id="errorMessage" class="hidden bg-red-100 border border-red-400 rounded-lg p-6">
                <div id="errorText" class="text-red-800 mb-4"></div>
                <div class="flex gap-4">
                    <a href="{{ route('guru.soal.upload.file') }}" class="btn-danger btn-sm flex-1 text-center">
                        <x-icon name="refresh" />  Upload Ulang
                    </a>
                    <a href="{{ route('guru.soal.create') }}" class="btn-premium btn-sm flex-1 text-center">
                        <x-icon name="pencil" />  Input Manual
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const extractedText = @json($extractedText);

    // Parse questions from extracted text
    function parseQuestions(text) {
        const questions = [];

        let clean = text
            .replace(/\r\n/g, '\n')
            .replace(/\r/g, '\n')
            .replace(/\f/g, '\n')
            .replace(/\u00a0/g, ' ')
            .replace(/\t/g, ' ')
            .replace(/\u2013|\u2014|\u2015/g, '-')
            .replace(/\u201C|\u201D/g, '"')
            .replace(/\u2018|\u2019/g, "'");

        // PDF extraction can put multiple questions or choices on one line.
        clean = clean
            .replace(/([^\n])\s+(?=\d{1,3}\s*[\.\):])/g, '$1\n')
            .replace(/([^\n])\s+(?=\(?[A-D][\)\.\]:]\s)/g, '$1\n');

        const lines = clean.split('\n')
            .map(l => l.replace(/\s+$/, ''))
            .filter(l => l.trim().length > 0);

        // Find question start lines
        const qStarts = [];
        for (let i = 0; i < lines.length; i++) {
            const l = lines[i].trim();
            if (/^\d{1,3}[\.\\):\s]/.test(l) && !isChoiceLine(l)) {
                qStarts.push(i);
            }
        }

        // Extract questions
        for (let qi = 0; qi < qStarts.length; qi++) {
            const startLine = qStarts[qi];
            const endLine = (qi + 1 < qStarts.length) ? qStarts[qi + 1] : lines.length;

            const block = lines.slice(startLine, endLine);
            let questionText = '';
            let choiceLines = [];
            let foundFirstChoice = false;

            for (let j = 0; j < block.length; j++) {
                const bl = block[j].trim();

                if (!foundFirstChoice) {
                    if (isChoiceLine(bl)) {
                        foundFirstChoice = true;
                        choiceLines.push(bl);
                    } else {
                        let qText = bl.replace(/^\d{1,3}[\.\\):\s]+/, '').trim();
                        if (qText.length > 0) {
                            if (questionText.length > 0) questionText += ' ';
                            questionText += qText;
                        }
                    }
                } else {
                    if (isChoiceLine(bl)) {
                        choiceLines.push(bl);
                    } else if (choiceLines.length > 0) {
                        choiceLines[choiceLines.length - 1] += ' ' + bl;
                    }
                }
            }

            // Parse choices
            const choices = {};
            let correctAnswer = 'a';
            for (const cl of choiceLines) {
                const parsed = parseChoiceLine(cl);
                if (parsed) {
                    choices[parsed.letter] = parsed.text;
                }
            }

            const answerLine = block.find(line => /^jawaban\s*:/i.test(line.trim()));
            const answerMatch = answerLine && answerLine.match(/^jawaban\s*:\s*\(?([a-d])\)?/i);
            if (answerMatch) {
                correctAnswer = answerMatch[1].toLowerCase();
            }

            if (questionText.length >= 5 && Object.keys(choices).length >= 2) {
                questions.push({
                    pertanyaan: questionText.substring(0, 500),
                    pilihan_a: (choices['a'] || '').substring(0, 200),
                    pilihan_b: (choices['b'] || '').substring(0, 200),
                    pilihan_c: (choices['c'] || '').substring(0, 200),
                    pilihan_d: (choices['d'] || '').substring(0, 200),
                    jawaban_benar: correctAnswer
                });
            }
        }

        return questions;
    }

    // Fallback parser
    function parseQuestionsFallback(text) {
        const questions = [];
        const blocks = text.split(/\n\s*\n/);

        for (const block of blocks) {
            const lines = block.split('\n').map(l => l.trim()).filter(l => l);
            if (lines.length < 2) continue;

            let qText = '';
            const choices = {};

            for (const line of lines) {
                if (isChoiceLine(line)) {
                    const p = parseChoiceLine(line);
                    if (p) choices[p.letter] = p.text;
                } else if (qText.length === 0) {
                    qText = line.replace(/^\d{1,3}[\.\\):\s]+/, '').trim();
                }
            }

            if (qText.length >= 5 && Object.keys(choices).length >= 2) {
                questions.push({
                    pertanyaan: qText.substring(0, 500),
                    pilihan_a: (choices['a'] || '').substring(0, 200),
                    pilihan_b: (choices['b'] || '').substring(0, 200),
                    pilihan_c: (choices['c'] || '').substring(0, 200),
                    pilihan_d: (choices['d'] || '').substring(0, 200),
                    jawaban_benar: 'a'
                });
            }
        }

        return questions;
    }

    function isChoiceLine(line) {
        const l = line.trim();
        if (/^[a-d][\.\):\]\s]/i.test(l)) return true;
        if (/^\([a-d]\)/i.test(l)) return true;
        if (/^[a-d][\s,]+[A-Za-z0-9]/i.test(l) && l.length > 3) return true;
        return false;
    }

    function parseChoiceLine(line) {
        const l = line.trim();
        const patterns = [
            /^\(?([a-d])\)?[\.\):\]\s]+(.+)/i,
            /^([a-d])\s{2,}(.+)/i,
            /^([a-d])[\s,]+(.+)/i,
        ];

        for (const pat of patterns) {
            const m = l.match(pat);
            if (m && m[2] && m[2].trim().length > 0) {
                return { letter: m[1].toLowerCase(), text: m[2].trim() };
            }
        }
        return null;
    }

    function displayQuestions(questions) {
        const container = document.getElementById('questionsContainer');
        container.innerHTML = '';

        questions.forEach((q, i) => {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500';
            div.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <span class="bg-purple-100 text-purple-800 text-sm font-semibold px-3 py-1 rounded-full">Soal #${i + 1}</span>
                    <button type="button" onclick="this.closest('.bg-white').remove()" class="btn-danger btn-sm"> Hapus</button>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Pertanyaan</label>
                    <textarea name="questions[${i}][pertanyaan]" required rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Edit pertanyaan...">${escapeHtml(q.pertanyaan)}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">Pilihan A</label>
                        <input type="text" name="questions[${i}][pilihan_a]" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                            value="${escapeHtml(q.pilihan_a)}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">Pilihan B</label>
                        <input type="text" name="questions[${i}][pilihan_b]" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                            value="${escapeHtml(q.pilihan_b)}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">Pilihan C</label>
                        <input type="text" name="questions[${i}][pilihan_c]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                            value="${escapeHtml(q.pilihan_c)}" placeholder="(opsional)">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">Pilihan D</label>
                        <input type="text" name="questions[${i}][pilihan_d]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                            value="${escapeHtml(q.pilihan_d)}" placeholder="(opsional)">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Jawaban Benar</label>
                    <select name="questions[${i}][jawaban_benar]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
            `;
            container.appendChild(div);
        });
    }

    function showError(message) {
        document.getElementById('loadingSpinner').classList.add('hidden');
        document.getElementById('errorText').innerHTML = `<strong>Error:</strong><br>${message}`;
        document.getElementById('errorMessage').classList.remove('hidden');
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text || '';
        return d.innerHTML;
    }

    // Process on page load
    window.addEventListener('load', function() {
        try {
            document.getElementById('loadingSpinner').classList.add('hidden');

            let questions = parseQuestions(extractedText);

            if (questions.length === 0) {
                questions = parseQuestionsFallback(extractedText);
            }

            if (questions.length === 0) {
                showError(`Tidak ada soal yang terdeteksi dari teks file.<br><br>
                    <strong>Kemungkinan penyebab:</strong><br>
                    • Format soal tidak sesuai<br>
                    • Teks tidak terstruktur dengan baik<br><br>
                    <strong>Format yang didukung:</strong><br>
                    <code>1. Pertanyaan?<br>A) Jawaban A<br>B) Jawaban B<br>C) Jawaban C<br>D) Jawaban D</code>`);
                return;
            }

            displayQuestions(questions);
            document.getElementById('reviewForm').classList.remove('hidden');
            document.getElementById('actionButtons').classList.remove('hidden');
            document.getElementById('questionSummary').textContent = ` ${questions.length} soal berhasil dideteksi dari file`;

        } catch (error) {
            console.error('Parse Error:', error);
            showError('Gagal memproses teks: ' + error.message);
        }
    });
</script>
@endsection
