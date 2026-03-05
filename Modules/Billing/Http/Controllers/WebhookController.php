<?php

namespace Modules\Billing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;
use Modules\Billing\Jobs\ProcessWebhookJob;
use Modules\Billing\Models\WebhookLog;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends \Laravel\Cashier\Http\Controllers\WebhookController
{
    /**
     * Create a new WebhookController instance.
     */
    public function __construct()
    {
        if (config('cashier.webhook.secret')) {
            $this->middleware(VerifyWebhookSignature::class);
        }
    }

    /**
     * Handle a Stripe webhook call.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        
        // Log webhook to database
        $webhookLog = $this->logWebhook($payload);

        // Dispatch to queue for processing
        if ($webhookLog) {
            ProcessWebhookJob::dispatch($webhookLog);
        }

        // Process webhook immediately for backward compatibility
        $method = 'handle' . Str::studly(str_replace('.', '_', $payload['type']));
        
        WebhookReceived::dispatch($payload);

        if (method_exists($this, $method)) {
            $this->setMaxNetworkRetries();
            $response = $this->{$method}($payload);
            WebhookHandled::dispatch($payload);
            return $response;
        }

        return $this->missingMethod($payload);
    }

    /**
     * Log webhook to database.
     *
     * @param array $payload
     * @return WebhookLog|null
     */
    protected function logWebhook(array $payload): ?WebhookLog
    {
        try {
            // Check if webhook already exists (idempotency)
            $existingLog = WebhookLog::where('event_id', $payload['id'] ?? null)->first();
            if ($existingLog) {
                Log::info('Duplicate webhook received', [
                    'event_id' => $payload['id'],
                    'event_type' => $payload['type'] ?? 'unknown',
                ]);
                return $existingLog;
            }

            return WebhookLog::create([
                'provider' => 'stripe',
                'event_type' => $payload['type'] ?? 'unknown',
                'event_id' => $payload['id'] ?? Str::uuid()->toString(),
                'payload' => $payload,
                'status' => WebhookLog::STATUS_PENDING,
                'attempts' => 0,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log webhook', [
                'error' => $e->getMessage(),
                'event_type' => $payload['type'] ?? 'unknown',
            ]);
            return null;
        }
    }

    /**
     * Handle invoice paid event.
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleInvoicePaid(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        
        if ($user) {
            Log::info('Invoice paid webhook received', [
                'customer' => $payload['data']['object']['customer'],
                'invoice_id' => $payload['data']['object']['id'],
                'amount_paid' => $payload['data']['object']['amount_paid'] ?? 0,
            ]);

            // Update subscription status if needed
            if (isset($payload['data']['object']['subscription'])) {
                $subscription = $user->subscriptions()
                    ->where('stripe_id', $payload['data']['object']['subscription'])
                    ->first();
                
                if ($subscription) {
                    $subscription->stripe_status = 'active';
                    $subscription->save();
                }
            }
        }

        return $this->successMethod();
    }

    /**
     * Handle customer subscription updated event.
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        // Call parent method for standard handling
        $response = parent::handleCustomerSubscriptionUpdated($payload);
        
        // Additional custom handling
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        
        if ($user) {
            Log::info('Customer subscription updated webhook received', [
                'customer' => $payload['data']['object']['customer'],
                'subscription_id' => $payload['data']['object']['id'],
                'status' => $payload['data']['object']['status'] ?? 'unknown',
            ]);
        }

        return $response ?? $this->successMethod();
    }
}
