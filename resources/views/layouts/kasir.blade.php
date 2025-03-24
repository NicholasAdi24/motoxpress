<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/kasir.css') }}">
</head>
<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <h2>KASIR</h2>
            <ul>
                <li><a href="/kasir" class="active"><i class="fas fa-home"></i> Kasir</a></li>
                <li><a href="/riwayat-transaksi"><i class="fas fa-history"></i> Riwayat Transaksi</a></li>
                <li>
                    <form action="/logout" method="POST" class="logout-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
                
            </ul>
        </div>
        <!-- Overlay untuk menutup sidebar saat diklik -->
    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

        <!-- Konten utama -->
        <div class="flex-grow-1">
            @yield('content')
        </div>
    </div>

    <!-- Tambahkan JavaScript -->
    <script src="{{ asset('js/kasir.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
