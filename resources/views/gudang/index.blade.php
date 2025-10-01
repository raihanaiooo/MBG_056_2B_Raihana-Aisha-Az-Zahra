<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Bahan Baku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

<div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-lg">
    <h1 class="text-2xl font-bold mb-6 text-center">Daftar Bahan Baku</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tombol Tambah Bahan Baku -->
    <div class="mb-4">
        <a href="{{ route('gudang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Tambah Bahan Baku
        </a>
    </div>

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
                <tr class="text-center">
                    <td class="p-2 border">{{ $item->id }}</td>
                    <td class="p-2 border">{{ $item->nama }}</td>
                    <td class="p-2 border">{{ $item->kategori }}</td>
                    <td class="p-2 border">{{ $item->jumlah }}</td>
                    <td class="p-2 border">{{ $item->satuan }}</td>
                    <td class="p-2 border">{{ $item->tanggal_masuk }}</td>
                    <td class="p-2 border">{{ $item->tanggal_kadaluarsa }}</td>
                    <td class="p-2 border font-semibold 
                        {{ $item->status == 'kadaluarsa' ? 'text-red-600' : '' }}
                        {{ $item->status == 'segera kadaluarsa' ? 'text-yellow-600' : '' }}
                        {{ $item->status == 'habis' ? 'text-gray-500' : '' }}
                        {{ $item->status == 'tersedia' ? 'text-green-600' : '' }}">
                        {{ ucfirst($item->status) }}
                    </td>
                    <td class="p-2 border">
                        <form action="{{ route('gudang.update', $item->id) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            @method('PUT')
                            <input type="number" name="jumlah" value="{{ $item->jumlah }}" class="w-16 px-1 py-1 border rounded">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded ml-1">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form action="{{ route('logout') }}" method="POST" class="inline mt-4">
        @csrf
        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            Logout
        </button>
    </form>
</div>

</body>
</html>
