<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\ContactForm\Models\Form as ContactForm;
use Modules\Form\Models\FormData;
use Modules\Form\Models\FormList;

abstract class AbstractFormsTool extends BaseTool
{
    protected string $domain = 'forms';

    protected array $requiredPermissions = ['view forms'];

    protected function safeLimit(mixed $limit, int $default = 20, int $max = 50): int
    {
        return max(1, min($max, (int) ($limit ?? $default)));
    }

    protected function normalizeReadStatus(mixed $status): string
    {
        $status = strtolower(trim((string) $status));

        return in_array($status, ['all', 'read', 'unread'], true) ? $status : 'all';
    }

    protected function normalizePeriod(mixed $period): string
    {
        $period = strtolower(trim((string) $period));

        return in_array($period, ['recent_7d', 'recent_30d', 'all'], true) ? $period : 'recent_30d';
    }

    protected function applyPeriod(\Illuminate\Database\Eloquent\Builder $query, string $period): void
    {
        if ($period === 'all') {
            return;
        }

        $days = $period === 'recent_7d' ? 7 : 30;

        $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * @return Collection<int, array{field_key:string,field_name:string,field_type:string,value:string}>
     */
    protected function extractSubmissionFields(FormData $submission): Collection
    {
        $rows = collect();
        $seenKeys = [];

        $fieldValues = $submission->relationLoaded('formDataValues')
            ? $submission->formDataValues
            : $submission->formDataValues()->get();

        foreach ($fieldValues as $fieldValue) {
            $fieldKey = strtolower((string) ($fieldValue->field_key ?? ''));

            if ($this->isCaptchaField($fieldKey, (string) ($fieldValue->field_name ?? ''))) {
                continue;
            }

            $rows->push([
                'field_key' => $fieldKey,
                'field_name' => (string) ($fieldValue->field_name ?: Str::headline((string) ($fieldValue->field_key ?: 'Field'))),
                'field_type' => strtolower((string) ($fieldValue->field_type ?? 'text')),
                'value' => $this->stringifyFieldValue($fieldValue->field_value_json ?: $fieldValue->field_value),
            ]);

            if ($fieldKey !== '') {
                $seenKeys[$fieldKey] = true;
            }
        }

        foreach ($this->decodeLegacyValues((string) ($submission->form_values ?? '')) as $legacyKey => $legacyValue) {
            $normalizedKey = strtolower((string) $legacyKey);

            if ($normalizedKey === '' || isset($seenKeys[$normalizedKey]) || $this->isCaptchaField($normalizedKey, $normalizedKey)) {
                continue;
            }

            $rows->push([
                'field_key' => $normalizedKey,
                'field_name' => Str::headline((string) $legacyKey),
                'field_type' => 'text',
                'value' => $this->stringifyFieldValue($legacyValue),
            ]);
        }

        return $rows->values();
    }

    /**
     * @return array<string, mixed>
     */
    protected function decodeLegacyValues(string $formValues): array
    {
        if ($formValues === '') {
            return [];
        }

        $decoded = json_decode($formValues, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        $decoded = json_decode(html_entity_decode($formValues), true);

        return is_array($decoded) ? $decoded : [];
    }

    protected function stringifyFieldValue(mixed $value): string
    {
        if (is_array($value)) {
            return collect($value)
                ->map(fn (mixed $item): string => is_scalar($item)
                    ? trim((string) $item)
                    : (json_encode($item, JSON_UNESCAPED_SLASHES) ?: ''))
                ->filter()
                ->implode(', ');
        }

        if (is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_SLASHES) ?: '';
        }

        return trim((string) $value);
    }

    protected function maskFieldValue(string $value, string $fieldKey, string $fieldType, bool $detailed = false): string
    {
        if ($value === '') {
            return 'Empty';
        }

        $normalizedKey = strtolower($fieldKey);
        $normalizedType = strtolower($fieldType);

        if ($this->isEmailField($normalizedKey, $normalizedType)) {
            return $this->maskEmail($value);
        }

        if ($this->isPhoneField($normalizedKey, $normalizedType)) {
            return $this->maskPhone($value);
        }

        if ($this->isNameField($normalizedKey, $normalizedType)) {
            return $detailed ? $this->maskNameDetailed($value) : $this->maskNameCompact($value);
        }

        if ($this->isAttachmentField($normalizedKey, $normalizedType)) {
            return $this->maskAttachment($value);
        }

        if ($this->isMessageField($normalizedKey, $normalizedType)) {
            return Str::limit(strip_tags($value), $detailed ? 500 : 100);
        }

        return Str::limit(strip_tags($value), $detailed ? 180 : 80);
    }

    protected function maskEmail(string $email): string
    {
        if (! str_contains($email, '@')) {
            return 'Hidden';
        }

        [$local, $domain] = explode('@', $email, 2);
        $visible = Str::substr($local, 0, 2);

        if ($visible === '') {
            $visible = '*';
        }

        return $visible . str_repeat('*', max(2, strlen($local) - strlen($visible))) . '@' . $domain;
    }

    protected function maskPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (strlen($digits) < 4) {
            return 'Hidden';
        }

        return '***-***-' . substr($digits, -4);
    }

