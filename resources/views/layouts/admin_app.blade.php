<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lifemedia Admin Panel</title>
    <!-- CSRF Token untuk AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
</head>
<body>
    <!-- Wrapper utama -->
    <div class="wrapper">
        <!-- Sidebar (include) -->
        @include('components.admin-sidebar')
        <!-- Content Area -->
        <div id="content" class="flex-grow-1 d-flex flex-column">
            <!-- Header (include) -->
            @include('components.admin-header')
            <!-- Main Content -->
            <main class="flex-fill">
                @yield('content')
            </main>
            <!-- Footer (include) -->
            @include('components.admin-footer')
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>

    @stack('js')
    
</body>
</html>
