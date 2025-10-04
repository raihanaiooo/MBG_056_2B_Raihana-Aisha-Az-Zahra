<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h1 class="text-3xl font-bold text-center mb-6">Login</h1>

        <!-- Form -->
        <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-4" novalidate>
            @csrf
            <div>
                <label class="block text-gray-700 mb-1" for="username">Username</label>
                <input type="text" name="username" id="username"
                       value="{{ old('username') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 mb-1" for="password">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                Login
            </button>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const flashError = "{{ session('error') ?? '' }}";

        // Server-side error
        if(flashError) {
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: flashError
            });
        }

        const form = document.getElementById('loginForm');
        const username = document.getElementById('username');
        const password = document.getElementById('password');

        form.addEventListener('submit', function(e) {
            let errors = [];

            const usernameValue = username.value.trim();
            const passwordValue = password.value.trim();

            // Validasi client-side
            if (!usernameValue) {
                errors.push("Username harus diisi.");
            } else if (!/^[@.a-zA-Z0-9_ ]+$/.test(usernameValue)) { // pake regex
                errors.push("Username hanya boleh mengandung huruf, angka, underscore, dan spasi.");
            }

            if (!passwordValue) {
                errors.push("Password harus diisi.");
            } else if (passwordValue.length < 6) {
                errors.push("Password minimal 6 karakter.");
            }

            if (errors.length > 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Form Tidak Valid',
                    html: errors.join('<br>'),
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    </script>
</body>
</html>
