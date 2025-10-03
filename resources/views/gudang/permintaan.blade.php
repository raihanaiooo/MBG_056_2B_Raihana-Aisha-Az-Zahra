@extends('layouts.gudang')

@section('title', 'Permintaan Bahan')

@section('content')
<h1 class="text-2xl font-bold mb-6 text-center">Permintaan Bahan Menunggu</h1>

<div class="overflow-x-auto">
    <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">ID Permintaan</th>
                <th class="p-2 border">Pemohon</th>
                <th class="p-2 border">Tanggal Masak</th>
                <th class="p-2 border">Menu</th>
                <th class="p-2 border">Jumlah Porsi</th>
                <th class="p-2 border">Bahan Diminta</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaan as $p)
            <tr class="text-center hover:bg-gray-50 transition">
                <td class="p-2 border">{{ $p->id }}</td>
                <td class="p-2 border">{{ $p->pemohon_id }}</td>
                <td class="p-2 border">{{ $p->tgl_masak }}</td>
                <td class="p-2 border">{{ $p->menu_makan }}</td>
                <td class="p-2 border">{{ $p->jumlah_porsi }}</td>
                <td class="p-2 border">
                    @foreach($p->detail as $d)
                        {{ $d->bahan->nama }} ({{ $d->jumlah_diminta }})<br>
                    @endforeach
                </td>
                <td class="p-2 border flex justify-center gap-1">
                    <form action="{{ route('permintaan.acc', $p->id) }}" method="POST" class="acc-form">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                            ACC
                        </button>
                    </form>
                    <form action="{{ route('permintaan.tolak', $p->id) }}" method="POST" class="tolak-form">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                            Tolak
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
    const flashSuccess = "{{ session('success') ?? '' }}";
    const flashError   = "{{ session('error') ?? '' }}";

    if(flashSuccess) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: flashSuccess,
        });
    }

    if(flashError) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: flashError,
        });
    }

    // Konfirmasi permintaan disetujui
    document.querySelectorAll('.acc-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'question',
                title: 'Setujui permintaan?',
                text: 'Stok bahan akan berkurang sesuai jumlah yang diminta.',
                showCancelButton: true,
                confirmButtonText: 'Ya, ACC',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // Konfirmasi permintaan ditolak
    document.querySelectorAll('.tolak-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Tolak permintaan?',
                text: 'Data permintaan akan ditandai sebagai ditolak.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>
@endsection
