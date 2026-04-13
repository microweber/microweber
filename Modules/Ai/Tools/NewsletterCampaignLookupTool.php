<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Illuminate\Support\Str;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class NewsletterCampaignLookupTool extends BaseTool
{
    protected string $domain = 'newsletter';

    protected array $requiredPermissions = ['view newsletters'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'newsletter_campaign_lookup',
            'Search newsletter campaigns by name, subject, status, and campaign type.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'campaign_id',
                type: PropertyType::INTEGER,
                description: 'Optional campaign ID for a single-campaign lookup.',
                required: false,
            ),
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional search term for campaign name or subject.',
                required: false,
            ),
            new ToolProperty(
                name: 'status',
                type: PropertyType::STRING,
                description: 'Optional campaign status filter such as "draft", "scheduled", "queued", "finished", or "failed".',
                required: false,
            ),
            new ToolProperty(
                name: 'campaign_type',
                type: PropertyType::STRING,
                description: 'Optional campaign type filter such as "broadcast", "triggered", or "automation".',
                required: false,
            ),
            new ToolProperty(
                name: 'trigger_event',
                type: PropertyType::STRING,
                description: 'Optional trigger event filter for triggered or automation campaigns.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of campaigns to return (1-50). Default is 20.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $campaignId = isset($args['campaign_id']) ? (int) $args['campaign_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $status = trim((string) ($args['status'] ?? ''));
        $campaignType = trim((string) ($args['campaign_type'] ?? ''));
        $triggerEvent = trim((string) ($args['trigger_event'] ?? ''));
        $limit = max(1, min(50, (int) ($args['limit'] ?? 20)));

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view newsletter campaigns.');
        }

        try {
            $query = NewsletterCampaign::query()->with('list');

            if ($campaignId !== null && $campaignId > 0) {
                $query->where('id', $campaignId);
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('subject', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($status !== '') {
                $query->where('status', $status);
            }

            if ($campaignType !== '') {
                $query->where('campaign_type', $campaignType);
            }

            if ($triggerEvent !== '') {
                $query->where('trigger_event', $triggerEvent);
            }

            $campaigns = $query
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();

            if ($campaigns->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'campaign' => 'Campaign',
                        'status' => 'Status',
                        'type' => 'Type',
                    ],
                    'No newsletter campaigns matched the current filters.',
                    'newsletter-campaign-lookup-empty'
                );
            }

            $campaignIds = $campaigns->pluck('id');
            $openCounts = NewsletterCampaignPixel::query()
                ->selectRaw('campaign_id, COUNT(*) AS aggregate_count')
                ->whereIn('campaign_id', $campaignIds)
                ->groupBy('campaign_id')
                ->pluck('aggregate_count', 'campaign_id');
            $clickCounts = NewsletterCampaignClickedLink::query()
                ->selectRaw('campaign_id, COUNT(*) AS aggregate_count')
                ->whereIn('campaign_id', $campaignIds)
                ->groupBy('campaign_id')
                ->pluck('aggregate_count', 'campaign_id');
            $sentCounts = NewsletterCampaignsSendLog::query()
                ->selectRaw('campaign_id, COUNT(*) AS aggregate_count')
                ->whereIn('campaign_id', $campaignIds)
                ->where('is_sent', 1)
                ->groupBy('campaign_id')
                ->pluck('aggregate_count', 'campaign_id');

            $tableRows = [];

            foreach ($campaigns as $campaign) {
                $tableRows[] = [
                    'campaign' => '#' . $campaign->id . ' ' . $campaign->name,
                    'subject' => Str::limit((string) $campaign->subject, 60),
                    'status' => (string) $campaign->status,
                    'type' => (string) $campaign->campaign_type . ($campaign->trigger_event ? ' / ' . $campaign->trigger_event : ''),
                    'audience' => $campaign->recipients_from === 'specific_list'
                        ? (($campaign->list?->name ?? 'Specific list') . ' (' . $campaign->countSubscribers() . ')')
                        : 'All subscribers (' . $campaign->countSubscribers() . ')',
                    'engagement' => 'sent ' . (int) ($sentCounts[$campaign->id] ?? 0)
                        . ', opened ' . (int) ($openCounts[$campaign->id] ?? 0)
                        . ', clicked ' . (int) ($clickCounts[$campaign->id] ?? 0),
                    'schedule' => $campaign->scheduled_at
                        ? $campaign->scheduled_at . ($campaign->scheduled_timezone ? ' ' . $campaign->scheduled_timezone : '')
                        : 'Not scheduled',
                ];
            }

            return $this->formatAsHtmlTable(
                $tableRows,
                [
                    'campaign' => 'Campaign',
                    'subject' => 'Subject',
                    'status' => 'Status',
                    'type' => 'Type',
                    'audience' => 'Audience',
                    'engagement' => 'Engagement',
                    'schedule' => 'Schedule',
                ],
                '',
                'newsletter-campaign-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error looking up newsletter campaigns: ' . $exception->getMessage());
        }
    }
}
