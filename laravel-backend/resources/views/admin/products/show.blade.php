@extends('admin.layout')

@section('title', 'Product Details')
@section('page-title', $product->name)

@section('content')
    <div class="row g-4 mb-4">
        {{-- Product Image & Info --}}
        <div class="col-md-4">
            <div class="card card-modern">
                <div class="card-body p-4 text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="rounded-4 mb-3" style="width: 100%; max-height: 260px; object-fit: cover; border: 1px solid #f0f2f5;">
                    @else
                        <div class="rounded-4 d-flex align-items-center justify-content-center mb-3 mx-auto"
                             style="width: 100%; max-width: 260px; height: 200px; background: #f8f9fc; color: #d1d5db; font-size: 56px; border: 1px solid #f0f2f5;">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif

                    <h5 class="fw-bold mb-1" style="color: #1e1e2f;">{{ $product->name }}</h5>
                    @if($product->category)
                        <span class="badge badge-custom" style="background: #f0f4ff; color: #4f6af5;">
                            <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Product Details --}}
        <div class="col-md-8">
            <div class="card card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-info-circle me-2 text-primary"></i>Product Information</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary" style="border-radius: 8px;">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" onclick="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.')">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-modern mb-0">
                        <tbody>
                            <tr>
                                <td style="width: 180px; font-weight: 600; color: #374151;">ID</td>
                                <td>#{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; color: #374151;">Slug</td>
                                <td><code>{{ $product->slug }}</code></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; color: #374151;">Category</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; color: #374151;">Price</td>
                                <td>
                                    @if($product->has_discount)
                                        <span style="color: #9ca3af; text-decoration: line-through;">${{ number_format($product->price, 2) }}</span>
                                        <span class="fw-bold" style="color: #ef4444; font-size: 16px;">${{ number_format($product->final_price, 2) }}</span>
                                        <span class="badge rounded-pill ms-2" style="background: #fee2e2; color: #dc2626;">-{{ $product->discount_percent }}%</span>
                                    @else
                                        <span class="fw-bold" style="font-size: 16px;">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; color: #374151;">Discount Status</td>
                                <td>
                                    @php
                                        $discountLabels = [
                                            'active'    => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Active'],
                                            'scheduled' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Scheduled'],
                                            'expired'   => ['bg' => '#f3f4f6', 'text' => '#9ca3af', 'label' => 'Expired'],
                                            'none'      => ['bg' => '#f8f9fc', 'text' => '#6b7280', 'label' => 'No Discount'],
                                        ];
                                        $ds = $discountLabels[$product->discount_status];
                                    @endphp
                                    <span class="badge" style="background: {{ $ds['bg'] }}; color: {{ $ds['text'] }}; padding: 5px 12px; border-radius: 20px;">
                                        {{ $ds['label'] }}
                                    </span>
                                    @if($product->discount_start_at)
                                        <span class="text-muted ms-2" style="font-size: 12px;">
                                            <i class="bi bi-calendar"></i> {{ $product->discount_start_at->format('M d, Y H:i') }}
                                            @if($product->discount_end_at)
                                                → {{ $product->discount_end_at->format('M d, Y H:i') }}
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; color: #374151;">Stock</td>
                                <td>
                                    @php
                                        $stockStatus = $product->stock === 0 ? 'out' : ($product->stock <= 5 ? 'low' : 'in');
                                        $stockColors = [
                                            'out' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'dot' => '#ef4444', 'label' => 'Out of Stock'],
                                            'low' => ['bg' => '#fef3c7', 'text' => '#92400e', 'dot' => '#f59e0b', 'label' => 'Low Stock'],
                                            'in'  => ['bg' => '#d1fae5', 'text' => '#065f46', 'dot' => '#10b981', 'label' => 'In Stock'],
                                        ];
                                        $sc = $stockColors[$stockStatus];
                                    @endphp
                                    <span class="badge d-inline-flex align-items-center gap-1" style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }}; padding: 5px 12px; border-radius: 20px;">
                                        <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background: {{ $sc['dot'] }};"></span>
                                        {{ $sc['label'] }}
                                        <span class="fw-bold ms-1">{{ $product->stock }}</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; color: #374151;">Created</td>
                                <td>{{ $product->created_at->format('F d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; color: #374151;">Updated</td>
                                <td>{{ $product->updated_at->diffForHumans() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Description --}}
            @if($product->description)
                <div class="card card-modern mt-4">
                    <div class="card-header">
                        <span><i class="bi bi-card-text me-2 text-primary"></i>Description</span>
                    </div>
                    <div class="card-body">
                        <p style="color: #374151; line-height: 1.7; margin: 0;">{{ nl2br(e($product->description)) }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="card card-modern mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-star me-2 text-warning"></i>Customer Reviews ({{ $product->reviews->count() }})</span>
        </div>
        <div class="card-body p-0">
            @if($product->reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th style="width: 140px;">Rating</th>
                                <th>Comment</th>
                                <th style="width: 120px;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->reviews as $review)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                 style="width: 30px; height: 30px; background: #eef2ff; color: #667eea; font-size: 12px; font-weight: 600;">
                                                {{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span style="font-size: 13px;">{{ $review->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}" 
                                               style="color: {{ $i <= $review->rating ? '#f59e0b' : '#d1d5db' }}; font-size: 14px;"></i>
                                        @endfor
                                    </td>
                                    <td style="font-size: 13px; max-width: 300px;">
                                        {{ $review->comment ?: '(No comment)' }}
                                    </td>
                                    <td style="font-size: 12px; color: #6b7280;">{{ $review->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div style="font-size: 40px; color: #d1d5db; margin-bottom: 12px;">
                        <i class="bi bi-star"></i>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 13px;">No reviews yet for this product.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Orders containing this product --}}
    @php $uniqueOrderCount = $product->orderItems->pluck('order_id')->unique()->count(); @endphp
    @if($uniqueOrderCount > 0)
        <div class="card card-modern">
            <div class="card-header">
                <span><i class="bi bi-truck me-2 text-primary"></i>Orders Containing This Product ({{ $uniqueOrderCount }})</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->orderItems as $item)
                                @if($item->order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $item->order) }}" class="fw-semibold text-decoration-none" style="color: #667eea;">
                                                {{ $item->order->order_number }}
                                            </a>
                                        </td>
                                        <td style="font-size: 13px;">{{ $item->order->user->name ?? 'N/A' }}</td>
                                        <td class="fw-semibold">{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td class="fw-semibold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending'    => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                                    'processing' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                                    'completed'  => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                                    'cancelled'  => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                                ];
                                                $sc = $statusColors[$item->order->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                            @endphp
                                            <span class="badge" style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }}; padding: 4px 10px; border-radius: 20px; font-size: 11px;">
                                                {{ ucfirst($item->order->status) }}
                                            </span>
                                        </td>
                                        <td style="font-size: 12px; color: #6b7280;">{{ $item->order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $item->order) }}" class="btn btn-sm btn-outline-secondary border-0" style="border-radius: 8px; padding: 4px 10px;">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>
@endsection
