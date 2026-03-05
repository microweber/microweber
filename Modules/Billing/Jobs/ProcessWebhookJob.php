<?php

namespace Modules\Billing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Billing\Models\WebhookLog;

class ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = [30, 60, 120];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public WebhookLog $webhookLog
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->webhookLog->markAsProcessing();

            // Dispatch the webhook event
            $payload = $this->webhookLog->payload;
            
            \Laravel\Cashier\Events\WebhookReceived::dispatch($payload);

            $this->webhookLog->markAsCompleted();
            
            Log::info('Webhook processed successfully', [
                'event_id' => $this->webhookLog->event_id,
                'event_type' => $this->webhookLog->event_type,
            ]);
        } catch (\Exception $e) {
            $this->webhookLog->markAsFailed($e->getMessage());
            
            Log::error('Webhook processing failed', [
                'event_id' => $this->webhookLog->event_id,
                'event_type' => $this->webhookLog->event_type,
                'error' => $e->getMessage(),
                'attempts' => $this->webhookLog->attempts,
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $this->webhookLog->markAsFailed($exception->getMessage());
        
        Log::error('Webhook job failed permanently', [
            'event_id' => $this->webhookLog->event_id,
            'event_type' => $this->webhookLog->event_type,
            'error' => $exception->getMessage(),
        ]);
    }
}
