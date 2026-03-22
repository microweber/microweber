<?php

namespace Modules\Product\Console\Commands;

use Illuminate\Console\Command;
use Modules\Product\Services\InventoryService;

class CleanupExpiredStockReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:cleanup-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired stock reservations and release reserved inventory';

    /**
     * Execute the console command.
     */
    public function handle(InventoryService $inventoryService): int
    {
        $this->info('Starting cleanup of expired stock reservations...');

        $count = $inventoryService->cleanupExpiredReservations();

        if ($count > 0) {
            $this->info("Successfully cleaned up {$count} expired reservation(s).");
        } else {
            $this->info('No expired reservations found.');
        }

        return self::SUCCESS;
    }
}
