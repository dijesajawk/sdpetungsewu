@extends('layouts.app')

@section('title', 'Review & Edit Soal Scan')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-2 text-gray-800"><x-icon name="clipboard-list" />  Review Soal Hasil Scan</h1>
    <p class="text-gray-600 mb-8">Periksa dan edit hasil OCR sebelum menyimpan ke database</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Image Preview (Left) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-4 sticky top-4">
                <h3 class="font-semibold text-gray-800 mb-4">Gambar Asli</h3>
                <div class="space-y-4" id="imagePreviewList"></div>
            </div>
        </div>

        <!-- Form & Processing -->
        <div class="lg:col-span-2">
            <!-- Loading -->
            <div id="loadingSpinner" class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="inline-block">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>
                <p id="statusText" class="text-gray-700 mt-4 font-semibold">Memulai OCR...</p>
                <p class="text-gray-500 text-sm mt-1" id="statusDetail">Mempersiapkan worker OCR</p>
                <div class="mt-4 w-full bg-gray-200 rounded-full h-3">
                    <div id="progressFill" class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="progressText" class="text-gray-600 text-xs mt-2">0%</p>
            </div>

            <!-- Form -->
            <form id="scanForm" action="{{ route('guru.soal.scan.save', [], false) }}" method="POST" class="hidden space-y-6">
                @csrf

                @if(isset($imagePaths))
                    @foreach($imagePaths as $path)
                        <input type="hidden" name="imagePaths[]" value="{{ $path }}">
                    @endforeach
                @endif
                <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

                <div id="questionsContainer"></div>

                <div id="actionButtons" class="hidden bg-white rounded-lg shadow-lg p-6 space-y-4">
                    <div id="questionSummary" class="text-center p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 font-semibold"></div>
                    <div class="flex gap-4">
                        <button type="submit" class="btn-success flex-1">
                            <x-icon name="save" />  Simpan Semua Soal
                        </button>
                        <a href="{{ route('guru.soal.scan.upload') }}" class="btn-soft flex-1 text-center">
                            Scan Ulang
                        </a>
                    </div>
                </div>
            </form>

            <!-- Error Message -->
            <div id="errorMessage" class="hidden bg-red-100 border border-red-400 rounded-lg p-6">
                <div id="errorText" class="text-red-800 mb-4"></div>
                <div class="flex gap-4">
                    <a href="{{ route('guru.soal.scan.upload') }}" class="btn-danger btn-sm flex-1 text-center">
                        <x-icon name="refresh" />  Scan Ulang
                    </a>
                    <a href="{{ route('guru.soal.create') }}" class="btn-premium btn-sm flex-1 text-center">
                        <x-icon name="pencil" />  Input Manual
                    </a>
                </div>
            </div>

            <!-- Debug: Raw Text -->
            <div id="debugSection" class="hidden mt-4 bg-gray-50 border border-gray-300 rounded-lg p-4">
                <button type="button" onclick="document.getElementById('debugText').classList.toggle('hidden')" class="btn-soft btn-xs">
                    <x-icon name="file-text" />  Lihat Teks Hasil Ekstrak (Debug)
                </button>
                <pre id="debugText" class="hidden mt-2 bg-white p-3 rounded text-xs max-h-60 overflow-y-auto border text-gray-700"></pre>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

