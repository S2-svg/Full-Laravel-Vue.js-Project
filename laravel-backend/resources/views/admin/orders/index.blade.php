@extends('admin.layout')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(102,126,234,0.1); color: #667eea; font-size: 22px;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Total Orders</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $totalOrders }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(245,158,11,0.1); color: #f59e0b; font-size: 22px;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Pending</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $pendingCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16,185,129,0.1); color: #10b981; font-size: 22px;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Completed</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $completedCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16,185,129,0.1); color: #10b981; font-size: 22px;">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Revenue</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">${{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Table Card --}}
    <div class="card card-modern">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="d-flex align-items-center gap-2">
                <i class="bi bi-list-columns text-primary"></i>
                All Orders
                <span class="badge rounded-pill" style="background: #eef2ff; color: #667eea; font-size: 11px; font-weight: 600;">{{ $totalOrders }} total</span>
            </span>
            <div class="d-flex gap-2">
                {{-- Status filter --}}
                <select id="status-filter" class="form-select form-select-sm" style="width: auto; border-radius: 8px; font-size: 13px; min-width: 130px;">
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                {{-- Search --}}
                <div class="input-group input-group-sm" style="width: 200px;">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;">
                        <i class="bi bi-search" style="font-size: 12px; color: #9ca3af;"></i>
                    </span>
                    <input type="text" id="order-search" class="form-control border-start-0 ps-0" placeholder="Search orders..." style="border-radius: 0 8px 8px 0; font-size: 13px;">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern" id="orders-table">
                    <thead>
                        <tr>
                            <th style="width: 140px;">Order #</th>
                            <th>Customer</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 110px;">Total</th>
                            <th style="width: 120px;">Date</th>
                            <th style="width: 210px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $statusColors = [
                                    'pending'    => ['bg' => '#fef3c7', 'text' => '#92400e', 'dot' => '#f59e0b'],
                                    'processing' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'dot' => '#3b82f6'],
                                    'completed'  => ['bg' => '#d1fae5', 'text' => '#065f46', 'dot' => '#10b981'],
                                    'cancelled'  => ['bg' => '#fee2e2', 'text' => '#991b1b', 'dot' => '#ef4444'],
                                ];
                                $sc = $statusColors[$order->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'dot' => '#6b7280'];
                            @endphp
                            <tr class="order-row" data-status="{{ $order->status }}" data-search="{{ $order->order_number }} {{ $order->user->name ?? '' }} {{ $order->user->email ?? '' }}">
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="fw-semibold text-decoration-none" style="color: #667eea;">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; background: #eef2ff; color: #667eea; font-size: 13px; font-weight: 600;">
                                            {{ strtoupper(substr($order->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size: 13px;">{{ $order->user->name ?? 'N/A' }}</div>
                                            @if($order->user?->email)
                                                <div class="text-muted" style="font-size: 11px;">{{ $order->user->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-custom d-inline-flex align-items-center gap-1" style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }};">
                                        <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background: {{ $sc['dot'] }};"></span>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="fw-semibold" style="color: #1e1e2f;">${{ number_format($order->total, 2) }}</td>
                                <td style="color: #6b7280; font-size: 13px;">{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary border-0" title="View Details" style="border-radius: 8px; padding: 4px 10px;">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- Inline status update --}}
                                        <form class="status-update-form d-inline" method="POST" action="{{ route('admin.orders.update', $order) }}">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm status-select" style="border-radius: 8px; font-size: 12px; padding: 4px 24px 4px 10px; min-width: 110px; background-color: {{ $sc['bg'] }}; color: {{ $sc['text'] }}; border-color: transparent; font-weight: 500; cursor: pointer;">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>🔄 Processing</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                            </select>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-5">
                                        <div style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <h6 style="color: #6b7280; font-weight: 600;">No orders yet</h6>
                                        <p class="text-muted mb-0" style="font-size: 13px;">Orders will appear here once customers start shopping.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->count() > 0)
            <div class="card-footer bg-white border-top-0 px-3 py-2 d-flex justify-content-between align-items-center" style="border-radius: 0 0 16px 16px;">
                <small class="text-muted">Showing {{ $orders->count() }} order{{ $orders->count() !== 1 ? 's' : '' }}</small>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const statusFilter = document.getElementById('status-filter');
    const searchInput = document.getElementById('order-search');
    const rows = document.querySelectorAll('.order-row');
    const forms = document.querySelectorAll('.status-update-form');
    const statusSelects = document.querySelectorAll('.status-select');

    // ── Status Filter ──
    statusFilter.addEventListener('change', filterTable);
    searchInput.addEventListener('input', filterTable);

    function filterTable() {
        const statusVal = statusFilter.value.toLowerCase();
        const searchVal = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(row => {
            const status = row.dataset.status;
            const searchText = row.dataset.search.toLowerCase();
            const matchesStatus = !statusVal || status === statusVal;
            const matchesSearch = !searchVal || searchText.includes(searchVal);
            row.style.display = matchesStatus && matchesSearch ? '' : 'none';
            if (matchesStatus && matchesSearch) visibleCount++;
        });

        // Update visible count in footer
        const footer = document.querySelector('.card-footer small');
        if (footer) {
            footer.textContent = `Showing ${visibleCount} order${visibleCount !== 1 ? 's' : ''}`;
        }
    }

    // ── AJAX Status Update ──
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const form = this.closest('.status-update-form');
            const formData = new FormData(form);

            // Optimistic UI update
            const row = this.closest('tr');
            const badge = row?.querySelector('.badge-custom');
            const oldHtml = badge ? badge.innerHTML : '';

            // Revert after 5s if no response
            const timeout = setTimeout(() => {
                if (badge) badge.innerHTML = oldHtml;
            }, 8000);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                clearTimeout(timeout);
                // Reload to get updated status colors from server
                window.location.reload();
            })
            .catch(err => {
                clearTimeout(timeout);
                console.error('Status update failed:', err);
                if (badge) badge.innerHTML = oldHtml;
            });
        });
    });

});
</script>
@endsection
