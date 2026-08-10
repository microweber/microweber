<?php

declare(strict_types=1);

namespace Modules\Billing\Tools;

use Modules\Billing\Models\WebhookLog;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingPaymentWebhookHealthTool extends AbstractPaymentTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_payment_webhook_health',
            'Review payment webhook processing health by provider and status without exposing raw payloads.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'provider', type: PropertyType::STRING, description: 'Optional provider filter such as stripe.', required: false),
            new ToolProperty(name: 'status', type: PropertyType::STRING, description: 'Optional webhook status filter such as failed or retrying.', required: false),
            new ToolProperty(name: 'period_days', type: PropertyType::INTEGER, description: 'Lookback window in days (1-90). Default is 7.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum recent webhook rows to include (1-50). Default is 10.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $provider = strtolower(trim((string) ($args['provider'] ?? '')));
        $status = $this->normalizeWebhookStatus($args['status'] ?? '');
        $periodDays = max(1, min(90, (int) ($args['period_days'] ?? 7)));
        $limit = $this->safeLimit($args['limit'] ?? 10, 10, 50);
        $windowStart = now()->subDays($periodDays);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view payment webhook health.');
        }

        try {
            $baseQuery = WebhookLog::query()->where('created_at', '>=', $windowStart);

            if ($provider !== '') {
                $baseQuery->where('provider', $provider);
            }

            if ($status !== '') {
                $baseQuery->where('status', $status);
            }

            $summary = $this->formatAsHtmlTable(
                [[
                    'total' => (string) (clone $baseQuery)->count(),
                    'completed' => (string) (clone $baseQuery)->where('status', WebhookLog::STATUS_COMPLETED)->count(),
                    'pending' => (string) (clone $baseQuery)->where('status', WebhookLog::STATUS_PENDING)->count(),
                    'retrying' => (string) (clone $baseQuery)->where('status', WebhookLog::STATUS_RETRYING)->count(),
                    'failed' => (string) (clone $baseQuery)->where('status', WebhookLog::STATUS_FAILED)->count(),
                    'window' => $periodDays . ' days',
                ]],
                [
                    'total' => 'Total',
                    'completed' => 'Completed',
                    'pending' => 'Pending',
                    'retrying' => 'Retrying',
                    'failed' => 'Failed',
                    'window' => 'Window',
                ],
                '',
                'billing-payment-webhook-health-summary'
            );

            $rows = $baseQuery
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get()
                ->map(function (WebhookLog $log): array {
                    return [
                        'provider' => (string) $log->provider,
                        'event_type' => (string) $log->event_type,
                        'event_id' => $this->maskTransactionId((string) $log->event_id),
                        'status' => $this->webhookStatusLabel((string) $log->status),
                        'attempts' => (string) $log->attempts,
                        'processed_at' => $log->processed_at?->format('M j, Y H:i') ?: 'Not processed',
                        'error' => $this->sanitizeMessage((string) ($log->error_message ?? '')),
                    ];
                })
                ->all();

            return '<h4>Payment webhook health</h4>'
                . $summary
                . '<h4>Recent webhook events</h4>'
                . $this->formatAsHtmlTable(
                    $rows,
                    [
                        'provider' => 'Provider',
                        'event_type' => 'Event type',
                        'event_id' => 'Event ID',
                        'status' => 'Status',
                        'attempts' => 'Attempts',
                        'processed_at' => 'Processed at',
                        'error' => 'Error',
                    ],
                    'No webhook events matched the requested filters.',
                    'billing-payment-webhook-health-results'
                );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading payment webhook health: ' . $exception->getMessage());
        }
    }
}
