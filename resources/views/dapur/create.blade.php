@extends('layouts.app')

@section('title', 'Dapur Dashboard')

@section('navbar')
    @include('layouts.navbar', [
        'routes' => [
            'index' => 'dapur.index',
            'permintaan' => 'dapur.create',
        ],
        'labels' => [
            'index' => 'Status Permintaan',
            'permintaan' => 'Tambah Permintaan',
        ]
    ])
@endsection

@section('content')
<h1 class="text-2xl font-bold mb-6 text-center">Buat Permintaan Bahan</h1>

@if(session('success'))
<div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
    {{ session('success') }}
</div>

@endif
    <form action="{{ route('dapur.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label>Tanggal Masak</label>
            <input type="date" name="tgl_masak" required class="border px-2 py-1 w-full">
        </div>

        <div class="mb-4">
            <label>Menu yang akan dibuat</label>
            <input type="text" name="menu_makan" required class="border px-2 py-1 w-full">
        </div>

        <div class="mb-4">
            <label>Jumlah Porsi</label>
            <input type="number" name="jumlah_porsi" min="1" required class="border px-2 py-1 w-full">
        </div>

        <h2 class="font-bold mt-4 mb-2">Daftar Bahan Baku</h2>
        <div id="bahan-wrapper">
            <div class="flex gap-2 mb-2 bahan-item">
                <select name="bahan_id[]" class="border px-2 py-1">
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($bahan as $b)
                        <option value="{{ $b->id }}">{{ $b->nama }} ({{ $b->jumlah }})</option>
                    @endforeach
                </select>
                <input type="number" name="jumlah_bahan[]" min="1" placeholder="Jumlah" class="border px-2 py-1">
                <button type="button" class="remove-bahan bg-red-500 text-white px-2 rounded">X</button>
            </div>
        </div>

        <button type="button" id="add-bahan" class="bg-blue-600 text-white px-3 py-1 rounded mb-4">Tambah Bahan</button>
        <br>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Buat Permintaan</button>
    </form>
@endsection

@section('scripts')
    <script>
        // Event listener untuk tombol "Tambah Bahan"
        document.getElementById('add-bahan').addEventListener('click', function() {
            const wrapper = document.getElementById('bahan-wrapper');
            const newItem = wrapper.querySelector('.bahan-item').cloneNode(true);
            newItem.querySelector('input').value = '';
            wrapper.appendChild(newItem);
        });

        // Event listener untuk tombol "X" di setiap item bahan (hapus item)
        document.addEventListener('click', function(e){
            if(e.target.classList.contains('remove-bahan')){
                e.target.closest('.bahan-item').remove(); // Hapus item bahan terkait dari DOM
            }
        });
    </script>
@endsection