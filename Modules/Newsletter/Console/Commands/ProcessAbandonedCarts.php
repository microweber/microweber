<?php

namespace Modules\Newsletter\Console\Commands;

use Illuminate\Console\Command;
use Modules\Newsletter\Services\AbandonedCartService;

class ProcessAbandonedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:process-abandoned-carts
                            {--delay=60 : Minutes of inactivity before cart is considered abandoned}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process abandoned carts and queue recovery emails';

    /**
     * Execute the console command.
     */
    public function handle(AbandonedCartService $abandonedCartService): int
    {
        $delay = (int) $this->option('delay');

        $this->info("Processing abandoned carts (delay: {$delay} minutes)...");

        $results = $abandonedCartService->processAbandonedCarts($delay);

        $this->info("Processed {$results['processed']} abandoned carts");
        $this->info("Triggered {$results['triggered']} recovery emails");

        if ($results['failed'] > 0) {
            $this->warn("Failed to process {$results['failed']} carts");
            foreach ($results['errors'] as $error) {
                $this->error("  - {$error['email']}: {$error['error']}");
            }
        }

        return self::SUCCESS;
    }
}
