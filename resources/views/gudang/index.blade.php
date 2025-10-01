<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>ini views gudang</h1>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit">
                Logout
            </button>
        </form>
</body>
</html>