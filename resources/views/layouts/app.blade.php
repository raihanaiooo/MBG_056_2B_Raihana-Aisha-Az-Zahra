<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- Header -->
    <header class="bg-gray-900 text-white shadow-md rounded-xl mb-6">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center">
            
            <!-- Judul dan info user -->
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl font-bold">@yield('title', 'Dashboard')</h1>
                <p class="text-sm">
                    Halo, <span class="font-semibold">{{ session('user_name') }}</span> 
                    ({{ session('user_role') }})
                </p>
            </div>

            <!-- Navigasi halaman -->
            @yield('navbar')

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg shadow-sm transition duration-150">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Content -->
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-lg">
        @yield('content')
    </div>

    <!-- Scripts -->
    @yield('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const flashSuccess = "{{ session('success') ?? '' }}";
        const flashError   = "{{ session('error') ?? '' }}";
        // Buat Popup sweetAlert untuk login
        if (flashSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: flashSuccess
            });
        }
        
        if (flashError) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: flashError
            });
        }
    });
    </script>
</body>
</html>
