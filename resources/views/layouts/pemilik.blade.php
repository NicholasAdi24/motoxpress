<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pemilik.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome 5 CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body class="bg-light">

<div class="d-flex">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
    <!-- Tombol Toggle Sidebar -->
    <button id="sidebarToggle" class="btn btn-light">
        <i class="bi bi-list" style="font-size: 24px; align-items: center; margin: 0 auto;"></i>
    </button>

    <h4 class="text-white text-center mt-3 sidebar-title">MotoXpress-Owner</h4>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('pemilik.dashboard') }}" class="nav-link {{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">
                <i  class="fas fa-home me-2"></i> <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
        <a href="{{ route('pemilik.pendapatan') }}" class="nav-link {{ request()->routeIs('pemilik.pendapatan') ? 'active' : '' }}">
                <i  class="fas fa-money-bill-wave me-2"></i> <span class="sidebar-text">Pendapatan</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('pemilik.pengeluaran') }}" class="nav-link {{ request()->routeIs('pemilik.pengeluaran') ? 'active' : '' }}">
                <i class="fas fa-wallet me-2"></i> <span class="sidebar-text">Pengeluaran</span>
            </a>

            <li class="nav-item">
            <a href="{{ route('pemilik.labaRugi') }}" class="nav-link {{ request()->routeIs('pemilik.labaRugi') ? 'active' : '' }}">
                <i class="fas fa-chart-line me-2"></i> <span class="sidebar-text">Laporan Laba Rugi</span>
            </a>
        <li class="nav-item">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="nav-link text-danger border-0 bg-transparent">
            <i class="bi bi-box-arrow-left"></i> <span class="sidebar-text">Quit</span>
        </button>
        </form>
</li>

    </ul>
</div>


    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>
</div>
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
<style>
    body {
        font-family: Arial, sans-serif;
    }
    /* Sidebar */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: #48A5FF;
        padding: 20px;
        position: fixed;
        color: white;
        transition: width 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    .sidebar.collapsed {
        width: 70px;
    }
    .sidebar-title {
        transition: opacity 0.3s ease;
    }
    .sidebar.collapsed .sidebar-title {
        opacity: 0;
    }
    .sidebar .nav-link {
        color: white;
        padding: 10px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        transition: padding 0.3s ease;
    }
    .sidebar .nav-link i {
        font-size: 20px;
    }
    .sidebar.collapsed .nav-link {
        justify-content: center;
        padding: 10px;
    }
    .sidebar.collapsed .sidebar-text {
        display: none;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
        background: white;
        color: #48A5FF;
    }

    /* Tombol Toggle Sidebar */
    #sidebarToggle {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border: none;
    width: 40px; /* Sesuaikan ukuran lingkaran */
    height: 40px;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    padding: 0;
}

#sidebarToggle i {
    font-size: 24px;
}

    .sidebar.collapsed #sidebarToggle {
        display: block;
        margin: 10px auto;
    }

    /* Content */
    .content {
        margin-left: 270px;
        padding: 20px;
        width: 100%;
        transition: margin-left 0.3s ease;
    }
    .sidebar.collapsed + .content {
        margin-left: 90px;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .sidebar {
            width: 250px;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .content {
            margin-left: 270px;
        }
        .sidebar.collapsed + .content {
            margin-left: 90px;
        }
    }
</style>

<script>
    document.getElementById("sidebarToggle").addEventListener("click", function() {
        document.getElementById("sidebar").classList.toggle("collapsed");
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
