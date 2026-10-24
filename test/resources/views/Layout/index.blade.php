<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Góc Nhìn - Blog tin tức')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f7f7f7; color: #333; font-family: Arial, sans-serif; }
        .brand { font-size: 34px; font-weight: bold; color: #172b4d; letter-spacing: 2px; }
        .navbar { background: #172b4d; }
        .navbar a { color: white !important; }
        .content-box { background: white; padding: 25px; margin-bottom: 25px; border-radius: 5px; }
        .post-image { width: 100%; height: 210px; object-fit: cover; }
        .post-title { color: #172b4d; font-weight: bold; }
        .category { color: #e76f51; font-size: 13px; font-weight: bold; text-transform: uppercase; }
        footer { background: #172b4d; color: white; padding: 25px; margin-top: 30px; }
    </style>
    @yield('styles')
</head>
<body>
    @include('partials.navbar')

    <main class="container mt-4">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
