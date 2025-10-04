<nav class="flex flex-col md:flex-row items-center gap-3 mb-4 md:mb-0">
    <a href="{{ route($routes['index']) }}" 
       class="hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition duration-150">
       {{ $labels['index'] }}
    </a>
    <a href="{{ route($routes['permintaan']) }}" 
       class="hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition duration-150">
       {{ $labels['permintaan'] }}
    </a>
</nav>
