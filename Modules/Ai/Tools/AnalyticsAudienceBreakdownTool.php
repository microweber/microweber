<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\SiteStats\Models\Sessions;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class AnalyticsAudienceBreakdownTool extends AbstractAnalyticsTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'analytics_audience_breakdown',
            'Break down analytics audiences by country and device type using aggregated session data.'
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
                description: 'Maximum number of country rows to return (1-50). Default is 10.',
                required: false,
            ),
            new ToolProperty(
                name: 'breakdown',
                type: PropertyType::STRING,
                description: 'Choose "countries", "devices", or "both". Default is both.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $period = $this->normalizePeriod($args['period'] ?? 'daily');
        $limit = $this->safeLimit($args['limit'] ?? 10);
        $breakdown = strtolower(trim((string) ($args['breakdown'] ?? 'both')));

        if (! in_array($breakdown, ['countries', 'devices', 'both'], true)) {
            $breakdown = 'both';
        }

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view analytics.');
        }

        try {
            $sections = [];

            if (in_array($breakdown, ['countries', 'both'], true)) {
                $countries = collect((array) $this->statsService()->get_stats_items([
                    'period' => $period,
                    'return' => 'locations_list',
                ]))->take($limit)->values();

                $countryTotal = max(1, (int) $countries->sum(fn (array $item): int => (int) ($item['sessions_count'] ?? 0)));

                $countryRows = $countries->map(function (array $item, int $index) use ($countryTotal): array {
                    return [
                        'rank' => '#' . ($index + 1),
                        'country' => trim((string) (($item['country_name'] ?? 'Unknown') . ' (' . ($item['country_code'] ?? '--') . ')')),
                        'sessions' => (string) ($item['sessions_count'] ?? 0),
                        'traffic_share' => $this->percent((int) ($item['sessions_count'] ?? 0), $countryTotal),
                    ];
                })->all();

                $sections[] = '<h4>Audience by country</h4>' . $this->formatAsHtmlTable(
                    $countryRows,
                    [
                        'rank' => 'Rank',
                        'country' => 'Country',
                        'sessions' => 'Sessions',
                        'traffic_share' => 'Traffic share',
                    ],
                    'No country analytics are available for the selected period.',
                    'analytics-audience-countries-results'
                );
            }

            if (in_array($breakdown, ['devices', 'both'], true)) {
                $deviceRows = Sessions::query()
                    ->period($period, 'stats_sessions')
                    ->join('stats_browser_agents', 'stats_sessions.browser_id', '=', 'stats_browser_agents.id')
                    ->select(
                        'stats_browser_agents.is_desktop',
                        'stats_browser_agents.is_mobile',
                        'stats_browser_agents.is_phone',
                        'stats_browser_agents.is_tablet',
                        'stats_browser_agents.is_robot',
                        DB::raw('COUNT(stats_sessions.id) as sessions_count')
                    )
                    ->groupBy(
                        'stats_browser_agents.is_desktop',
                        'stats_browser_agents.is_mobile',
                        'stats_browser_agents.is_phone',
                        'stats_browser_agents.is_tablet',
                        'stats_browser_agents.is_robot'
                    )
                    ->get();

                $deviceTotals = [];

                foreach ($deviceRows as $row) {
                    $deviceType = $this->deviceTypeFromFlags(
                        (int) ($row->is_desktop ?? 0),
                        (int) ($row->is_mobile ?? 0),
                        (int) ($row->is_phone ?? 0),
                        (int) ($row->is_tablet ?? 0),
                        (int) ($row->is_robot ?? 0),
                    );

                    $deviceTotals[$deviceType] = ($deviceTotals[$deviceType] ?? 0) + (int) ($row->sessions_count ?? 0);
                }

                arsort($deviceTotals);
                $deviceTotalCount = max(1, array_sum($deviceTotals));

                $mappedRows = [];
                $rank = 1;
                foreach ($deviceTotals as $deviceType => $count) {
                    $mappedRows[] = [
                        'rank' => '#' . $rank++,
                        'device' => ucfirst($deviceType),
                        'sessions' => (string) $count,
                        'traffic_share' => $this->percent($count, $deviceTotalCount),
                    ];
                }

                $sections[] = '<h4>Audience by device</h4>' . $this->formatAsHtmlTable(
                    $mappedRows,
                    [
                        'rank' => 'Rank',
                        'device' => 'Device type',
                        'sessions' => 'Sessions',
                        'traffic_share' => 'Traffic share',
                    ],
                    'No device analytics are available for the selected period.',
                    'analytics-audience-devices-results'
                );
            }

            return implode('', $sections);
        } catch (\Throwable $exception) {
            return $this->handleError('Error building audience analytics: ' . $exception->getMessage());
        }
    }

    private function deviceTypeFromFlags(int $isDesktop, int $isMobile, int $isPhone, int $isTablet, int $isRobot): string
    {
        if ($isRobot === 1) {
            return 'robot';
        }

        if ($isTablet === 1) {
            return 'tablet';
        }

        if ($isMobile === 1 || $isPhone === 1) {
            return 'mobile';
        }

        if ($isDesktop === 1) {
            return 'desktop';
        }

        return 'other';
    }
}
