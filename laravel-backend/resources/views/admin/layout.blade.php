<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Store Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
      * { font-family: 'Inter', sans-serif; }
      body { background: #f0f2f5; }
      .sidebar {
        width: 260px;
        min-height: 100vh;
        background: linear-gradient(180deg, #1e1e2f 0%, #2d2d44 100%);
        flex-shrink: 0;
      }
      .sidebar-brand {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
      }
      .sidebar-brand h5 {
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #fff;
      }
      .sidebar-brand small {
        color: rgba(255,255,255,0.4);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
      }
      .sidebar .nav-link {
        color: rgba(255,255,255,0.55) !important;
        padding: 10px 20px;
        margin: 2px 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
      }
      .sidebar .nav-link:hover {
        color: #fff !important;
        background: rgba(255,255,255,0.08);
      }
      .sidebar .nav-link.active {
        color: #fff !important;
        background: linear-gradient(135deg, #667eea, #764ba2);
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
      }
      .sidebar .nav-link i { font-size: 18px; vertical-align: middle; }
      .sidebar .nav-divider {
        border-top: 1px solid rgba(255,255,255,0.06);
        margin: 12px 20px;
      }
      .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
      }
      .topbar {
        background: #fff;
        padding: 16px 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .topbar h5 {
        font-weight: 700;
        color: #1e1e2f;
        margin: 0;
      }
      .topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
      }
      .topbar-right .badge-notif {
        position: relative;
        color: #6b7280;
        font-size: 20px;
      }
      .admin-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
      }
      .content-area {
        padding: 28px 32px;
        flex: 1;
      }
      .stat-card {
        border: none;
        border-radius: 16px;
        padding: 8px;
        transition: all 0.3s ease;
        cursor: default;
      }
      .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.1);
      }
      .stat-card .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
      }
      .stat-card .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: rgba(255,255,255,0.7);
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      .stat-card .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
      }
      .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow: hidden;
      }
      .card-modern .card-header {
        background: #fff;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 18px 24px;
        font-weight: 600;
        font-size: 15px;
        color: #1e1e2f;
      }
      .card-modern .card-body { padding: 20px 24px; }
      .table-modern { margin: 0; }
      .table-modern thead th {
        background: #f8f9fc;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: none;
      }
      .table-modern tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f5;
        font-size: 14px;
        color: #374151;
      }
      .table-modern tbody tr:last-child td { border-bottom: none; }
      .table-modern tbody tr:hover { background: #f8f9fc; }
      .badge-custom {
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 12px;
      }
      .alert-modern {
        border: none;
        border-radius: 12px;
        padding: 16px 20px;
      }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-brand">
                <h5 class="mb-0">Store Admin</h5>
                <small>Management Panel</small>
            </div>
            <nav class="p-2">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="bi bi-collection me-2"></i>Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="bi bi-box me-2"></i>Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="bi bi-truck me-2"></i>Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i>Users
                        </a>
                    </li>
                    <li class="nav-divider"></li>
                    <li class="nav-item">
                        <a href="{{ route('admin.logout') }}" class="nav-link text-danger"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-left me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main -->
        <div class="main-content">
            <div class="topbar">
                <h5>@yield('page-title', 'Dashboard')</h5>
                <div class="topbar-right">
                    <span class="badge-notif"><i class="bi bi-bell"></i></span>
                    <div class="admin-avatar">A</div>
                </div>
            </div>

            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-modern" style="background: #ecfdf5; color: #065f46;">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show alert-modern" style="background: #fef2f2; color: #991b1b;">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
