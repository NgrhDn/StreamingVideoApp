@stack('scripts')

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Lifemedia VOD')</title>

    {{-- Google Fonts - Pilih font yang modern dan mudah dibaca --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    

    {{-- Custom CSS Anda --}}
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa; /* Warna latar belakang yang lebih soft */
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Memastikan body mengisi seluruh tinggi viewport */
        }
        .text-primary {
            color: #007bff !important; /* Contoh warna primary, sesuaikan dengan branding Anda */
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .card {
            border-radius: 0.75rem; /* Sudut membulat pada kartu */
            overflow: hidden; /* Penting untuk gambar/video di dalam kartu */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sedikit bayangan pada kartu */
            transition: transform 0.2s ease-in-out; /* Animasi hover */
        }
        .card:hover {
            transform: translateY(-5px); /* Efek naik saat di-hover */
        }
        .card-title {
            font-weight: 600; /* Judul video lebih tebal */
        }
        .pagination .page-item .page-link {
            border-radius: 0.5rem; /* Sudut membulat pada pagination */
            margin: 0 3px; /* Jarak antar tombol pagination */
        }
        /* Penyesuaian warna pagination */
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    @include('components.public-navbar')
    <main class="flex-fill">
        @yield('content')
    </main>
    {{-- Tambahkan footer di sini jika diperlukan --}}
    <footer class="custom-navbar text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Lifemedia. All rights reserved.</p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>