<?php

namespace App\Console\Commands;

use App\Mail\VatReportMail;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GenerateMonthlyVatReport extends Command
{
    protected $signature = 'tax:generate-monthly-report
                            {--month= : The month (1-12) to generate the report for}
                            {--year= : The year to generate the report for}
                            {--email= : Send the report to a specific email address (falls back to TAX_REPORT_EMAIL env)}
                            {--disk=local : Disk to store the generated PDF on}
                            {--keep-pdf : Keep the PDF on disk after sending (default: delete)}';

    protected $description = 'Generate the monthly VAT declaration PDF and email it to the configured recipient';

    /**
     * VAT rate used for calculation (10%).
     */
    protected const VAT_RATE = 0.10;

    public function handle(): int
    {
        // Determine the period — default to the previous month
        $month = (int) ($this->option('month') ?: Carbon::now()->subMonth()->month);
        $year  = (int) ($this->option('year') ?: Carbon::now()->subMonth()->year);

        $periodName = Carbon::create($year, $month, 1)->format('F Y');
        $startDate  = "{$year}-{$month}-01";
        $endDate    = date('Y-m-t', strtotime($startDate));

        $this->info(sprintf('📊 Generating VAT Declaration for %s...', $periodName));
        $this->line(sprintf('   Period: %s — %s', $startDate, $endDate));

        // ── Fetch orders ──
        $orders = Order::with(['items', 'user'])
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        if ($orders->isEmpty()) {
            $this->warn(sprintf('⚠️  No orders found for %s. Skipping report.', $periodName));

            return Command::SUCCESS;
        }

        $this->line(sprintf('   Found %d order(s).', $orders->count()));

        // ── Calculate summary ──
        $summary = $this->calculateSummary($orders, $year, $month, $periodName, $startDate, $endDate);

        // ── Generate PDF ──
        $pdf = Pdf::loadView('admin.tax.pdf', compact('orders', 'summary', 'year', 'month', 'startDate', 'endDate'));

        $disk       = $this->option('disk') ?: 'local';
        $pdfFilename = sprintf('vat_report_%d_%d.pdf', $year, $month);
        $pdfPath     = 'tax-reports/' . $pdfFilename;

        Storage::disk($disk)->put($pdfPath, $pdf->output());

        $fullPath = Storage::disk($disk)->path($pdfPath);
        $this->line(sprintf('   PDF saved to: %s', $fullPath));

        // ── Determine recipient ──
        $recipient = $this->option('email') ?: env('TAX_REPORT_EMAIL');

        if (!$recipient) {
            $this->warn('⚠️  No recipient email configured. Set TAX_REPORT_EMAIL in .env or pass --email.');
            $this->line(sprintf('   PDF remains at: %s', $fullPath));

            return Command::SUCCESS;
        }

        // ── Send email (synchronous — do not queue, since the PDF is deleted after sending) ──
        try {
            Mail::to($recipient)->send(new VatReportMail($periodName, $fullPath, $summary));

            $this->info(sprintf('📧 Report emailed to: %s', $recipient));

            // Log the auto-send for audit trail
            $this->logAutoSend($periodName, $recipient, $orders->count(), $summary['total_vat']);
        } catch (\Exception $e) {
            $this->error(sprintf('❌ Failed to send email: %s', $e->getMessage()));
            $this->line(sprintf('   PDF remains at: %s', $fullPath));

            return Command::FAILURE;
        }

        // ── Cleanup ──
        if (!$this->option('keep-pdf')) {
            Storage::disk($disk)->delete($pdfPath);
            $this->line('   🧹 Temporary PDF cleaned up.');
        } else {
            $this->line(sprintf('   📁 PDF kept at: %s', $fullPath));
        }

        $this->newLine();
        $this->info('✅ Monthly VAT report generated and sent successfully.');

        return Command::SUCCESS;
    }

    /**
     * Calculate the tax summary for the given orders.
     */
    protected function calculateSummary($orders, int $year, int $month, string $periodName, string $startDate, string $endDate): array
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
            'period'               => $periodName,
            'total_orders'         => $orders->count(),
            'completed_orders'     => $orders->where('status', 'completed')->count(),
            'total_original_sales' => round($totalOriginalSales, 2),
            'total_discounts'      => round($totalDiscounts, 2),
            'total_net_sales'      => round($totalNetSales, 2),
            'total_vat'            => round($totalVat, 2),
            'total_grand'          => round($totalNetSales + $totalVat, 2),
            'vat_rate'             => (self::VAT_RATE * 100) . '%',
            'start_date'           => $startDate,
            'end_date'             => $endDate,
        ];
    }

    /**
     * Log the auto-generated report to storage for audit trail purposes.
     */
    protected function logAutoSend(string $period, string $recipient, int $orderCount, float $vatTotal): void
    {
        $logLine = sprintf(
            '[%s] VAT Report sent for %s | Recipient: %s | Orders: %d | VAT: $%.2f',
            now()->toDateTimeString(),
            $period,
            $recipient,
            $orderCount,
            $vatTotal
        );

        Storage::disk('local')->append('tax-report-log.txt', $logLine);
    }
}
