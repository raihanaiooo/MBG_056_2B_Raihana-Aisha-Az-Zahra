@extends('layouts.app')

@section('title', 'Permintaan Bahan')

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
<h1 class="text-2xl font-bold mb-6 text-center">Permintaan Bahan Menunggu</h1>

<div class="overflow-x-auto">
    <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">ID Permintaan</th>
                <th class="p-2 border">Pemohon</th>
                <th class="p-2 border">Status</th>
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
                <td class="p-2 border">{{ $p->pemohon ?? '-' }}</td>
                <td class="p-2 border">
                    @if($p->status == 'menunggu')
                        <span class="px-2 py-1 bg-yellow-300 text-yellow-800 rounded">Menunggu</span>
                    @elseif($p->status == 'disetujui')
                        <span class="px-2 py-1 bg-green-300 text-green-800 rounded">Disetujui</span>
                    @elseif($p->status == 'ditolak')
                        <span class="px-2 py-1 bg-red-300 text-red-800 rounded">Ditolak</span>
                    @endif
                </td>
                <td class="p-2 border">{{ $p->tgl_masak }}</td>
                <td class="p-2 border">{{ $p->menu_makan }}</td>
                <td class="p-2 border">{{ $p->jumlah_porsi }}</td>
                <td class="p-2 border">{{ $p->bahan_diminta }}</td>
                <td class="p-2 border flex items-center justify-center gap-1">
                    @if($p->status == 'menunggu')
                        <!-- Form ACC -->
                        <form class="acc-form flex items-center" action="{{ route('permintaan.acc', $p->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                ACC
                            </button>
                        </form>
                        <!-- Form Tolak -->
                        <form class="tolak-form flex items-center" action="{{ route('permintaan.tolak', $p->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="alasan">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                Tolak
                            </button>
                        </form>
                    @else
                        <span class="text-gray-500">{{ $p->alasan_penolakan ?? '-' }}</span>
                    @endif
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

        // Konfirmasi ACC
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
                }).then(result => { if(result.isConfirmed) form.submit(); });
            });
        });

        // Konfirmasi Tolak + input alasan
        document.querySelectorAll('.tolak-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Tolak permintaan?',
                    input: 'textarea',
                    inputLabel: 'Alasan Penolakan',
                    inputPlaceholder: 'Tuliskan alasan penolakan di sini...',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal',
                    preConfirm: (alasan) => {
                        if(!alasan) Swal.showValidationMessage('Alasan penolakan wajib diisi!');
                        return alasan;
                    }
                }).then(result => {
                    if(result.isConfirmed) {
                        form.querySelector('input[name="alasan"]').value = result.value;
                        form.submit();
                    }
                });
            });
        });

    });
</script>
@endsection
