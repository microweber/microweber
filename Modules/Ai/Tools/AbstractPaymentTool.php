<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Billing\Models\WebhookLog;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Models\Payment;

abstract class AbstractPaymentTool extends AbstractBillingTool
{
    protected string $domain = 'payment';

    protected array $requiredPermissions = ['view payments'];

    protected function normalizePaymentStatus(mixed $value): string
    {
        $status = strtolower(trim((string) $value));

        return in_array($status, array_column(PaymentStatus::cases(), 'value'), true) ? $status : '';
    }

    protected function normalizeWebhookStatus(mixed $value): string
    {
        $status = strtolower(trim((string) $value));

        return in_array($status, [
            WebhookLog::STATUS_PENDING,
            WebhookLog::STATUS_PROCESSING,
            WebhookLog::STATUS_COMPLETED,
            WebhookLog::STATUS_FAILED,
            WebhookLog::STATUS_RETRYING,
        ], true) ? $status : '';
    }

    protected function normalizeDate(mixed $value): ?Carbon
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    protected function maskTransactionId(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return 'Not set';
        }

        if (strlen($value) <= 12) {
            return Str::substr($value, 0, 4) . '...' . Str::substr($value, -2);
        }

        return Str::substr($value, 0, 8) . '...' . Str::substr($value, -4);
    }

    protected function paymentStatusLabel(?string $status): string
    {
        foreach (PaymentStatus::cases() as $case) {
            if ($case->value === $status) {
                return $case->getLabel();
            }
        }

        return Str::headline((string) $status);
    }

    protected function webhookStatusLabel(?string $status): string
    {
        return match ($status) {
            WebhookLog::STATUS_PENDING => 'Pending',
            WebhookLog::STATUS_PROCESSING => 'Processing',
            WebhookLog::STATUS_COMPLETED => 'Completed',
            WebhookLog::STATUS_FAILED => 'Failed',
            WebhookLog::STATUS_RETRYING => 'Retrying',
            default => Str::headline((string) $status),
        };
    }

    protected function paymentIdentifier(Payment $payment): string
    {
        $identifier = 'Payment #' . $payment->id;

        if ((string) $payment->transaction_id !== '') {
            $identifier .= '<br><small class="text-muted">' . e($this->maskTransactionId((string) $payment->transaction_id)) . '</small>';
        }

        return $identifier;
    }

    protected function relationSummary(Payment $payment): string
    {
        $relType = trim((string) $payment->rel_type);
        $relId = trim((string) $payment->rel_id);

        if ($relType === '' && $relId === '') {
            return 'Not linked';
        }

        return ($relType !== '' ? $relType : 'record') . ($relId !== '' ? (' #' . $relId) : '');
    }

    protected function sanitizeMessage(string $message, int $limit = 100): string
    {
        $message = trim($message);

        if ($message === '') {
            return 'None';
        }

        $sanitized = preg_replace('/(password|secret|token|api[_-]?key|client[_-]?secret|webhook[_-]?secret)\s*[:=]\s*[^,\s]+/i', '$1=[redacted]', $message) ?? $message;
        $sanitized = preg_replace('/\b\d{12,19}\b/', '[redacted]', $sanitized) ?? $sanitized;

        return Str::limit($sanitized, $limit);
    }
}
