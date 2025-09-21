<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Activity Log Visualizer')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans&display=swap" rel="stylesheet">

    <!-- Package CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/activitylog-visualizer/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/activitylog-visualizer/css/style.css') }}">
    <script src="{{ asset('vendor/activitylog-visualizer/js/scripts.js') }}"></script>

    @stack('styles')
</head>
<body>
    <div class="container-fluid bg-white border-bottom">
        <div class="container">
            <div class="d-flex align-items-center py-3">
                <div class="logo me-3"></div>
                <h1 class="h4 mb-0 fw-semibold">Activity Log Visualizer</h1>
            </div>
        </div>
    </div>

    <div class="container mt-4 fs-7">
        @yield('content')
    </div>

    <script src="{{ asset('vendor/activitylog-visualizer/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
