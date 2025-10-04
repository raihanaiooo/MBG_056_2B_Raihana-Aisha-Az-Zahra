@extends('layouts.app')

@section('title', 'Gudang Dashboard')

@section('navbar')
    @include('layouts.navbar', [
        'routes' => [
            'index' => 'gudang.index',
            'permintaan' => 'gudang.permintaan',
        ],
        'labels' => [
            'index' => 'Bahan Baku',
            'permintaan' => 'Permintaan',
        ]
    ])
@endsection

@section('content')
<h1 class="text-2xl font-bold mb-6 text-center">Daftar Bahan Baku</h1>

<!-- Tombol Tambah Bahan Baku -->
<div class="mb-4 text-right">
    <a href="{{ route('gudang.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow transition duration-150">
        Tambah Bahan Baku
    </a>
</div>

<!-- Tabel Bahan Baku -->
<div class="overflow-x-auto">
    <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Nama</th>
                <th class="p-2 border">Kategori</th>
                <th class="p-2 border">Jumlah</th>
                <th class="p-2 border">Satuan</th>
                <th class="p-2 border">Tanggal Masuk</th>
                <th class="p-2 border">Tanggal Kadaluarsa</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bahan as $item)
                <tr class="text-center hover:bg-gray-50 transition">
                    <td class="p-2 border">{{ $item->id }}</td>
                    <td class="p-2 border">{{ $item->nama }}</td>
                    <td class="p-2 border">{{ $item->kategori }}</td>
                    <td class="p-2 border">{{ $item->jumlah }}</td>
                    <td class="p-2 border">{{ $item->satuan }}</td>
                    <td class="p-2 border">{{ $item->tanggal_masuk }}</td>
                    <td class="p-2 border">{{ $item->tanggal_kadaluarsa }}</td>
                    <td class="p-2 border font-semibold 
                        {{ $item->status == 'kadaluarsa' ? 'text-red-600' : '' }}
                        {{ $item->status == 'segera_kadaluarsa' ? 'text-yellow-600' : '' }}
                        {{ $item->status == 'habis' ? 'text-gray-500' : '' }}
                        {{ $item->status == 'tersedia' ? 'text-green-600' : '' }}">
                        {{ ucfirst($item->status) }}
                    </td>
                    <!-- Tombol Update Jumlah Stok -->
                    <td class="p-2 border flex justify-center gap-1">
                        <form action="{{ route('gudang.update', $item->id) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            @method('PUT')
                            <input type="number" name="jumlah" value="{{ $item->jumlah }}" class="w-16 px-1 py-1 border rounded">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow transition duration-150">
                                Update
                            </button>
                        </form>
                        <!-- Tombol Delete -->
                        <form action="{{ route('gudang.destroy', $item->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow transition duration-150"
                                data-nama="{{ $item->nama }}"
                                data-kategori="{{ $item->kategori }}"
                                data-jumlah="{{ $item->jumlah }} {{ $item->satuan }}"
                                data-expired="{{ $item->tanggal_kadaluarsa }}">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Konfirmasi Sebelum Delete
       document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                let btn = form.querySelector('button');
                let nama     = btn.dataset.nama;
                let kategori = btn.dataset.kategori;
                let jumlah   = btn.dataset.jumlah;
                let expired  = btn.dataset.expired;

                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus bahan ini?',
                    html: `
                        <div style="text-align:left">
                            <b>Nama:</b> ${nama} <br>
                            <b>Kategori:</b> ${kategori} <br>
                            <b>Jumlah:</b> ${jumlah} <br>
                            <b>Kadaluarsa:</b> ${expired}
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }); 
    });
</script>
@endsection
