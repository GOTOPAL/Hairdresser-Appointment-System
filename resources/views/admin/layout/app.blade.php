<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Özel CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="admin-container d-flex" style="min-height: 100vh; background-color: #f9f9f9;">

    {{-- Sidebar --}}
    @include('admin.layout.sidebar')

    {{-- Main Content --}}
    <div class="admin-main flex-grow-1 p-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h1 class="h4 text-primary mb-0">Admin Paneli</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> Çıkış Yap
                </button>
            </form>
        </div>

        {{-- Page Content --}}
        <main class="admin-content">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Özel JS -->
<script src="{{ asset('js/admin.js') }}" defer></script>
</body>
</html>
