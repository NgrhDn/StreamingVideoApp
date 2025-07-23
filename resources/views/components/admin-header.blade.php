<!-- resources/views/components/admin-header.blade.php -->
<header id="header" class="d-flex justify-content-between align-items-center px-3 py-2">
    <div class="d-flex align-items-center">
        <!-- Tombol toggle sidebar -->
        <button id="sidebarToggle" class="btn btn-link text-white me-3"><i class="fas fa-bars"></i></button>
        <h4 class="mb-0 text-white">
            @yield('page_title', 'Dashboard') {{-- fallback title jika tidak ada section --}}
        </h4>
    </div>
</header>






