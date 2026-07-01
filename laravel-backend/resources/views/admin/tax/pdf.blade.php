<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VAT Declaration - {{ $summary['period'] }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            padding: 30px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 800;
            color: #1e1e2f;
            margin: 0 0 5px;
        }
        .header .subtitle {
            font-size: 13px;
            color: #6b7280;
        }
        .header .ministry {
            font-size: 14px;
            font-weight: 700;
            color: #667eea;
            margin-top: 5px;
        }
        .period-info {
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #374151;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .summary-table td {
            padding: 8px 14px;
            border: 1px solid #d1d5db;
        }
        .summary-table .label {
            font-weight: 600;
            background: #f0f2f5;
            width: 50%;
        }
        .summary-table .value {
            text-align: right;
            font-weight: 700;
        }
        .summary-table .highlight {
            color: #667eea;
            font-size: 13px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 9px;
        }
        .details-table th {
            background: #667eea;
            color: #fff;
            padding: 7px 8px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .details-table th.right {
            text-align: right;
        }
        .details-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-table td.right {
            text-align: right;
        }
        .details-table tfoot td {
            font-weight: 700;
            background: #f0f2f5;
            border-top: 2px solid #667eea;
            padding: 8px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
        }
        .footer .signature {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }
        .footer .signature div {
            text-align: center;
            width: 200px;
        }
        .footer .signature .line {
            margin-top: 35px;
            border-top: 1px solid #374151;
            padding-top: 5px;
            font-weight: 600;
            color: #374151;
        }
        .vat-rate {
            font-size: 12px;
            font-weight: 600;
        }
        .badge-status {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
        }
        .badge-completed { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-processing { background: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MONTHLY VALUE ADDED TAX (VAT) DECLARATION</h1>
        <div class="ministry">Ministry of Economy and Finance</div>
        <div class="subtitle">Pursuant to the Law on Taxation of the Kingdom of Cambodia</div>
    </div>

    <div class="period-info">
        Declaration Period: {{ $summary['period'] }}<br>
        ({{ $summary['start_date'] }} — {{ $summary['end_date'] }})
    </div>

    {{-- Summary --}}
    <table class="summary-table">
        <tr>
            <td class="label">Total Orders</td>
            <td class="value">{{ $summary['total_orders'] }}</td>
        </tr>
        <tr>
            <td class="label">Completed Orders</td>
            <td class="value">{{ $summary['completed_orders'] }}</td>
        </tr>
        <tr>
            <td class="label">Total Original Sales (Before Discount)</td>
            <td class="value">${{ number_format($summary['total_original_sales'], 2) }}</td>
        </tr>
        <tr>
            <td class="label">Total Discounts Granted</td>
            <td class="value">(${{ number_format($summary['total_discounts'], 2) }})</td>
        </tr>
        <tr>
            <td class="label">Total Net Sales (After Discount)</td>
            <td class="value">${{ number_format($summary['total_net_sales'], 2) }}</td>
        </tr>
        <tr>
            <td class="label">VAT Rate</td>
            <td class="value vat-rate">{{ $summary['vat_rate'] }}</td>
        </tr>
        <tr style="background: #eef2ff;">
            <td class="label" style="font-size: 13px;">Total VAT Collected</td>
            <td class="value highlight" style="font-size: 15px;">${{ number_format($summary['total_vat'], 2) }}</td>
        </tr>
        <tr style="background: #f0fdf4;">
            <td class="label" style="font-size: 13px;">Grand Total (Including VAT)</td>
            <td class="value" style="font-size: 15px; color: #065f46;">${{ number_format($summary['total_grand'], 2) }}</td>
        </tr>
    </table>

    {{-- Order Details --}}
    @if($orders->count() > 0)
        <h4 style="margin-bottom: 10px; color: #1e1e2f; font-size: 12px;">Order Details</h4>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th class="right">Original</th>
                    <th class="right">Discount</th>
                    <th class="right">Net Total</th>
                    <th class="right">VAT (10%)</th>
                    <th class="right">Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    @php
                        $origTotal = $order->items->sum(fn($item) => ($item->original_price ?? $item->price) * $item->quantity);
                        $discount  = $order->items->sum(fn($item) => (($item->original_price ?? $item->price) - $item->price) * $item->quantity);
                        $vatCollected = $order->vat_total ?: $order->items->sum('vat_amount');
                        $statusClass = 'badge-' . $order->status;
                    @endphp
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td><span class="badge-status {{ $statusClass }}">{{ ucfirst($order->status) }}</span></td>
                        <td class="right">${{ number_format($origTotal, 2) }}</td>
                        <td class="right">${{ number_format($discount, 2) }}</td>
                        <td class="right">${{ number_format($order->total, 2) }}</td>
                        <td class="right">${{ number_format($vatCollected, 2) }}</td>
                        <td class="right">${{ number_format($order->total + $vatCollected, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">Totals</td>
                    <td class="right">${{ number_format($summary['total_original_sales'], 2) }}</td>
                    <td class="right">${{ number_format($summary['total_discounts'], 2) }}</td>
                    <td class="right">${{ number_format($summary['total_net_sales'], 2) }}</td>
                    <td class="right">${{ number_format($summary['total_vat'], 2) }}</td>
                    <td class="right">${{ number_format($summary['total_grand'], 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer">
        <p style="font-size: 9px; color: #9ca3af;">
            <strong>Notes:</strong>
            VAT is calculated at 10% on the original price (before discounts) as prescribed by the 
            Law on Taxation of the Kingdom of Cambodia. This declaration is prepared in accordance 
            with the requirements of the Ministry of Economy and Finance.
        </p>

        <div class="signature">
            <div>
                <div>Prepared by:</div>
                <div class="line">Authorized Officer</div>
                <div style="font-size: 9px; color: #9ca3af; margin-top: 3px;">Date: {{ date('F d, Y') }}</div>
            </div>
            <div>
                <div>Reviewed by:</div>
                <div class="line">Finance Manager</div>
                <div style="font-size: 9px; color: #9ca3af; margin-top: 3px;">Date: _______________</div>
            </div>
            <div>
                <div>For Ministry Use:</div>
                <div class="line">Official Stamp</div>
                <div style="font-size: 9px; color: #9ca3af; margin-top: 3px;">Date Received: _______________</div>
            </div>
        </div>
    </div>
</body>
</html>
