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
        cursor: pointer;
        transition: color 0.2s;
      }
      .topbar-right .badge-notif:hover {
        color: #667eea;
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
                      <span class="badge-notif dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="notif-bell" style="position: relative; display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 12px; transition: all 0.2s;">
                        <i class="bi bi-bell" style="font-size: 18px; transition: transform 0.2s;"></i>
                        <span id="notif-badge" class="position-absolute" style="display: none; top: 2px; right: 2px; min-width: 18px; height: 18px; background: linear-gradient(135deg, #f43f5e, #e11d48); border: 2px solid #fff; border-radius: 20px; font-size: 10px; font-weight: 700; color: #fff; display: none; align-items: center; justify-content: center; padding: 0 4px; box-shadow: 0 2px 6px rgba(228,29,72,0.3);">
                          0
                        </span>
                        <span class="notif-ring" style="position: absolute; inset: -4px; border-radius: 16px; border: 2px solid rgba(102,126,234,0.2); pointer-events: none;"></span>
                      </span>
                      <div class="dropdown-menu dropdown-menu-end" style="width: 380px; max-height: 480px; overflow-y: auto; border-radius: 16px; border: none; padding: 0; box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 4px 20px rgba(0,0,0,0.06); background: #ffffff;" id="notif-menu">
                        <!-- Header -->
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 18px 20px; position: sticky; top: 0; z-index: 2;">
                          <div class="d-flex align-items-center justify-content-between">
                            <div>
                              <h6 class="fw-bold mb-0" style="font-size: 15px; color: #fff; letter-spacing: 0.3px;">Notifications</h6>
                              <span class="notif-header-sub" style="font-size: 11px; color: rgba(255,255,255,0.6); font-weight: 400;">Stay updated with the latest activity</span>
                            </div>
                            <button class="btn btn-sm" id="mark-all-read" style="font-size: 11px; color: rgba(255,255,255,0.85); background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 5px 12px; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                              <i class="bi bi-check-all me-1"></i> Mark all read
                            </button>
                          </div>
                        </div>
                        <!-- List -->
                        <div id="notif-list">
                          <div class="text-center py-5 text-muted" style="font-size: 13px;">
                            <div style="width: 56px; height: 56px; border-radius: 16px; background: #f0f2f5; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 24px; color: #c4c8d0;">
                              <i class="bi bi-bell-slash"></i>
                            </div>
                            <div style="font-weight: 600; color: #6b7280; margin-bottom: 4px;">No notifications yet</div>
                            <div style="font-size: 12px; color: #9ca3af;">You're all caught up!</div>
                          </div>
                        </div>
                        <!-- Footer -->
                        <div class="text-center py-2" style="border-top: 1px solid #f0f2f5; font-size: 11px; color: #9ca3af; position: sticky; bottom: 0; background: #fff;">
                          <i class="bi bi-clock me-1"></i> Updates every 15s
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
          notifList.innerHTML = '<div class="text-center py-5 text-muted" style="font-size: 13px;">' +
            '<div style="width: 56px; height: 56px; border-radius: 16px; background: #f0f2f5; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 24px; color: #c4c8d0;">' +
              '<i class="bi bi-bell-slash"></i>' +
            '</div>' +
            '<div style="font-weight: 600; color: #6b7280; margin-bottom: 4px;">No notifications yet</div>' +
            '<div style="font-size: 12px; color: #9ca3af;">You\'re all caught up!</div>' +
          '</div>';
          return;
        }
        notifList.innerHTML = notifications.map(n => {
          const isUnread = !n.read_at;
          const time = new Date(n.created_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });

          // Determine link, icon, and badge label based on notification type
          let icon = 'bi-cart';
          let badgeLabel = 'Order';
          let iconGradient = isUnread ? 'linear-gradient(135deg, #667eea, #764ba2)' : '#f0f2f5';
          let iconColor = isUnread ? '#fff' : '#6b7280';

          if (n.type === 'low_stock') {
            icon = 'bi-exclamation-triangle';
            badgeLabel = 'Stock';
            iconGradient = isUnread ? 'linear-gradient(135deg, #f59e0b, #ef4444)' : '#fef3c7';
            iconColor = isUnread ? '#fff' : '#92400e';
          }

          let detailUrl = '#';
          if (n.order_id) detailUrl = '/admin/orders/' + n.order_id;
          else if (n.product_id) detailUrl = '/admin/products';

          const readClass = isUnread ? 'notif-unread' : '';

          return '<div class="notif-item ' + readClass + '" data-id="' + n.id + '" style="display: flex; align-items: start; gap: 12px; padding: 12px 16px; border-bottom: 1px solid #f3f4f6; cursor: default; transition: background 0.15s; position: relative;">' +
            '<a href="' + detailUrl + '" style="display: flex; align-items: start; gap: 12px; flex: 1; min-width: 0; text-decoration: none; color: inherit;">' +
              '<div style="position: relative; flex-shrink: 0;">' +
                '<div style="width: 40px; height: 40px; border-radius: 12px; background: ' + iconGradient + '; color: ' + iconColor + '; display: flex; align-items: center; justify-content: center; font-size: 16px;">' +
                  '<i class="bi ' + icon + '"></i>' +
                '</div>' +
                '<span style="position: absolute; bottom: -3px; right: -3px; font-size: 8px; font-weight: 700; padding: 1px 5px; border-radius: 6px; background: ' + (isUnread ? '#667eea' : '#e5e7eb') + '; color: ' + (isUnread ? '#fff' : '#9ca3af') + '; line-height: 1.4; border: 1.5px solid #fff;">' + badgeLabel + '</span>' +
              '</div>' +
              '<div style="flex: 1; min-width: 0;">' +
                '<div style="font-size: 13px; font-weight: ' + (isUnread ? '600' : '400') + '; color: ' + (isUnread ? '#111827' : '#4b5563') + '; line-height: 1.4; margin-bottom: 3px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">' + n.message + '</div>' +
                '<div style="display: flex; align-items: center; gap: 6px;">' +
                  '<i class="bi bi-clock" style="font-size: 10px; color: #9ca3af;"></i>' +
                  '<span style="font-size: 11px; color: #9ca3af;">' + time + '</span>' +
                '</div>' +
              '</div>' +
              (isUnread ? '<span style="flex-shrink: 0; width: 8px; height: 8px; border-radius: 50%; background: #667eea; margin-top: 4px; box-shadow: 0 0 6px rgba(102,126,234,0.4);"></span>' : '') +
            '</a>' +
            '<button class="delete-notif-btn" data-id="' + n.id + '" title="Delete notification" style="flex-shrink: 0; width: 28px; height: 28px; border: none; background: transparent; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; opacity: 0; transition: all 0.2s; color: #d1d5db; margin-top: 6px;">' +
              '<i class="bi bi-trash3" style="font-size: 12px;"></i>' +
            '</button>' +
          '</div>';
        }).join('');

        // Click handler to mark as read (only when clicking the link part)
        document.querySelectorAll('.notif-item a').forEach(el => {
          el.addEventListener('click', function(e) {
            const id = this.closest('.notif-item').dataset.id;
            if (id) {
              fetch('/admin/notifications/' + id + '/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } }).catch(() => {});
            }
          });
        });

        // Click handler to delete individual notification
        document.querySelectorAll('.delete-notif-btn').forEach(el => {
          el.addEventListener('click', async function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            if (!id) return;
            if (!confirm('Delete this notification?')) return;
            try {
              await fetch('/admin/notifications/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } });
              fetchNotifications();
              fetchUnreadCount();
            } catch (e) {
              console.error('Delete notification error:', e);
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

      // ── Polling-based notifications ──────────────────────────
      // Uses short polling instead of SSE (Server-Sent Events) because
      // PHP's built-in dev server is single-threaded and SSE blocks all
      // other requests. Polling every 15s is fast, safe, and reliable.
      let lastNotifId = {{ \App\Models\AdminNotification::max('id') ?? 0 }};

      async function pollNotifications() {
        try {
          const res = await fetch('{{ route("admin.notifications.index") }}?since_id=' + lastNotifId);
          const notifications = await res.json();
          if (notifications && notifications.length > 0) {
            // Update badge
            fetchUnreadCount();

            // Update last known ID
            for (const n of notifications) {
              if (n.id > lastNotifId) lastNotifId = n.id;
            }

            // If the dropdown is open, refresh the list
            if (notifDropdownEl && notifDropdownEl.classList.contains('show')) {
              fetchNotifications();
            }
          }
        } catch (e) {
          // Silently retry on next poll
        }
      }

      // Initial fetch for badge
      fetchUnreadCount();

      // Poll every 15 seconds for new notifications
      setInterval(pollNotifications, 15000);

      // Refresh notifications list when dropdown opens
      if (notifDropdownEl) {
        notifDropdownEl.addEventListener('show.bs.dropdown', function() {
          fetchNotifications();
        });
      }

      // Pulse animation and modern styles
      const styleSheet = document.createElement('style');
      styleSheet.textContent = `
        @keyframes notifPulse {
          0% { transform: scale(1); }
          50% { transform: scale(1.3); }
          100% { transform: scale(1); }
        }
        .badge-notif:hover {
          background: rgba(102,126,234,0.08) !important;
        }
        .badge-notif:hover i.bi-bell {
          transform: rotate(15deg);
        }
        .delete-notif-btn:hover {
          background: #fef2f2 !important;
          color: #ef4444 !important;
        }
        .notif-unread {
          background: rgba(102,126,234,0.04);
        }
        .notif-item {
          transition: background 0.15s ease;
        }
        .notif-item:hover {
          background: #f8f9fc !important;
        }
        .notif-item:hover .delete-notif-btn {
          opacity: 1 !important;
        }
        .notif-item:last-child {
          border-bottom: none !important;
        }
        .badge-notif.dropdown-toggle::after {
          display: none !important;
        }
        .notif-item a:hover + .delete-notif-btn {
          opacity: 1 !important;
        }

        #notif-menu::-webkit-scrollbar {
          width: 4px;
        }
        #notif-menu::-webkit-scrollbar-track {
          background: transparent;
        }
        #notif-menu::-webkit-scrollbar-thumb {
          background: #d1d5db;
          border-radius: 10px;
        }
        #notif-menu::-webkit-scrollbar-thumb:hover {
          background: #9ca3af;
        }
        .notif-header-sub {
          opacity: 0.8;
        }
      `;
      document.head.appendChild(styleSheet);
    </script>
</body>
</html>
