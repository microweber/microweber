<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class AnalyticsTrafficSummaryTool extends AbstractAnalyticsTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'analytics_traffic_summary',
            'Summarize Microweber traffic metrics such as visitors, views, bounce rate, average session duration, and top traffic sources.'
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
        ];
    }

    public function __invoke(...$args): string
    {
        $period = $this->normalizePeriod($args['period'] ?? 'daily');

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view analytics.');
        }

        try {
            $range = $this->defaultDateRange($period);
            $repository = $this->statsRepository();
            $stats = $this->statsService();

            $visitors = array_sum($repository->getSessionsForPeriod($range['startDate'], $range['endDate'], $period));
            $views = array_sum($repository->getSessionsForPeriod($range['startDate'], $range['endDate'], $period, 'views'));
            $bounced = array_sum($repository->getBouncedSessionsForPeriod($range['startDate'], $range['endDate'], $period));
            $avgDurations = $repository->getAvgTimeOnSiteForSessionsForPeriod($range['startDate'], $range['endDate'], $period);
            $avgDuration = $avgDurations !== [] ? array_sum($avgDurations) / count($avgDurations) : 0.0;

            $topPage = collect((array) $stats->get_stats_items([
                'period' => $period,
                'return' => 'content_list',
            ]))->first();

            $topReferrer = collect((array) $stats->get_stats_items([
                'period' => $period,
                'return' => 'referrers_list',
            ]))->firstWhere('is_internal', 0) ?? collect((array) $stats->get_stats_items([
                'period' => $period,
                'return' => 'referrers_list',
            ]))->first();

            $tableRows = [[
                'period' => ucfirst($period),
                'range' => $range['startDate']->toDateString() . ' to ' . $range['endDate']->toDateString(),
                'visitors' => (string) $visitors,
                'pageviews' => (string) $views,
                'bounce_rate' => $this->percent($bounced, $visitors),
                'avg_session_minutes' => number_format($avgDuration, 1) . ' min',
                'users_online' => (string) ($stats->get_stats_count(['return' => 'users_online']) ?? 0),
                'top_page' => (string) (($topPage['content_title'] ?? $topPage['url_slug'] ?? 'None')),
                'top_referrer' => (string) (($topReferrer['referrer_domain'] ?? 'Direct / unknown')),
            ]];

            return $this->formatAsHtmlTable(
                $tableRows,
                [
                    'period' => 'Period',
                    'range' => 'Date range',
                    'visitors' => 'Visitors',
                    'pageviews' => 'Pageviews',
                    'bounce_rate' => 'Bounce rate',
                    'avg_session_minutes' => 'Avg session',
                    'users_online' => 'Users online',
                    'top_page' => 'Top page',
                    'top_referrer' => 'Top referrer',
                ],
                '',
                'analytics-traffic-summary-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error building analytics traffic summary: ' . $exception->getMessage());
        }
    }
}
