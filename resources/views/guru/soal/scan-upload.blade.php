@extends('layouts.app')

@section('title', 'Scan Soal Otomatis')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-2 text-gray-800">📸 Scan Soal Otomatis</h1>
    <p class="text-gray-600 mb-8">Upload foto soal untuk ekstrak otomatis menggunakan OCR (Optical Character Recognition)</p>

    <form action="{{ route('guru.soal.scan.process', [], false) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg p-8 space-y-6" id="scanForm">
        @csrf

        <!-- Image Upload -->
        <div class="border-2 border-dashed border-blue-400 rounded-lg p-8 text-center bg-blue-50 cursor-pointer hover:bg-blue-100 transition"
            id="uploadArea">
            <input type="file" id="imageInput" name="images[]" accept="image/*" class="hidden" multiple required>
            
            <svg class="mx-auto h-12 w-12 text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            
            <p class="text-gray-700 font-semibold mb-2">Klik atau drag gambar soal ke sini</p>
            <p class="text-gray-500 text-sm">Bisa upload beberapa foto sekaligus (max 5MB per foto)</p>
        </div>

        <!-- Image Preview -->
        <div id="previewContainer" class="hidden">
            <label class="block text-gray-700 font-semibold mb-2">Preview Gambar (<span id="imageCount">0</span> foto):</label>
            <div id="previewGrid" class="grid grid-cols-2 md:grid-cols-3 gap-4"></div>
            <button type="button" id="removeImage" class="mt-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Hapus Semua & Ganti
            </button>
        </div>

        <!-- Kelas -->
        <div>
            <label for="kelas_id" class="block text-gray-700 font-semibold mb-2">Pilih Kelas</label>
            <select id="kelas_id" name="kelas_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Kelas --</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
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
                @foreach (['Agama', 'PPKn', 'Bahasa Indonesia', 'Matematika', 'IPA', 'IPS', 'PJOK', 'Seni Budaya', 'Bahasa Inggris'] as $mapel)
                    <option value="{{ $mapel }}">{{ $mapel }}</option>
                @endforeach
            </select>
            @error('mata_pelajaran')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tips -->
        <div class="bg-blue-100 border border-blue-400 rounded-lg p-4 space-y-3">
            <p class="text-blue-800 text-sm font-semibold">💡 Tips untuk Hasil Terbaik:</p>
            <ul class="text-blue-800 text-sm ml-4 space-y-2 list-disc">
                <li>Foto soal jelas, terang, dan tidak blur</li>
                <li>Teks mudah dibaca (tidak miring atau terpotong)</li>
                <li>Bisa beberapa soal per foto, akan dideteksi otomatis</li>
                <li>Format: <strong>1. Pertanyaan?</strong> diikuti <strong>A) Jawaban</strong></li>
            </ul>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button type="submit" id="submitBtn" disabled
                class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-3 px-4 rounded-lg transition">
                Proses & Scan
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
        <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mx-auto mb-4"></div>
        <p class="text-xl font-bold text-gray-800 mb-2">Mengupload gambar...</p>
        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
            <div id="uploadProgress" class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p id="uploadPercent" class="text-gray-600 text-sm">0%</p>
        <p class="text-gray-500 text-xs mt-4">Mohon tunggu, jangan tutup halaman ini</p>
    </div>
</div>

<script>
    const uploadArea = document.getElementById('uploadArea');
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('previewContainer');
    const previewGrid = document.getElementById('previewGrid');
    const imageCount = document.getElementById('imageCount');
    const removeImageBtn = document.getElementById('removeImage');
    const submitBtn = document.getElementById('submitBtn');
    const scanForm = document.getElementById('scanForm');
    const loadingOverlay = document.getElementById('loadingOverlay');

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('bg-blue-200', 'border-blue-600');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('bg-blue-200', 'border-blue-600');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('bg-blue-200', 'border-blue-600');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            handleImageSelect();
        }
    });

    uploadArea.addEventListener('click', () => imageInput.click());
    imageInput.addEventListener('change', handleImageSelect);

    function handleImageSelect() {
        const files = imageInput.files;
        if (files.length > 0) {
            previewGrid.innerHTML = '';
            imageCount.textContent = files.length;

            Array.from(files).forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${i+1}" class="w-full h-32 object-cover border rounded-lg shadow">
                        <span class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-2 py-1 rounded">${i+1}</span>
                    `;
                    previewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });

            previewContainer.classList.remove('hidden');
            uploadArea.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

    removeImageBtn.addEventListener('click', () => {
        imageInput.value = '';
        previewContainer.classList.add('hidden');
        previewGrid.innerHTML = '';
        uploadArea.classList.remove('hidden');
        submitBtn.disabled = true;
    });

    // Show loading overlay on submit with upload progress
    scanForm.addEventListener('submit', function(e) {
        if (imageInput.files.length === 0) return;

        e.preventDefault();
        loadingOverlay.classList.remove('hidden');

        const formData = new FormData(scanForm);
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

        xhr.open('POST', scanForm.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    });
</script>
@endsection