<script>
    const imageUrls = {!! json_encode($imageUrls ?? []) !!};
    const imagePaths = {!! json_encode($imagePaths ?? []) !!};

    // Display image previews
    const previewList = document.getElementById('imagePreviewList');
    imageUrls.forEach((url, i) => {
        const img = document.createElement('img');
        img.src = url;
        img.alt = 'Scanned ' + (i+1);
        img.className = 'w-full border rounded-lg shadow';
        previewList.appendChild(img);
    });

    function updateProgress(pct, status, detail) {
        document.getElementById('progressFill').style.width = pct + '%';
        document.getElementById('progressText').textContent = pct + '%';
        if (status) document.getElementById('statusText').textContent = status;
        if (detail) document.getElementById('statusDetail').textContent = detail;
    }

    async function processImage() {
        try {
            updateProgress(5, 'Mempersiapkan OCR...', 'Memuat Tesseract.js worker');

            const worker = await Tesseract.createWorker('eng', 1, {
                logger: m => {
                    if (m.status === 'recognizing text') {
                        const pct = Math.round(m.progress * 100);
                        updateProgress(40 + Math.round(pct * 0.55), 'Membaca teks dari gambar...', `Mengenali karakter: ${pct}%`);
                    } else if (m.status === 'loading language traineddata') {
                        const pct = Math.round(m.progress * 100);
                        updateProgress(5 + Math.round(pct * 0.25), 'Mengunduh data bahasa...', `${pct}% selesai`);
                    } else if (m.status === 'initializing api') {
                        updateProgress(32, 'Inisialisasi OCR engine...', 'Hampir siap');
                    } else if (m.status === 'loaded tesseract core') {
                        updateProgress(8, 'Core OCR dimuat', 'Memuat data bahasa...');
                    } else if (m.status === 'initialized tesseract') {
                        updateProgress(10, 'OCR siap', 'Mengunduh data bahasa...');
                    }
                }
            });

            let allText = '';

            for (let i = 0; i < imageUrls.length; i++) {
                updateProgress(35 + Math.round((i / imageUrls.length) * 5), `Scan gambar ${i + 1} dari ${imageUrls.length}...`, 'Membaca teks');

                const result = await worker.recognize(imageUrls[i]);
                allText += '\n\n' + result.data.text;

                console.log(`Image ${i+1} text:`, result.data.text);
                console.log(`Image ${i+1} confidence:`, result.data.confidence);
            }

            await worker.terminate();

            updateProgress(98, 'Memproses hasil...', 'Parsing soal');

            console.log('=== ALL EXTRACTED TEXT ===');
            console.log(allText);

            document.getElementById('debugText').textContent = allText.trim();
            document.getElementById('debugSection').classList.remove('hidden');

            let questions = parseQuestions(allText);

            if (questions.length === 0) {
                questions = parseQuestionsFallback(allText);
            }

            console.log('Parsed questions:', questions);

            if (questions.length === 0) {
                showError(`Tidak ada soal yang terdeteksi.<br><br>
                    <strong>Kemungkinan penyebab:</strong><br>
                    • Foto kurang jelas atau blur<br>
                    • Format soal tidak sesuai<br>
                    • Teks terlalu kecil<br><br>
                    <strong>Format yang didukung:</strong><br>
                    <code>1. Pertanyaan?<br>A) Jawaban A<br>B) Jawaban B<br>C) Jawaban C<br>D) Jawaban D</code><br><br>
                    atau <code>A. Jawaban</code>, <code>A: Jawaban</code>`);
                return;
            }

            displayQuestions(questions);
            updateProgress(100, 'Selesai!', `${questions.length} soal terdeteksi`);

            setTimeout(() => {
                document.getElementById('loadingSpinner').classList.add('hidden');
                document.getElementById('scanForm').classList.remove('hidden');
                document.getElementById('actionButtons').classList.remove('hidden');
                document.getElementById('questionSummary').textContent = ` ${questions.length} soal berhasil dideteksi dari ${imageUrls.length} gambar`;
            }, 500);

        } catch (error) {
            console.error('OCR Error:', error);
            showError('Gagal memproses gambar: ' + error.message + '<br><br>Silakan coba lagi dengan gambar yang lebih jelas.');
        }
    }

    // === MAIN PARSER ===
    function parseQuestions(text) {
        const questions = [];

        let clean = text
            .replace(/\r\n/g, '\n')
            .replace(/\r/g, '\n')
            .replace(/\t/g, ' ')
            .replace(/\u2013|\u2014|\u2015/g, '-')
            .replace(/\u201C|\u201D/g, '"')
            .replace(/\u2018|\u2019/g, "'");

        const lines = clean.split('\n')
            .map(l => l.replace(/\s+$/, ''))
            .filter(l => l.trim().length > 0);

        // Step 1: Find all question start lines
        const qStarts = [];
        for (let i = 0; i < lines.length; i++) {
            const l = lines[i].trim();
            // Match: "1." "1)" "1:" "(1)" "1 " followed by non-choice text
            if (/^\d{1,3}[\.\)\:\s]/.test(l) && !isChoiceLine(l)) {
                qStarts.push(i);
            }
        }

        // Step 2: For each question, extract text + choices
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
                        // Remove question number prefix
                        let qText = bl.replace(/^\d{1,3}[\.\)\:\s]+/, '').trim();
                        if (qText.length > 0) {
                            if (questionText.length > 0) questionText += ' ';
                            questionText += qText;
                        }
                    }
                } else {
                    if (isChoiceLine(bl)) {
                        choiceLines.push(bl);
                    } else if (choiceLines.length > 0) {
                        // Append to last choice (multi-line)
                        choiceLines[choiceLines.length - 1] += ' ' + bl;
                    }
                }
            }

            // Parse choices
            const choices = {};
            for (const cl of choiceLines) {
                const parsed = parseChoiceLine(cl);
                if (parsed) {
                    choices[parsed.letter] = parsed.text;
                }
            }

            // Validate: need question text + at least 2 choices
            if (questionText.length >= 5 && Object.keys(choices).length >= 2) {
                questions.push({
                    pertanyaan: questionText.substring(0, 500),
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

    // === FALLBACK PARSER: more aggressive ===
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
                    qText = line.replace(/^\d{1,3}[\.\)\:\s]+/, '').trim();
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
        // A) A. A: A] (A)
        if (/^[a-d][\)\.\:\]\s]/i.test(l)) return true;
        if (/^\([a-d]\)/i.test(l)) return true;
        // "a " or "a," at start (lowercase, only if followed by text)
        if (/^[a-d][\s,]+[A-Za-z0-9]/i.test(l) && l.length > 3) return true;
        return false;
    }

    function parseChoiceLine(line) {
        const l = line.trim();
        const patterns = [
            /^\(?([a-d])\)?[\)\.\:\]\s]+(.+)/i,
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
            div.className = 'bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500';
            div.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">Soal #${i + 1}</span>
                    <button type="button" onclick="this.closest('.bg-white').remove()" class="btn-danger btn-sm"> Hapus</button>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Pertanyaan</label>
                    <textarea name="questions[${i}][pertanyaan]" required rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Edit pertanyaan...">${escapeHtml(q.pertanyaan)}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">Pilihan A</label>
                        <input type="text" name="questions[${i}][pilihan_a]" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                            value="${escapeHtml(q.pilihan_a)}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">Pilihan B</label>
                        <input type="text" name="questions[${i}][pilihan_b]" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                            value="${escapeHtml(q.pilihan_b)}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">Pilihan C</label>
                        <input type="text" name="questions[${i}][pilihan_c]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                            value="${escapeHtml(q.pilihan_c)}" placeholder="(opsional)">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">Pilihan D</label>
                        <input type="text" name="questions[${i}][pilihan_d]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                            value="${escapeHtml(q.pilihan_d)}" placeholder="(opsional)">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">Jawaban Benar</label>
                    <select name="questions[${i}][jawaban_benar]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
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

    window.addEventListener('load', processImage);
</script>
@endsection
