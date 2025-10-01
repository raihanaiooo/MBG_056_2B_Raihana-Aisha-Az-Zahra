<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Bahan Baku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg">
    <h1 class="text-2xl font-bold mb-6 text-center">Tambah Bahan Baku</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('gudang.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-gray-700 mb-1">Nama</label>
            <input type="text" name="nama" required class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Kategori</label>
            <input type="text" name="kategori" required class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Jumlah</label>
            <input type="number" name="jumlah" min="0" required class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Satuan</label>
            <input type="text" name="satuan" required class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" required class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Tanggal Kadaluarsa</label>
            <input type="date" name="tanggal_kadaluarsa" required class="w-full px-4 py-2 border rounded-lg">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
            Tambah
        </button>
    </form>
</div>

</body>
</html>
