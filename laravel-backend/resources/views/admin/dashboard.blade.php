@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: #1e1e2f;">Welcome back, Admin</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; border-radius: 10px; padding: 10px 20px; font-weight: 600;">
                <i class="bi bi-plus-lg me-1"></i> New Product
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255,255,255,0.15);">
                        <i class="bi bi-box"></i>
                    </div>
                    <div>
                        <div class="stat-label">Products</div>
                        <div class="stat-value">{{ $total_products ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255,255,255,0.15);">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div>
                        <div class="stat-label">Categories</div>
                        <div class="stat-value">{{ $total_categories ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255,255,255,0.15);">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <div class="stat-label">Orders</div>
                        <div class="stat-value">{{ $total_orders ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(0,0,0,0.1);">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="stat-label" style="color: rgba(0,0,0,0.5);">Users</div>
                        <div class="stat-value" style="color: #064e3b;">{{ $total_users ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history me-2 text-primary"></i>Recent Orders</span>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_orders ?? [] as $order)
                                <tr>
                                    <td><span class="fw-semibold">{{ $order->order_number }}</span></td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                                'completed' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                                'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                                'processing' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                            ];
                                            $color = $statusColors[$order->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                        @endphp
                                        <span class="badge badge-custom" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">${{ number_format($order->total, 2) }}</td>
                                    <td style="color: #6b7280;">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4" style="color: #9ca3af;">No orders yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-modern">
                <div class="card-header">
                    <i class="bi bi-currency-dollar me-2 text-success"></i>Revenue Overview
                </div>
                <div class="card-body">
                    <div class="text-center py-3">
                        <div style="font-size: 14px; color: #6b7280; font-weight: 500;">Total Revenue</div>
                        <div style="font-size: 36px; font-weight: 800; color: #1e1e2f; line-height: 1.2;">
                            ${{ number_format($total_revenue ?? 0, 2) }}
                        </div>
                        <div class="mt-3 d-flex justify-content-center gap-3">
                            <div class="text-center px-3 py-2 rounded-3" style="background: #f0f2f5;">
                                <div style="font-size: 11px; color: #6b7280; font-weight: 500;">Orders</div>
                                <div style="font-size: 20px; font-weight: 700; color: #1e1e2f;">{{ $total_orders ?? 0 }}</div>
                            </div>
                            <div class="text-center px-3 py-2 rounded-3" style="background: #f0f2f5;">
                                <div style="font-size: 11px; color: #6b7280; font-weight: 500;">Customers</div>
                                <div style="font-size: 20px; font-weight: 700; color: #1e1e2f;">{{ $total_users ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-modern mt-4">
                <div class="card-header">
                    <i class="bi bi-lightning me-2 text-warning"></i>Quick Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-light text-start d-flex align-items-center gap-2" style="border-radius: 10px; padding: 12px 16px; border: 1px solid #e5e7eb;">
                            <i class="bi bi-plus-circle text-primary"></i> Add Product
                        </a>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-light text-start d-flex align-items-center gap-2" style="border-radius: 10px; padding: 12px 16px; border: 1px solid #e5e7eb;">
                            <i class="bi bi-plus-circle text-success"></i> Add Category
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light text-start d-flex align-items-center gap-2" style="border-radius: 10px; padding: 12px 16px; border: 1px solid #e5e7eb;">
                            <i class="bi bi-eye text-info"></i> View Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
