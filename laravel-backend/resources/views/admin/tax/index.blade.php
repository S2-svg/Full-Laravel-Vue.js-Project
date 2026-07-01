@extends('admin.layout')

@section('title', 'VAT Declaration')
@section('page-title', 'VAT Declaration — Ministry of Economy & Finance')

@section('content')
    {{-- Period Selector --}}
    <div class="card card-modern mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-auto">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Month</label>
                    <select name="month" class="form-select" style="border-radius: 10px; min-width: 150px;">
                        @foreach($months as $val => $label)
                            <option value="{{ $val }}" {{ $month == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Year</label>
                    <select name="year" class="form-select" style="border-radius: 10px; min-width: 120px;">
                        @foreach($years as $val)
                            <option value="{{ $val }}" {{ $year == $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; border-radius: 10px; font-weight: 600;">
                        <i class="bi bi-search me-1"></i> View Report
                    </button>
                    <a href="{{ route('admin.tax.export-pdf', ['year' => $year, 'month' => $month]) }}" class="btn btn-danger px-3" style="border-radius: 10px; font-weight: 600;">
                        <i class="bi bi-filetype-pdf me-1"></i> PDF
                    </a>
                    <a href="{{ route('admin.tax.export-csv', ['year' => $year, 'month' => $month]) }}" class="btn btn-success px-3" style="border-radius: 10px; font-weight: 600;">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel (CSV)
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; background: rgba(102,126,234,0.1); color: #667eea; font-size: 22px;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Orders</div>
                    <div class="fw-bold" style="font-size: 28px; color: #1e1e2f;">{{ $summary['total_orders'] }}</div>
                    <div class="text-muted" style="font-size: 12px;">{{ $summary['completed_orders'] }} completed</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; background: rgba(16,185,129,0.1); color: #10b981; font-size: 22px;">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Original Sales</div>
                    <div class="fw-bold" style="font-size: 28px; color: #1e1e2f;">${{ number_format($summary['total_original_sales'], 2) }}</div>
                    <div class="text-muted" style="font-size: 12px;">Before discounts</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; background: rgba(245,158,11,0.1); color: #f59e0b; font-size: 22px;">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">VAT Collected (10%)</div>
                    <div class="fw-bold" style="font-size: 28px; color: #1e1e2f;">${{ number_format($summary['total_vat'], 2) }}</div>
                    <div class="text-muted" style="font-size: 12px;">On original price</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; background: rgba(239,68,68,0.1); color: #ef4444; font-size: 22px;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Grand Total</div>
                    <div class="fw-bold" style="font-size: 28px; color: #1e1e2f;">${{ number_format($summary['total_grand'], 2) }}</div>
                    <div class="text-muted" style="font-size: 12px;">Net + VAT</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detailed Breakdown --}}
    <div class="card card-modern">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="d-flex align-items-center gap-2">
                <i class="bi bi-list-columns text-primary"></i>
                Order Breakdown — {{ $summary['period'] }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th class="text-end">Original Total</th>
                            <th class="text-end">Discount</th>
                            <th class="text-end">Net Total</th>
                            <th class="text-end">VAT (10%)</th>
                            <th class="text-end">Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $origTotal = $order->items->sum(fn($item) => ($item->original_price ?? $item->price) * $item->quantity);
                                $discount  = $order->items->sum(fn($item) => (($item->original_price ?? $item->price) - $item->price) * $item->quantity);
                                $vatCollected = $order->vat_total ?: $order->items->sum('vat_amount');
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="fw-semibold text-decoration-none" style="color: #667eea;">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td style="color: #6b7280; font-size: 13px;">{{ $order->created_at->format('M d, Y') }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending'    => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                            'processing' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                            'completed'  => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                            'cancelled'  => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                        ];
                                        $sc = $statusColors[$order->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                    @endphp
                                    <span class="badge badge-custom" style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }};">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="text-end fw-semibold">${{ number_format($origTotal, 2) }}</td>
                                <td class="text-end text-danger">${{ number_format($discount, 2) }}</td>
                                <td class="text-end fw-semibold">${{ number_format($order->total, 2) }}</td>
                                <td class="text-end fw-semibold" style="color: #667eea;">${{ number_format($vatCollected, 2) }}</td>
                                <td class="text-end fw-bold" style="color: #1e1e2f;">${{ number_format($order->total + $vatCollected, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="text-center py-5">
                                        <div style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <h6 style="color: #6b7280; font-weight: 600;">No orders found</h6>
                                        <p class="text-muted mb-0" style="font-size: 13px;">No orders exist for {{ $summary['period'] }}.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($orders->count() > 0)
                        <tfoot>
                            <tr style="background: #f8f9fc;">
                                <td colspan="4" class="fw-bold text-end" style="font-size: 14px;">Totals for {{ $summary['period'] }}</td>
                                <td class="text-end fw-bold">${{ number_format($summary['total_original_sales'], 2) }}</td>
                                <td class="text-end fw-bold text-danger">${{ number_format($summary['total_discounts'], 2) }}</td>
                                <td class="text-end fw-bold">${{ number_format($summary['total_net_sales'], 2) }}</td>
                                <td class="text-end fw-bold" style="color: #667eea;">${{ number_format($summary['total_vat'], 2) }}</td>
                                <td class="text-end fw-bold" style="color: #1e1e2f; font-size: 15px;">
                                    ${{ number_format($summary['total_grand'], 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Declaration Note --}}
    <div class="mt-4 p-4 rounded-4" style="background: #f0f4ff; border: 1px solid #dbeafe;">
        <div class="d-flex align-items-start gap-3">
            <div style="font-size: 24px; color: #667eea;">
                <i class="bi bi-info-circle"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1" style="color: #1e1e2f;">Declaration for Submission</h6>
                <p class="mb-0" style="font-size: 13px; color: #6b7280;">
                    This report serves as a Monthly VAT Declaration for submission to the 
                    <strong>Ministry of Economy and Finance</strong>. VAT is calculated at <strong>10%</strong> 
                    on the original sales price (before discounts) as per Cambodian tax regulations. 
                    Download the <strong>PDF</strong> for a formal signed document or the <strong>Excel (CSV)</strong> 
                    for digital submission.
                </p>
            </div>
        </div>
    </div>
@endsection
