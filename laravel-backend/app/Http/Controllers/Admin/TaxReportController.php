<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TaxReportController extends Controller
{
    /**
     * VAT rate used for calculation (10%).
     */
    protected const VAT_RATE = 0.10;

    /**
     * Show the monthly VAT declaration report.
     */
    public function index(Request $request)
    {
        $year  = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $startDate = "{$year}-{$month}-01";
        $endDate   = date('Y-m-t', strtotime($startDate));

        $orders = Order::with(['items', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        // If no orders with vat_total exist, calculate from order_items for backward compatibility
        $summary = $this->calculateSummary($orders, $startDate, $endDate);

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = date('F', mktime(0, 0, 0, $m, 1));
        }

        $years = range(now()->year, 2024);

        return view('admin.tax.index', compact(
            'orders', 'summary', 'year', 'month', 'months', 'years', 'startDate', 'endDate'
        ));
    }

    /**
     * Export the monthly VAT declaration as a PDF document.
     */
    public function exportPdf(Request $request)
    {
        $year  = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $startDate = "{$year}-{$month}-01";
        $endDate   = date('Y-m-t', strtotime($startDate));

        $orders = Order::with(['items', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        $summary = $this->calculateSummary($orders, $startDate, $endDate);

        $pdf = Pdf::loadView('admin.tax.pdf', compact(
            'orders', 'summary', 'year', 'month', 'startDate', 'endDate'
        ));

        $filename = "VAT_Declaration_{$year}_{$month}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Export the monthly VAT declaration as a CSV file (opens in Excel).
     */
    public function exportCsv(Request $request)
    {
        $year  = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $startDate = "{$year}-{$month}-01";
        $endDate   = date('Y-m-t', strtotime($startDate));

        $orders = Order::with(['items', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        $summary = $this->calculateSummary($orders, $startDate, $endDate);

        $filename = "VAT_Declaration_{$year}_{$month}.csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders, $summary, $year, $month) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // ── Header Section ──
            fputcsv($handle, ['MONTHLY VAT DECLARATION']);
            fputcsv($handle, ["Period: {$month}/{$year}"]);
            fputcsv($handle, []);

            // ── Summary ──
            fputcsv($handle, ['SUMMARY']);
            fputcsv($handle, ['Total Orders', $summary['total_orders']]);
            fputcsv($handle, ['Completed Orders', $summary['completed_orders']]);
            fputcsv($handle, ['Total Original Sales (before discount)', number_format($summary['total_original_sales'], 2)]);
            fputcsv($handle, ['Total Discounts Given', number_format($summary['total_discounts'], 2)]);
            fputcsv($handle, ['Total Net Sales (after discount)', number_format($summary['total_net_sales'], 2)]);
            fputcsv($handle, ['VAT Rate', (self::VAT_RATE * 100) . '%']);
            fputcsv($handle, ['Total VAT Collected', number_format($summary['total_vat'], 2)]);
            fputcsv($handle, ['Grand Total (incl. VAT)', number_format($summary['total_grand'], 2)]);
            fputcsv($handle, []);

            // ── Header Row ──
            fputcsv($handle, [
                'Order #', 'Date', 'Customer', 'Status',
                'Original Total', 'Discount', 'Net Total', 'VAT Collected', 'Grand Total'
            ]);

            // ── Order Rows ──
            foreach ($orders as $order) {
                $origTotal = $order->items->sum(function ($item) {
                    return ($item->original_price ?? $item->price) * $item->quantity;
                });
                $discount  = $order->items->sum(function ($item) {
                    return (($item->original_price ?? $item->price) - $item->price) * $item->quantity;
                });
                $vatCollected = $order->vat_total ?: $order->items->sum('vat_amount');

                fputcsv($handle, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d'),
                    $order->user->name ?? 'N/A',
                    ucfirst($order->status),
                    number_format($origTotal, 2),
                    number_format($discount, 2),
                    number_format($order->total, 2),
                    number_format($vatCollected, 2),
                    number_format($order->total + $vatCollected, 2),
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Calculate the tax summary for the given orders.
     */
    protected function calculateSummary($orders, string $startDate, string $endDate): array
    {
        $totalOriginalSales = 0;
        $totalDiscounts     = 0;
        $totalNetSales      = 0;
        $totalVat           = 0;

        foreach ($orders as $order) {
            $orderOrigTotal = 0;
            $orderDiscount  = 0;

            foreach ($order->items as $item) {
                $originalPrice = $item->original_price ?? $item->price;
                $finalPrice    = $item->price;
                $qty           = $item->quantity;

                $orderOrigTotal += $originalPrice * $qty;
                $orderDiscount  += ($originalPrice - $finalPrice) * $qty;
            }

            $totalOriginalSales += $orderOrigTotal;
            $totalDiscounts     += $orderDiscount;
            $totalNetSales      += $order->total;
            $totalVat           += $order->vat_total ?: $order->items->sum('vat_amount');
        }

        return [
            'period'              => date('F Y', strtotime($startDate)),
            'total_orders'        => $orders->count(),
            'completed_orders'    => $orders->where('status', 'completed')->count(),
            'total_original_sales' => round($totalOriginalSales, 2),
            'total_discounts'     => round($totalDiscounts, 2),
            'total_net_sales'     => round($totalNetSales, 2),
            'total_vat'           => round($totalVat, 2),
            'total_grand'         => round($totalNetSales + $totalVat, 2),
            'vat_rate'            => (self::VAT_RATE * 100) . '%',
            'start_date'          => $startDate,
            'end_date'            => $endDate,
        ];
    }
}
