<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 24px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
            margin-bottom: 24px;
        }
        .header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #1e1e2f;
            margin: 0 0 6px;
        }
        .header .ministry {
            font-size: 13px;
            font-weight: 600;
            color: #667eea;
        }
        .greeting {
            font-size: 16px;
            color: #374151;
            margin-bottom: 20px;
        }
        .summary-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }
        .summary-item {
            flex: 1;
            min-width: 120px;
            background: #f8f9fc;
            border-radius: 12px;
            padding: 14px 16px;
            text-align: center;
        }
        .summary-item .label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .summary-item .value {
            font-size: 20px;
            font-weight: 800;
            color: #1e1e2f;
            margin-top: 4px;
        }
        .summary-item .value.vat {
            color: #667eea;
        }
        .summary-item .value.grand {
            color: #065f46;
        }
        .cta {
            text-align: center;
            margin: 28px 0;
        }
        .cta a {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
        }
        .details {
            background: #f0f4ff;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .details p {
            margin: 4px 0;
            font-size: 13px;
            color: #374151;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }
        .footer strong {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>📊 Monthly VAT Declaration</h1>
                <div class="ministry">Ministry of Economy and Finance</div>
                <div style="color: #6b7280; font-size: 14px; margin-top: 4px;">
                    Declaration Period: <strong>{{ $periodName }}</strong>
                </div>
            </div>

            <div class="greeting">
                Dear Admin,
            </div>

            <p style="color: #374151; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                The monthly VAT declaration for <strong>{{ $periodName }}</strong> has been automatically generated.
                Please find the official PDF report attached for submission to the Ministry of Economy and Finance.
            </p>

            <div class="summary-grid">
                <div class="summary-item">
                    <div class="label">Orders</div>
                    <div class="value">{{ $summary['total_orders'] }}</div>
                </div>
                <div class="summary-item">
                    <div class="label">Net Sales</div>
                    <div class="value">${{ number_format($summary['total_net_sales'], 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="label">VAT (10%)</div>
                    <div class="value vat">${{ number_format($summary['total_vat'], 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="label">Grand Total</div>
                    <div class="value grand">${{ number_format($summary['total_grand'], 2) }}</div>
                </div>
            </div>

            <div class="details">
                <p><strong>📅 Period:</strong> {{ $summary['start_date'] }} — {{ $summary['end_date'] }}</p>
                <p><strong>✅ Completed Orders:</strong> {{ $summary['completed_orders'] }}</p>
                <p><strong>💰 Original Sales:</strong> ${{ number_format($summary['total_original_sales'], 2) }}</p>
                <p><strong>🏷️ Discounts Given:</strong> ${{ number_format($summary['total_discounts'], 2) }}</p>
                <p><strong>📋 VAT Rate:</strong> {{ $summary['vat_rate'] }}</p>
            </div>

            <div class="cta">
                <a href="{{ config('app.url') }}/admin/tax?year={{ date('Y', strtotime($summary['start_date'])) }}&month={{ date('m', strtotime($summary['start_date'])) }}">
                    View in Admin Panel →
                </a>
            </div>

            <p style="color: #6b7280; font-size: 13px; line-height: 1.5; margin-bottom: 0;">
                The attached PDF is ready for submission. Please ensure it is signed by an authorized officer
                before submitting to the Ministry of Economy and Finance.
            </p>

            <div class="footer">
                <p>This is an automated message from your e-commerce system.</p>
                <p><strong>{{ config('app.name') }}</strong> — VAT Report Auto-Generation</p>
            </div>
        </div>
    </div>
</body>
</html>
