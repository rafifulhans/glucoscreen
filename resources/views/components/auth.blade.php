<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/glucoscreen.css') }}">
</head>

<body>
    <div class="gs-bg">
        <span class="gs-orb-primary"></span>
        <span class="gs-orb-secondary"></span>

        <div class="gs-auth-wrap">
            <div class="gs-glass gs-auth-card">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    @include('sweetalert::alert')
</body>
</html>
