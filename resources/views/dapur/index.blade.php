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
<h1 class="text-2xl font-bold mb-6 text-center">Status Permintaan Bahan</h1>

<div class="overflow-x-auto">
    <table class="w-full border border-gray-300 rounded-lg">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Tanggal Masak</th>
                <th class="p-2 border">Menu</th>
                <th class="p-2 border">Jumlah Porsi</th>
                <th class="p-2 border">Bahan Diminta</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Alasan Penolakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaan as $p)
            <tr class="text-center hover:bg-gray-50">
                <td class="p-2 border">{{ $p->id }}</td>
                <td class="p-2 border">{{ $p->tgl_masak }}</td>
                <td class="p-2 border">{{ $p->menu_makan }}</td>
                <td class="p-2 border">{{ $p->jumlah_porsi }}</td>
                <td class="p-2 border">
                    @foreach($p->detail as $d)
                        {{ $d->bahan->nama ?? $d->nama_bahan }} ({{ $d->jumlah_diminta }})<br>
                    @endforeach
                </td>
                <td class="p-2 border">
                    @if($p->status == 'menunggu')
                        <span class="px-2 py-1 bg-yellow-300 text-yellow-800 rounded">Menunggu</span>
                    @elseif($p->status == 'disetujui')
                        <span class="px-2 py-1 bg-green-300 text-green-800 rounded">Disetujui</span>
                    @elseif($p->status == 'ditolak')
                        <span class="px-2 py-1 bg-red-300 text-red-800 rounded">Ditolak</span>
                    @else
                        <span class="px-2 py-1 bg-gray-300 text-gray-800 rounded">{{ ucfirst($p->status) }}</span>
                    @endif
                </td>
                <td class="p-2 border">{{ $p->alasan_penolakan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection