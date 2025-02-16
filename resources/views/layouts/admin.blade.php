<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
    <!-- Tombol Toggle Sidebar -->
    <button id="sidebarToggle" class="btn btn-light">
        <i class="bi bi-list" style="font-size: 24px; align-items: center; margin: 0 auto;"></i>
    </button>

    <h4 class="text-white text-center mt-3 sidebar-title">Administration</h4>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-cash-coin"></i> <span class="sidebar-text">Keuangan</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-calendar-check"></i> <span class="sidebar-text">Employee Absence</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.barang.index') }}" class="nav-link {{ request()->routeIs('admin.barang.index') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> <span class="sidebar-text">Stok/Inventaris</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-gear"></i> <span class="sidebar-text">Settings</span>
            </a>
        </li>
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