    protected function maskIp(?string $ip): string
    {
        $ip = trim((string) $ip);

        if ($ip === '') {
            return 'Unknown';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $segments = explode('.', $ip);

            return $segments[0] . '.' . $segments[1] . '.x.x';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $segments = explode(':', $ip);

            return implode(':', array_slice($segments, 0, 2)) . '::';
        }

        return 'Hidden';
    }

    protected function maskAttachment(string $value): string
    {
        return '[file] ' . basename($value);
    }

    protected function maskNameCompact(string $value): string
    {
        $parts = preg_split('/\s+/', trim($value)) ?: [];

        if ($parts === []) {
            return 'Unknown';
        }

        return collect($parts)
            ->filter()
            ->map(fn (string $part): string => Str::substr($part, 0, 1) . '.')
            ->implode(' ');
    }

    protected function maskNameDetailed(string $value): string
    {
        $parts = preg_split('/\s+/', trim($value)) ?: [];

        if ($parts === []) {
            return 'Unknown';
        }

        return collect($parts)
            ->filter()
            ->map(function (string $part): string {
                $first = Str::substr($part, 0, 1);
                $tail = Str::substr($part, -1);

                if (strlen($part) <= 2) {
                    return $first . '*';
                }

                return $first . str_repeat('*', max(1, strlen($part) - 2)) . $tail;
            })
            ->implode(' ');
    }

    protected function firstFieldValue(Collection $fields, callable $matcher): ?array
    {
        /** @var array|null $field */
        $field = $fields->first(fn (array $field): bool => $matcher($field));

        return $field;
    }

    protected function formDescriptorForSubmission(FormData $submission, Collection $formsByListId, Collection $formsByModuleId): string
    {
        $listMatch = $submission->list_id ? $formsByListId->get((int) $submission->list_id) : null;

        if ($listMatch instanceof ContactForm) {
            return '#' . $listMatch->id . ' ' . ($listMatch->name ?: 'Untitled form');
        }

        $moduleKey = trim((string) $submission->rel_id);
        $moduleMatch = $moduleKey !== '' ? $formsByModuleId->get($moduleKey) : null;

        if ($moduleMatch instanceof ContactForm) {
            return '#' . $moduleMatch->id . ' ' . ($moduleMatch->name ?: 'Untitled form');
        }

        if ($submission->relationLoaded('formList') && $submission->formList instanceof FormList) {
            return 'List ' . $submission->formList->title;
        }

        if ((string) $submission->module_name !== '') {
            return (string) $submission->module_name;
        }

        return 'Form submission';
    }

    protected function countRecipients(?string $emailsNotifications): int
    {
        return collect(explode(',', (string) $emailsNotifications))
            ->map(fn (string $email): string => trim($email))
            ->filter()
            ->count();
    }

    protected function isEmailField(string $fieldKey, string $fieldType): bool
    {
        return Str::contains($fieldKey, 'email') || $fieldType === 'email';
    }

    protected function isPhoneField(string $fieldKey, string $fieldType): bool
    {
        return Str::contains($fieldKey, ['phone', 'telephone', 'mobile']) || $fieldType === 'phone';
    }

    protected function isNameField(string $fieldKey, string $fieldType): bool
    {
        return $fieldType === 'name' || Str::contains($fieldKey, ['name', 'first_name', 'last_name']);
    }

    protected function isMessageField(string $fieldKey, string $fieldType): bool
    {
        return in_array($fieldType, ['textarea', 'editor'], true) || Str::contains($fieldKey, ['message', 'subject', 'notes', 'comment']);
    }

    protected function isAttachmentField(string $fieldKey, string $fieldType): bool
    {
        return in_array($fieldType, ['file', 'upload'], true) || Str::contains($fieldKey, ['file', 'attachment', 'upload']);
    }

    protected function isCaptchaField(string $fieldKey, string $fieldName): bool
    {
        return Str::contains(strtolower($fieldKey . ' ' . $fieldName), 'captcha');
    }
}
