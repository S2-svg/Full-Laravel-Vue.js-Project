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
                            <i class="bi bi-box-arrow-left me-2 text-danger"></i> <span class="text-danger">Logout</span>
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
                    <div class="dropdown" id="notif-dropdown">
                      <span class="badge-notif dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="notif-bell">
                        <i class="bi bi-bell"></i>
                        <span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="display: none; font-size: 10px; background: #f43f5e; border: 2px solid #fff; min-width: 18px; height: 18px; align-items: center; justify-content: center; padding: 0 4px;">
                          0
                        </span>
                      </span>
                      <div class="dropdown-menu dropdown-menu-end shadow-sm" style="width: 340px; max-height: 420px; overflow-y: auto; border-radius: 14px; border: none; padding: 0;" id="notif-menu">
                        <div class="d-flex align-items-center justify-content-between px-3 py-2" style="border-bottom: 1px solid #f0f2f5;">
                          <h6 class="fw-bold mb-0" style="font-size: 14px;">Notifications</h6>
                          <button class="btn btn-sm btn-link text-decoration-none p-0" id="mark-all-read" style="font-size: 12px; color: var(--color-primary);">Mark all read</button>
                        </div>
                        <div id="notif-list">
                          <div class="text-center py-4 text-muted small">
                            <i class="bi bi-bell-slash d-block mb-2 fs-4"></i>
                            No notifications
                          </div>
                        </div>
                      </div>
                    </div>
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
    @yield('scripts')
    <script>
      const notifBadge = document.getElementById('notif-badge');
      const notifList = document.getElementById('notif-list');
      const markAllBtn = document.getElementById('mark-all-read');
      const notifDropdownEl = document.getElementById('notif-dropdown');
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

      async function fetchUnreadCount() {
        try {
          const res = await fetch('{{ route("admin.notifications.unread-count") }}');
          const data = await res.json();
          updateBadge(data.count);
        } catch (e) {
          console.error('Notif fetch error:', e);
        }
      }

      function updateBadge(count) {
        if (count > 0) {
          notifBadge.style.display = 'flex';
          notifBadge.textContent = count > 99 ? '99+' : count;
          // Pulse animation
          notifBadge.style.animation = 'none';
          notifBadge.offsetHeight;
          notifBadge.style.animation = 'notifPulse 0.4s ease';
        } else {
          notifBadge.style.display = 'none';
        }
      }

      async function fetchNotifications() {
        try {
          const res = await fetch('{{ route("admin.notifications.index") }}');
          const notifications = await res.json();
          renderNotifications(notifications);
        } catch (e) {
          console.error('Notif list fetch error:', e);
        }
      }

      function renderNotifications(notifications) {
        if (!notifications || notifications.length === 0) {
          notifList.innerHTML = '<div class="text-center py-4 text-muted small"><i class="bi bi-bell-slash d-block mb-2 fs-4"></i>No notifications</div>';
          return;
        }
        notifList.innerHTML = notifications.map(n => {
          const isUnread = !n.read_at;
          const time = new Date(n.created_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });

          // Determine link and icon based on notification type
          let icon = 'bi-cart';
          let iconBg = isUnread ? 'linear-gradient(135deg, #667eea, #764ba2)' : '#f0f2f5';
          let iconColor = isUnread ? '#fff' : '#6b7280';

          if (n.type === 'low_stock') {
            icon = 'bi-exclamation-triangle';
            iconBg = isUnread ? 'linear-gradient(135deg, #f59e0b, #ef4444)' : '#fef3c7';
            iconColor = isUnread ? '#fff' : '#92400e';
          }

          let detailUrl = '#';
          if (n.order_id) detailUrl = '/admin/orders/' + n.order_id;
          else if (n.product_id) detailUrl = '/admin/products';

          return '<div class="notif-item d-flex align-items-start gap-3 px-3 py-3 position-relative ' + (isUnread ? 'notif-unread' : '') + '" data-id="' + n.id + '" style="border-bottom: 1px solid #f0f2f5;">' +
            '<a href="' + detailUrl + '" class="d-flex align-items-start gap-3 text-decoration-none text-reset flex-grow-1 min-w-0" style="color: inherit;">' +
              '<div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: ' + iconBg + '; color: ' + iconColor + '; font-size: 16px;">' +
                '<i class="bi ' + icon + '"></i>' +
              '</div>' +
              '<div class="flex-grow-1 min-w-0" style="max-width: 210px;">' +
                '<div class="small fw-semibold mb-0" style="font-size: 13px; white-space: normal; line-height: 1.4;">' + n.message + '</div>' +
                '<div class="text-muted" style="font-size: 11px; margin-top: 2px;">' + time + '</div>' +
              '</div>' +
              (isUnread ? '<span class="rounded-circle flex-shrink-0" style="width: 8px; height: 8px; background: #667eea; margin-top: 6px;"></span>' : '') +
            '</a>' +
            '<button class="btn notif-delete-btn p-0 border-0 d-flex align-items-center justify-content-center flex-shrink-0" data-id="' + n.id + '" title="Delete notification" style="width: 24px; height: 24px; border-radius: 6px; background: transparent; color: #9ca3af; cursor: pointer; transition: all 0.15s;">' +
              '<i class="bi bi-x" style="font-size: 14px; font-weight: 700;"></i>' +
            '</button>' +
          '</div>';
        }).join('');

        // Click handler to mark as read (only on the link portion)
        document.querySelectorAll('.notif-item a').forEach(el => {
          el.addEventListener('click', function(e) {
            const id = this.closest('.notif-item').dataset.id;
            if (id) {
              fetch('/admin/notifications/' + id + '/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } }).catch(() => {});
            }
          });
        });

        // Delete notification handler
        document.querySelectorAll('.notif-delete-btn').forEach(el => {
          el.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            const item = this.closest('.notif-item');
            if (id && item) {
              // Brief visual fade before removing
              item.style.transition = 'all 0.2s ease';
              item.style.opacity = '0';
              item.style.transform = 'translateX(-10px)';
              fetch('/admin/notifications/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } })
                .then(() => {
                  fetchNotifications();
                  fetchUnreadCount();
                })
                .catch(e => {
                  // Revert on error
                  item.style.opacity = '1';
                  item.style.transform = 'none';
                  console.error('Delete error:', e);
                });
            }
          });
        });
      }

      // Mark all as read
      if (markAllBtn) {
        markAllBtn.addEventListener('click', async function() {
          try {
            await fetch('{{ route("admin.notifications.read-all") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } });
            fetchNotifications();
            fetchUnreadCount();
          } catch (e) {
            console.error('Mark all read error:', e);
          }
        });
      }

      // ── Real-time EventSource (SSE) ──────────────────────────
      // Connect to the SSE stream to push new notifications the
      // instant they are created — no polling delay needed.
      let notifEventSource = null;
      // Seed with the current max ID so we don't replay existing notifications
      let lastNotifId = {{ \App\Models\AdminNotification::max('id') ?? 0 }};

      function connectNotifSSE() {
        const url = '{{ route("admin.notifications.stream") }}?last_id=' + lastNotifId;
        notifEventSource = new EventSource(url);

        notifEventSource.addEventListener('notification', function (e) {
          const notif = JSON.parse(e.data);
          lastNotifId = Math.max(lastNotifId, notif.id);

          // Pulse the bell badge
          fetchUnreadCount();

          // If the dropdown is open, refresh the list
          if (notifDropdownEl && notifDropdownEl.classList.contains('show')) {
            fetchNotifications();
          }
        });

        notifEventSource.onerror = function () {
          // Close & reconnect manually after a delay to avoid rapid retry storms
          notifEventSource.close();
          setTimeout(connectNotifSSE, 3000);
        };
      }

      // Initial fetch for badge + connect SSE
      fetchUnreadCount();
      connectNotifSSE();

      // Refresh notifications list when dropdown opens
      if (notifDropdownEl) {
        notifDropdownEl.addEventListener('show.bs.dropdown', function() {
          fetchNotifications();
        });
      }

      // Pulse animation
      const styleSheet = document.createElement('style');
      styleSheet.textContent = `
        @keyframes notifPulse {
          0% { transform: scale(1); }
          50% { transform: scale(1.3); }
          100% { transform: scale(1); }
        }
        .notif-unread {
          background: rgba(102,126,234,0.04);
        }
        .notif-item:hover {
          background: #f8f9fc;
        }
        .notif-item:last-child {
          border-bottom: none !important;
        }
        .notif-item:hover .notif-delete-btn {
          opacity: 1 !important;
          visibility: visible !important;
        }
        .notif-delete-btn {
          opacity: 0;
          visibility: hidden;
          transition: all 0.15s;
        }
        .notif-delete-btn:hover {
          background: #fee2e2 !important;
          color: #ef4444 !important;
        }
        .badge-notif.dropdown-toggle::after {
          display: none !important;
        }
      `;
      document.head.appendChild(styleSheet);
    </script>
</body>
</html>
