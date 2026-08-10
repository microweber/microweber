<?php

declare(strict_types=1);

namespace Modules\SiteStats\Tools;

use Illuminate\Support\Collection;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class AnalyticsTopPagesTool extends AbstractAnalyticsTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'analytics_top_pages',
            'List the top Microweber pages or content items by sessions and views.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'period',
                type: PropertyType::STRING,
                description: 'Reporting period: daily, weekly, monthly, or yearly. Default is daily.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of pages to return (1-50). Default is 10.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $period = $this->normalizePeriod($args['period'] ?? 'daily');
        $limit = $this->safeLimit($args['limit'] ?? 10);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view analytics.');
        }

        try {
            $pages = collect((array) $this->statsService()->get_stats_items([
                'period' => $period,
                'return' => 'content_list',
            ]))->take($limit)->values();

            if ($pages->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'page' => 'Page',
                        'sessions' => 'Sessions',
                    ],
                    'No analytics page data is available for the selected period.',
                    'analytics-top-pages-empty'
                );
            }

            $totalSessions = max(1, (int) $pages->sum(fn (array $item): int => (int) ($item['sessions_count'] ?? 0)));

            $rows = $pages->map(function (array $item, int $index) use ($totalSessions): array {
                $pageTitle = $item['content_title'] ?? null;
                $urlSlug = $item['url_slug'] ?? '/';
                $path = str_starts_with((string) $urlSlug, '/') || str_starts_with((string) $urlSlug, 'http')
                    ? (string) $urlSlug
                    : '/' . ltrim((string) $urlSlug, '/');

                return [
                    'rank' => '#' . ($index + 1),
                    'page' => (string) ($pageTitle ?: $urlSlug),
                    'path' => $path,
                    'sessions' => (string) ($item['sessions_count'] ?? 0),
                    'traffic_share' => $this->percent((int) ($item['sessions_count'] ?? 0), $totalSessions),
                    'views' => (string) ($item['view_count_sum'] ?? 0),
                    'content_id' => isset($item['content_id']) && $item['content_id'] ? (string) $item['content_id'] : 'n/a',
                ];
            })->all();

            return $this->formatAsHtmlTable(
                $rows,
                [
                    'rank' => 'Rank',
                    'page' => 'Page',
                    'path' => 'Path',
                    'sessions' => 'Sessions',
                    'traffic_share' => 'Traffic share',
                    'views' => 'Views',
                    'content_id' => 'Content ID',
                ],
                '',
                'analytics-top-pages-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error building top pages analytics: ' . $exception->getMessage());
        }
    }
}
