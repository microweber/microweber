<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Illuminate\Support\Collection;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class AnalyticsTrafficReferrersTool extends AbstractAnalyticsTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'analytics_traffic_referrers',
            'Summarize traffic referrers by domain and top path without exposing raw external URLs.'
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
                description: 'Maximum number of referrer domains to return (1-50). Default is 10.',
                required: false,
            ),
            new ToolProperty(
                name: 'include_internal',
                type: PropertyType::STRING,
                description: 'Set to "yes" to include internal referrers. Default is "no".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $period = $this->normalizePeriod($args['period'] ?? 'daily');
        $limit = $this->safeLimit($args['limit'] ?? 10);
        $includeInternal = in_array(strtolower((string) ($args['include_internal'] ?? 'no')), ['1', 'true', 'yes'], true);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view analytics.');
        }

        try {
            $referrers = collect((array) $this->statsService()->get_stats_items([
                'period' => $period,
                'return' => 'referrers_list',
            ]));

            if (! $includeInternal) {
                $referrers = $referrers->filter(fn (array $item): bool => (int) ($item['is_internal'] ?? 0) === 0);
            }

            $referrers = $referrers->take($limit)->values();

            if ($referrers->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'domain' => 'Domain',
                        'sessions' => 'Sessions',
                    ],
                    'No traffic referrer data is available for the selected period.',
                    'analytics-traffic-referrers-empty'
                );
            }

            $totalSessions = max(1, (int) $referrers->sum(fn (array $item): int => (int) ($item['sessions_count'] ?? 0)));

            $rows = $referrers->map(function (array $item, int $index) use ($totalSessions): array {
                $topPath = collect((array) ($item['referrer_paths'] ?? []))->first();

                return [
                    'rank' => '#' . ($index + 1),
                    'domain' => (string) ($item['referrer_domain'] ?? 'Direct / unknown'),
                    'source' => (int) ($item['is_internal'] ?? 0) === 1 ? 'Internal' : 'External',
                    'sessions' => (string) ($item['sessions_count'] ?? 0),
                    'traffic_share' => $this->percent((int) ($item['sessions_count'] ?? 0), $totalSessions),
                    'top_path' => (string) ($topPath['referrer_path'] ?? '/'),
                    'top_path_sessions' => isset($topPath['path_sessions_count']) ? (string) $topPath['path_sessions_count'] : '0',
                ];
            })->all();

            return $this->formatAsHtmlTable(
                $rows,
                [
                    'rank' => 'Rank',
                    'domain' => 'Referrer domain',
                    'source' => 'Source',
                    'sessions' => 'Sessions',
                    'traffic_share' => 'Traffic share',
                    'top_path' => 'Top path',
                    'top_path_sessions' => 'Path sessions',
                ],
                '',
                'analytics-traffic-referrers-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error building traffic referrer analytics: ' . $exception->getMessage());
        }
    }
}
