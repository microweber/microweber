<?php

namespace Modules\Newsletter\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Newsletter\Jobs\ProcessTriggeredEmail;
use Modules\Newsletter\Models\NewsletterAutomationQueue;
use Modules\Newsletter\Models\NewsletterCampaign;

class ProcessAutomationQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:process-automation-queue
                            {--limit=100 : Maximum number of emails to process}
                            {--campaign= : Process only emails for a specific campaign ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the automation queue and send triggered emails';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $campaignId = $this->option('campaign');

        $this->info("Processing automation queue (limit: {$limit})...");

        $query = NewsletterAutomationQueue::readyToSend();

        if ($campaignId) {
            $query->where('campaign_id', $campaignId);
            $this->info("Filtering by campaign ID: {$campaignId}");
        }

        $items = $query->limit($limit)->get();

        if ($items->isEmpty()) {
            $this->info('No pending emails to send.');
            return self::SUCCESS;
        }

        $this->info("Found {$items->count()} pending emails to process.");

        $sent = 0;
        $failed = 0;

        foreach ($items as $item) {
            try {
                $this->processQueueItem($item);
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                Log::error('ProcessAutomationQueue: Failed to process queue item', [
                    'queue_item_id' => $item->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Sent: {$sent}, Failed: {$failed}");

        return self::SUCCESS;
    }

    /**
     * Process a single queue item.
     *
     * @param NewsletterAutomationQueue $item
     * @return void
     */
    protected function processQueueItem(NewsletterAutomationQueue $item): void
    {
        $campaign = NewsletterCampaign::find($item->campaign_id);

        if (!$campaign) {
            $item->markAsFailed('Campaign not found');
            return;
        }

        if (!$campaign->is_active) {
            $item->markAsFailed('Campaign is inactive');
            return;
        }

        // Dispatch the job to send the email
        dispatch(new ProcessTriggeredEmail($item->id));

        Log::info('ProcessAutomationQueue: Dispatched triggered email job', [
            'queue_item_id' => $item->id,
            'campaign_id' => $item->campaign_id,
            'email' => $item->email,
        ]);
    }
}
