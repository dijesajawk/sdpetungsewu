@extends('layouts.app')

@section('title', 'Upload File Soal')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-2 text-gray-800">📄 Upload File Soal</h1>
    <p class="text-gray-600 mb-8">Upload file PDF atau Word untuk mengekstrak soal secara otomatis</p>

    <form action="{{ route('guru.soal.upload.process') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg p-8 space-y-6">
        @csrf

        <!-- File Upload -->
        <div class="border-2 border-dashed border-purple-400 rounded-lg p-8 text-center bg-purple-50 cursor-pointer hover:bg-purple-100 transition"
            id="uploadArea">
            <input type="file" id="fileInput" name="files[]" accept=".pdf,.doc,.docx" class="hidden" multiple required>
            
            <svg class="mx-auto h-12 w-12 text-purple-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            
            <p class="text-gray-700 font-semibold mb-2">Klik atau drag file ke sini</p>
            <p class="text-gray-500 text-sm">Bisa pilih beberapa file sekaligus dari folder (PDF, DOC, DOCX, max 10MB per file)</p>
        </div>

        <!-- File Preview -->
        <div id="filePreview" class="hidden">
            <label class="block text-gray-700 font-semibold mb-2">File yang dipilih (<span id="fileCount">0</span> file):</label>
            <div id="fileNames" class="bg-green-50 border border-green-200 rounded-lg p-3 text-green-800"></div>
            <button type="button" id="removeFile" class="mt-2 bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-1 px-3 rounded">
                Hapus & Ganti
            </button>
        </div>

        <!-- Kelas -->
        <div>
            <label for="kelas_id" class="block text-gray-700 font-semibold mb-2">Pilih Kelas</label>
            <select id="kelas_id" name="kelas_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Kelas --</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
            @error('kelas_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Mata Pelajaran -->
        <div>
            <label for="mata_pelajaran" class="block text-gray-700 font-semibold mb-2">Mata Pelajaran</label>
            <select id="mata_pelajaran" name="mata_pelajaran" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Mata Pelajaran --</option>
                @foreach ($mataPelajaran as $mapel)
                    <option value="{{ $mapel }}" {{ old('mata_pelajaran') == $mapel ? 'selected' : '' }}>
                        {{ $mapel }}
                    </option>
                @endforeach
            </select>
            @error('mata_pelajaran')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tips -->
        <div class="bg-purple-100 border border-purple-400 rounded-lg p-4 space-y-3">
            <p class="text-purple-800 text-sm font-semibold">💡 Tips untuk Hasil Terbaik:</p>
            <ul class="text-purple-800 text-sm ml-4 space-y-2 list-disc">
                <li>File harus berisi teks yang bisa dibaca (bukan scan gambar)</li>
                <li>Format soal yang didukung: <strong>1. Pertanyaan?</strong> diikuti <strong>A) Jawaban</strong></li>
                <li>Untuk file scan/gambar, gunakan fitur <strong>Scan Soal</strong> di halaman lain</li>
                <li>Pastikan file tidak terlalu besar (max 10MB)</li>
            </ul>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button type="submit" id="submitBtn" disabled
                class="flex-1 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white font-bold py-3 px-4 rounded-lg transition">
                📤 Upload & Proses
            </button>
            <a href="{{ route('guru.soal.list') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                Batal
            </a>
        </div>
    </form>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 text-center shadow-2xl">
        <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-purple-600 mx-auto mb-4"></div>
        <p class="text-xl font-bold text-gray-800 mb-2">Mengupload file...</p>
        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
            <div id="uploadProgress" class="bg-purple-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p id="uploadPercent" class="text-gray-600 text-sm">0%</p>
        <p class="text-gray-500 text-xs mt-4">Mohon tunggu, jangan tutup halaman ini</p>
    </div>
</div>

<script>
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileNames = document.getElementById('fileNames');
    const fileCount = document.getElementById('fileCount');
    const removeFileBtn = document.getElementById('removeFile');
    const submitBtn = document.getElementById('submitBtn');
    const uploadForm = document.querySelector('form');
    const loadingOverlay = document.getElementById('loadingOverlay');

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('bg-purple-200', 'border-purple-600');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('bg-purple-200', 'border-purple-600');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('bg-purple-200', 'border-purple-600');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect();
        }
    });

    uploadArea.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const files = fileInput.files;
        if (files.length > 0) {
            fileNames.innerHTML = '';
            fileCount.textContent = files.length;

            Array.from(files).forEach((file, i) => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between py-1 border-b border-green-200 last:border-b-0';
                div.innerHTML = `<span>📄 ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>`;
                fileNames.appendChild(div);
            });

            filePreview.classList.remove('hidden');
            uploadArea.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

    removeFileBtn.addEventListener('click', () => {
        fileInput.value = '';
        fileNames.innerHTML = '';
        fileCount.textContent = '0';
        filePreview.classList.add('hidden');
        uploadArea.classList.remove('hidden');
        submitBtn.disabled = true;
    });

    // Show loading overlay on submit with upload progress
    uploadForm.addEventListener('submit', function(e) {
        if (fileInput.files.length === 0) return;

        e.preventDefault();
        loadingOverlay.classList.remove('hidden');

        const formData = new FormData(uploadForm);
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const pct = Math.round((e.loaded / e.total) * 100);
                document.getElementById('uploadProgress').style.width = pct + '%';
                document.getElementById('uploadPercent').textContent = pct + '%';
            }
        });

        xhr.addEventListener('load', () => {
            if (xhr.status >= 200 && xhr.status < 400) {
                document.open();
                document.write(xhr.responseText);
                document.close();
            } else {
                loadingOverlay.classList.add('hidden');
                alert('Gagal upload. Silakan coba lagi.');
            }
        });

        xhr.addEventListener('error', () => {
            loadingOverlay.classList.add('hidden');
            alert('Terjadi error saat upload. Silakan coba lagi.');
        });

        xhr.open('POST', uploadForm.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    });
</script>
@endsection
