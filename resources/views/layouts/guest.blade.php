<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AgriFlow') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h1 class="text-white fw-bold">🌾 AgriFlow</h1>
                    <p class="text-white-50">Sistem Monitoring Pertanian</p>
                </div>
                <div class="card">
                    <div class="card-body p-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>