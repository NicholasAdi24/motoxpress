<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .logo {
            max-width: 1000px; /* Sesuaikan ukuran logo */
            height: auto;
        }
        .btn-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Logo Bengkel di Tengah -->
    <div class="text-center">
        <img src="{{ asset('images/logo-motoxpress.png') }}" alt="Logo Bengkel" class="logo">
    </div>

    <!-- Tombol Login dan Register -->
    <div class="btn-container text-center">
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
    </div>

</body>
</html>
