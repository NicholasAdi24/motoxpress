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
                <li><a href="/kasir" ><i class="fas fa-home"></i> Kasir</a></li>
                <li><a href="/riwayat-transaksi" class="active"><i class="fas fa-history"></i> Riwayat Transaksi</a></li>
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
    <script src="{{ asset('js/riwayat.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
.toggle-month {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    font-weight: bold;
    font-size: 1.1rem;
    transition: background-color 0.2s;
}

.toggle-month:hover {
    background-color: #e2e6ea;
}

.month-icon {
    transition: transform 0.3s ease;
}

.rotate-down {
    transform: rotate(90deg);
}

.card-header {
    background: linear-gradient(45deg, #007bff, #0056b3);
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.toggle-month');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            const target = document.querySelector(this.dataset.target);
            const icon = this.querySelector('.month-icon');

            if (target.classList.contains('d-none')) {
                target.classList.remove('d-none');
                icon.classList.add('rotate-down');
                icon.textContent = '🔽';
            } else {
                target.classList.add('d-none');
                icon.classList.remove('rotate-down');
                icon.textContent = '▶️';
            }
        });
    });
});
</script>


</body>
</html>
