<?php

declare(strict_types=1);

namespace Modules\Newsletter\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Illuminate\Support\Str;
use Modules\Newsletter\Models\NewsletterSubscriber;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class NewsletterSubscriberLookupTool extends BaseTool
{
    protected string $domain = 'newsletter';

    protected array $requiredPermissions = ['view newsletters'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'newsletter_subscriber_lookup',
            'Search newsletter subscribers by name, masked email, list membership, or status.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'subscriber_id',
                type: PropertyType::INTEGER,
                description: 'Optional subscriber ID for a single-subscriber lookup.',
                required: false,
            ),
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional search term for subscriber name or email.',
                required: false,
            ),
            new ToolProperty(
                name: 'status',
                type: PropertyType::STRING,
                description: 'Optional subscriber status filter such as "active", "unsubscribed", or "bounced".',
                required: false,
            ),
            new ToolProperty(
                name: 'list_id',
                type: PropertyType::INTEGER,
                description: 'Optional newsletter list ID filter.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of subscribers to return (1-50). Default is 20.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $subscriberId = isset($args['subscriber_id']) ? (int) $args['subscriber_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $status = trim((string) ($args['status'] ?? ''));
        $listId = isset($args['list_id']) ? (int) $args['list_id'] : null;
        $limit = max(1, min(50, (int) ($args['limit'] ?? 20)));

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view newsletter subscribers.');
        }

        try {
            $query = NewsletterSubscriber::query()->with('lists');

            if ($subscriberId !== null && $subscriberId > 0) {
                $query->where('id', $subscriberId);
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($status !== '') {
                $query->where('status', $status);
            }

            if ($listId !== null && $listId > 0) {
                $query->whereHas('lists', function ($builder) use ($listId): void {
                    $builder->where('newsletter_lists.id', $listId);
                });
            }

            $subscribers = $query
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();

            if ($subscribers->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'subscriber' => 'Subscriber',
                        'status' => 'Status',
                        'lists' => 'Lists',
                    ],
                    'No newsletter subscribers matched the current filters.',
                    'newsletter-subscriber-lookup-empty'
                );
            }

            $tableRows = [];

            foreach ($subscribers as $subscriber) {
                $tableRows[] = [
                    'subscriber' => '#' . $subscriber->id . ' ' . ($subscriber->name ?: 'Unnamed subscriber'),
                    'email' => $this->maskEmail((string) $subscriber->email),
                    'status' => (string) ($subscriber->status ?: ($subscriber->is_subscribed ? 'active' : 'inactive')),
                    'lists' => $subscriber->lists->pluck('name')->filter()->implode(', ') ?: 'No lists',
                    'subscribed' => $subscriber->subscribed_at ? (string) $subscriber->subscribed_at : 'Unknown',
                ];
            }

            return $this->formatAsHtmlTable(
                $tableRows,
                [
                    'subscriber' => 'Subscriber',
                    'email' => 'Masked email',
                    'status' => 'Status',
                    'lists' => 'Lists',
                    'subscribed' => 'Subscribed at',
                ],
                '',
                'newsletter-subscriber-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error looking up newsletter subscribers: ' . $exception->getMessage());
        }
    }

    private function maskEmail(string $email): string
    {
        if ($email === '' || ! str_contains($email, '@')) {
            return 'Hidden';
        }

        [$local, $domain] = explode('@', $email, 2);

        $maskedLocal = Str::substr($local, 0, 2);

        if ($maskedLocal === '') {
            $maskedLocal = '*';
        }

        return $maskedLocal . str_repeat('*', max(2, strlen($local) - strlen($maskedLocal))) . '@' . $domain;
    }
}
