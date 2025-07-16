<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="container mt-4">
        <div class="text-center mb-4">
            <img src="{{ asset('image/love.png  ') }}" alt="Report Header" class="img-fluid" style="max-height: 150px;">
            <h2 class="mt-3">Daily Expense Reporting System</h2>
        </div>
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
