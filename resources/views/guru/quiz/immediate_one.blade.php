@extends('layouts.app')

@section('title', 'Soal')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold">Soal {{ $index + 1 }} / {{ $total }}</h1>
        </div>
        <a href="#" id="ubahFilterLink" class="text-blue-600 hover:underline">← Ubah Filter</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('guru.quiz.immediate.answer') }}" method="POST" class="bg-white p-6 rounded-xl shadow">
        @csrf
        <div class="mb-4">
            <div class="font-semibold text-lg text-gray-800">{!! nl2br(e($soal->pertanyaan)) !!}</div>
            <input type="hidden" name="soal_id" value="{{ $soal->id }}">
        </div>

        <div class="grid grid-cols-1 gap-3">
            <label class="p-3 border rounded flex items-center gap-3">
                <input type="radio" name="jawaban" value="a" required>
                <span>A. {{ $soal->pilihan_a }}</span>
            </label>

            <label class="p-3 border rounded flex items-center gap-3">
                <input type="radio" name="jawaban" value="b" required>
                <span>B. {{ $soal->pilihan_b }}</span>
            </label>

            <label class="p-3 border rounded flex items-center gap-3">
                <input type="radio" name="jawaban" value="c" required>
                <span>C. {{ $soal->pilihan_c }}</span>
            </label>

            <label class="p-3 border rounded flex items-center gap-3">
                <input type="radio" name="jawaban" value="d" required>
                <span>D. {{ $soal->pilihan_d }}</span>
            </label>
        </div>

        <div class="flex justify-between mt-6 items-center">
            <div class="flex gap-3">
                <button type="button" id="exitButton" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Keluar</button>
                <button type="button" id="reshuffleBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Acak Ulang</button>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">Jawab</button>
        </div>
    </form>
</div>

<!-- Exit Confirmation Modal -->
<div id="exitModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-md p-6">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi</h3>
        <p class="text-gray-700 mb-6">Anda yakin ingin keluar dari Soal Langsung? Pilihan Anda tidak akan tersimpan.</p>
        <div class="flex justify-end gap-3">
            <button id="cancelExit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">Batal</button>
            <button id="confirmExit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Keluar</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const link = document.getElementById('ubahFilterLink');
    let pendingRedirect = null;

    function showExitModal(redirectUrl){
        pendingRedirect = redirectUrl || '{{ route('guru.dashboard') }}';
        const modal = document.getElementById('exitModal');
        if (modal) modal.classList.remove('hidden');
    }

    function hideExitModal(){
        const modal = document.getElementById('exitModal');
        if (modal) modal.classList.add('hidden');
    }

    if (link) {
        link.addEventListener('click', function(e){
            e.preventDefault();
            showExitModal('{{ route('guru.quiz.start') }}');
        });
    }

    const exitButton = document.getElementById('exitButton');
    if (exitButton) {
        exitButton.addEventListener('click', function(e){
            showExitModal('{{ route('guru.dashboard') }}');
        });
    }

    const cancelBtn = document.getElementById('cancelExit');
    if (cancelBtn) cancelBtn.addEventListener('click', hideExitModal);

    const confirmBtn = document.getElementById('confirmExit');
    if (confirmBtn) confirmBtn.addEventListener('click', function(){
        const token = '{{ csrf_token() }}';
        const exitUrl = '{{ route('guru.quiz.immediate.exit') }}';
        fetch(exitUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        }).then(resp => {
            hideExitModal();
            window.location.href = pendingRedirect || '{{ route('guru.dashboard') }}';
        }).catch(err => {
            hideExitModal();
            window.location.href = pendingRedirect || '{{ route('guru.dashboard') }}';
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const reshuffleBtn = document.getElementById('reshuffleBtn');
    if (!reshuffleBtn) return;

    reshuffleBtn.addEventListener('click', function(){
        const token = '{{ csrf_token() }}';
        const url = '{{ route('guru.quiz.immediate.reshuffle') }}';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        }).then(resp => {
            // reload to reflect new order
            window.location.reload();
        }).catch(err => {
            window.location.reload();
        });
    });
});
</script>

@endsection

<!-- Toast handled globally in layout -->
