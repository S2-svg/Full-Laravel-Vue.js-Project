<?php

namespace App\Console\Commands;

use App\Models\AdminNotification;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessDiscounts extends Command
{
    protected $signature = 'products:process-discounts
                            {--dry-run : Run without making any changes or creating notifications}
                            {--cleanup : Clear discount_percent and date fields for fully expired discounts}';

    protected $description = 'Check scheduled product discounts, notify admin on activation/expiration, and optionally clean up expired data';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $cleanup = $this->option('cleanup');
        $now = Carbon::now();

        if ($dryRun) {
            $this->warn('🔍 DRY RUN — no changes will be made');
        }

        // ── 1. Discounts that have just expired ──
        $this->processExpiredDiscounts($now, $dryRun);

        // ── 2. Discounts that have just become active ──
        $this->processActivatedDiscounts($dryRun);

        // ── 3. Optional cleanup of fully expired discounts ──
        if ($cleanup && !$dryRun) {
            $this->cleanupExpiredDiscounts($now);
        }

        $this->newLine();
        $this->info('✅ Done' . ($dryRun ? ' (dry run)' : ''));

        return Command::SUCCESS;
    }

    /**
     * Find products whose discount has expired and create notifications.
     * Checks for ANY existing notification (read or unread) to prevent duplicates.
     */
    protected function processExpiredDiscounts(Carbon $now, bool $dryRun): void
    {
        $expired = Product::where('discount_percent', '>', 0)
            ->whereNotNull('discount_end_at')
            ->where('discount_end_at', '<=', $now)
            ->get();

        if ($expired->isEmpty()) {
            $this->line(' No expired discounts found.');
            return;
        }

        $this->line(" Found <fg=red>{$expired->count()}</> expired discount(s):");

        foreach ($expired as $product) {
            $this->line("   • {$product->name} — ended {$product->discount_end_at->diffForHumans()}");

            if ($dryRun) {
                continue;
            }

            // Check for ANY existing notification (read or unread) to avoid duplicates across runs
            $alreadyNotified = AdminNotification::ofType('discount_expired')
                ->where('product_id', $product->id)
                ->exists();

            if (!$alreadyNotified) {
                AdminNotification::create([
                    'type'       => 'discount_expired',
                    'message'    => "Discount expired: {$product->name} (was -{$product->discount_percent}%)",
                    'product_id' => $product->id,
                ]);
                $this->line("     → Notification created");
            } else {
                $this->line("     → Already notified (skipped)");
            }
        }
    }

    /**
     * Find products whose scheduled discount has become active and create notifications.
     * Uses ANY existing notification (read or unread) for deduplication — no time-window heuristic needed.
     */
    protected function processActivatedDiscounts(bool $dryRun): void
    {
        $now = Carbon::now();

        $activated = Product::where('discount_percent', '>', 0)
            ->whereNotNull('discount_start_at')
            ->where('discount_start_at', '>=', $now->copy()->subDay())
            ->where('discount_start_at', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('discount_end_at')
                  ->orWhere('discount_end_at', '>', $now);
            })
            ->get();

        if ($activated->isEmpty()) {
            $this->line(' No newly activated discounts found.');
            return;
        }

        $newCount = 0;

        foreach ($activated as $product) {
            // Check for ANY existing discount_activated notification (read or unread)
            $alreadyNotified = AdminNotification::ofType('discount_activated')
                ->where('product_id', $product->id)
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            $newCount++;

            $this->line("   • {$product->name} — -{$product->discount_percent}% (started {$product->discount_start_at->diffForHumans()})");

            if ($dryRun) {
                continue;
            }

            $endMsg = $product->discount_end_at
                ? " until {$product->discount_end_at->format('M d, H:i')}"
                : '';

            AdminNotification::create([
                'type'       => 'discount_activated',
                'message'    => "Discount active: {$product->name} — -{$product->discount_percent}%{$endMsg}",
                'product_id' => $product->id,
            ]);
        }

        if ($newCount === 0) {
            $this->line(' No newly activated discounts found (all previously notified).');
        }
    }

    /**
     * Clear discount data for fully expired products where discount_end_at is well in the past
     * (older than 7 days) so the database stays tidy.
     */
    protected function cleanupExpiredDiscounts(Carbon $now): void
    {
        $cleaned = Product::where('discount_percent', '>', 0)
            ->whereNotNull('discount_end_at')
            ->where('discount_end_at', '<=', $now->copy()->subDays(7))
            ->update([
                'discount_percent' => 0,
                'discount_start_at' => null,
                'discount_end_at' => null,
            ]);

        if ($cleaned > 0) {
            $this->line("🧹 <fg=yellow>{$cleaned}</> expired discount(s) cleaned up (data reset).");
        } else {
            $this->line(' No discounts eligible for cleanup yet (expired < 7 days ago).');
        }
    }
}
