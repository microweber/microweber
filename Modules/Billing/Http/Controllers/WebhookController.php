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

        if (empty($payload) || !isset($payload['type'])) {
            return new Response('Invalid payload', 400);
        }

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
        $customerId = $payload['data']['object']['customer'] ?? null;
        if (!$customerId) {
            return $this->successMethod();
        }

        $user = $this->getUserByStripeId($customerId);

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
        // Call parent method for standard handling (requires items key)
        $response = null;
        if (isset($payload['data']['object']['items'])) {
            $response = parent::handleCustomerSubscriptionUpdated($payload);
        }

        // Additional custom handling
        $customerId = $payload['data']['object']['customer'] ?? null;
        $user = $customerId ? $this->getUserByStripeId($customerId) : null;

        if ($user) {
            Log::info('Customer subscription updated webhook received', [
                'customer' => $payload['data']['object']['customer'],
                'subscription_id' => $payload['data']['object']['id'],
                'status' => $payload['data']['object']['status'] ?? 'unknown',
            ]);
        }

        return $response ?? $this->successMethod();
    }

    /**
     * Handle customer subscription deleted event.
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        $customerId = $payload['data']['object']['customer'] ?? null;
        $subscriptionId = $payload['data']['object']['id'] ?? null;

        if ($customerId) {
            $user = $this->getUserByStripeId($customerId);

            if ($user && $subscriptionId) {
                Log::info('Customer subscription deleted webhook received', [
                    'customer' => $customerId,
                    'subscription_id' => $subscriptionId,
                ]);

                // Update local subscription status
                $subscription = $user->subscriptions()
                    ->where('stripe_id', $subscriptionId)
                    ->first();

                if ($subscription) {
                    $subscription->update([
                        'stripe_status' => 'canceled',
                        'ends_at' => now(),
                    ]);

                    Log::info('Subscription marked as canceled', [
                        'subscription_id' => $subscription->id,
                        'stripe_id' => $subscriptionId,
                    ]);
                }
            }
        }

        return $this->successMethod();
    }

    /**
     * Handle invoice payment failed event.
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleInvoicePaymentFailed(array $payload)
    {
        $customerId = $payload['data']['object']['customer'] ?? null;
        $subscriptionId = $payload['data']['object']['subscription'] ?? null;

        if ($customerId) {
            $user = $this->getUserByStripeId($customerId);

            if ($user) {
                Log::info('Invoice payment failed webhook received', [
                    'customer' => $customerId,
                    'invoice_id' => $payload['data']['object']['id'] ?? null,
                    'subscription_id' => $subscriptionId,
                ]);

                // Update subscription status to past_due if applicable
                if ($subscriptionId) {
                    $subscription = $user->subscriptions()
                        ->where('stripe_id', $subscriptionId)
                        ->first();

                    if ($subscription) {
                        // Check if this is the final attempt
                        $nextPaymentAttempt = $payload['data']['object']['next_payment_attempt'] ?? null;
                        if ($nextPaymentAttempt === null) {
                            // Final failed attempt
                            $subscription->update(['stripe_status' => 'unpaid']);
                        } else {
                            $subscription->update(['stripe_status' => 'past_due']);
                        }
                    }
                }
            }
        }

        return $this->successMethod();
    }

    /**
     * Handle invoice created event.
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleInvoiceCreated(array $payload)
    {
        $customerId = $payload['data']['object']['customer'] ?? null;
        $subscriptionId = $payload['data']['object']['subscription'] ?? null;

        if ($customerId) {
            Log::info('Invoice created webhook received', [
                'customer' => $customerId,
                'invoice_id' => $payload['data']['object']['id'] ?? null,
                'subscription_id' => $subscriptionId,
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle checkout session completed event.
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCheckoutSessionCompleted(array $payload)
    {
        $session = $payload['data']['object'];
        $customerId = $session['customer'] ?? null;
        $subscriptionId = $session['subscription'] ?? null;

        if ($customerId && $subscriptionId) {
            $user = $this->getUserByStripeId($customerId);

            if ($user) {
                Log::info('Checkout session completed webhook received', [
                    'customer' => $customerId,
                    'session_id' => $session['id'] ?? null,
                    'subscription_id' => $subscriptionId,
                ]);

                // Update subscription with the new Stripe subscription ID
                // The actual subscription creation is handled by Laravel Cashier
                $subscription = $user->subscriptions()
                    ->where('stripe_id', $subscriptionId)
                    ->first();

                if ($subscription) {
                    // Mark as active if payment was successful
                    if ($session['payment_status'] === 'paid') {
                        $subscription->update(['stripe_status' => 'active']);
                    }
                }
            }
        }

        return $this->successMethod();
    }
}
