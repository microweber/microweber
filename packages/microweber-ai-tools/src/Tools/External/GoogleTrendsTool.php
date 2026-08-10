<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Tools\External;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\AiTools\Services\GoogleTrendsService;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;
use Illuminate\Support\Facades\Log;

/**
 * Google Trends Tool
 *
 * Get trending search queries, real-time trends, daily trends, and
 * generate content ideas based on Google Trends data.
 */
class GoogleTrendsTool extends BaseTool
{
    protected string $domain = 'trends';
    protected array $requiredPermissions = ['view trends'];

    /**
     * @param array<string, mixed> $dependencies
     */
    public function __construct(array $dependencies = [])
    {
        parent::__construct(
            'google_trends',
            'Get trending search queries, real-time trends, daily trends, and generate content ideas based on Google Trends data. Perfect for content creation and SEO strategy.',
            $dependencies
        );
    }

    /**
     * @return array<string|int, mixed>
     */
    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'action',
                type: PropertyType::STRING,
                description: 'Action to perform. Options: "trending_queries" (get trending queries for a keyword), "daily_trends" (get daily search trends), "realtime_trends" (get real-time trending searches), "product_queries" (generate product-focused queries from trends)',
                required: true,
            ),
            new ToolProperty(
                name: 'keyword',
                type: PropertyType::STRING,
                description: 'Keyword to analyze (required for "trending_queries" and "product_queries" actions). For example: "AI", "technology", "fitness", etc.',
                required: false,
            ),
            new ToolProperty(
                name: 'geo',
                type: PropertyType::STRING,
                description: 'Geographic location for trends. Examples: "US", "GB", "DE", "JP", "worldwide". Default is "US".',
                required: false,
            ),
            new ToolProperty(
                name: 'time_range',
                type: PropertyType::STRING,
                description: 'Time range for trends. Options: "now 1-H", "now 4-H", "now 1-d", "now 7-d", "today 1-m", "today 3-m", "today 12-m", "today 5-y", "all". Default is "today 12-m".',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of results to return (1-50). Default is 20.',
                required: false,
            ),
            new ToolProperty(
                name: 'product_categories',
                type: PropertyType::STRING,
                description: 'Comma-separated product categories for product query generation (only for "product_queries" action). Examples: "electronics,gadgets,tech"',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters - args could be passed as an associative array
        $params = is_array($args[0] ?? null) ? $args[0] : $args;
        $action = $params['action'] ?? '';
        $keyword = $params['keyword'] ?? '';
        $geo = $params['geo'] ?? 'US';
        $time_range = $params['time_range'] ?? 'today 12-m';
        $limit = $params['limit'] ?? 20;
        $product_categories = $params['product_categories'] ?? '';

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to access Google Trends data.');
        }

        if (empty($action)) {
            return $this->handleError('Action parameter is required. Choose from: trending_queries, daily_trends, realtime_trends, product_queries.');
        }

        // Validate limit
        $limit = max(1, min(50, (int)$limit));

        try {
            $trendsService = new GoogleTrendsService([
                'geo' => $geo,
                'time' => $time_range,
                'hl' => 'en-US'
            ]);

            switch ($action) {
                case 'trending_queries':
                    return $this->handleTrendingQueries($trendsService, $keyword, $limit);

                case 'daily_trends':
                    return $this->handleDailyTrends($trendsService, $limit);

                case 'realtime_trends':
                    return $this->handleRealtimeTrends($trendsService, $limit);

                case 'product_queries':
                    return $this->handleProductQueries($trendsService, $keyword, $product_categories, $limit);

                default:
                    return $this->handleError("Unknown action: {$action}. Available actions: trending_queries, daily_trends, realtime_trends, product_queries.");
            }

        } catch (\Exception $e) {
            return $this->handleError('Error accessing Google Trends: ' . $e->getMessage());
        }
    }

    protected function handleTrendingQueries(GoogleTrendsService $service, string $keyword, int $limit): string
    {
        if (empty($keyword)) {
            return $this->handleError('Keyword is required for trending queries action.');
        }

        $trendingQueries = $service->getTrendingQueries($keyword);

        if (empty($trendingQueries)) {
            return $this->formatNoResultsMessage('trending queries', $keyword);
        }

        $limitedQueries = array_slice($trendingQueries, 0, $limit);

        return $this->formatTrendingQueriesHtml($limitedQueries, $keyword, $service->getOptions());
    }

    protected function handleDailyTrends(GoogleTrendsService $service, int $limit): string
    {
        $dailyTrends = $service->getDailySearchTrends();

        if (empty($dailyTrends)) {
            return $this->formatNoResultsMessage('daily trends');
        }

        return $this->formatDailyTrendsHtml($dailyTrends, $limit, $service->getOptions());
    }

    protected function handleRealtimeTrends(GoogleTrendsService $service, int $limit): string
    {
        $realtimeTrends = $service->getRealTimeSearchTrends();

        if (empty($realtimeTrends)) {
            return $this->formatNoResultsMessage('real-time trends');
        }

        return $this->formatRealtimeTrendsHtml($realtimeTrends, $limit, $service->getOptions());
    }

    protected function handleProductQueries(GoogleTrendsService $service, string $keyword, string $product_categories, int $limit): string
    {
        if (empty($keyword)) {
            return $this->handleError('Keyword is required for product queries action.');
        }

        // Get trending queries first
        $trendingQueries = $service->getTrendingQueries($keyword);

        if (empty($trendingQueries)) {
            return $this->formatNoResultsMessage('product queries', $keyword);
        }

        // Parse product categories
        $categories = array_filter(array_map('trim', explode(',', $product_categories)));

        // Generate product queries
        $productQueries = $service->generateProductQueries($trendingQueries, $categories);

        if (empty($productQueries)) {
            return $this->formatNoResultsMessage('product queries', $keyword);
        }

        $limitedQueries = array_slice($productQueries, 0, $limit);

        return $this->formatProductQueriesHtml($limitedQueries, $keyword, $categories, $service->getOptions());
    }

    /**
     * @param array<int|string, mixed> $queries
 * @param array<string|int, mixed> $options
 */
    protected function formatTrendingQueriesHtml(array $queries, string $keyword, array $options): string
    {
        $geo = $options['geo'] ?? 'US';
        $timeRange = $options['time'] ?? 'today 12-m';
        $totalQueries = count($queries);

        $header = "
<div class='trends-header mb-4'>
<h4><i class='fas fa-trending-up text-success me-2'></i>Trending Queries for \"{$keyword}\"</h4>
<div class='row'>
<div class='col-md-6'>
<p class='mb-1'><strong>Region:</strong> {$geo}</p>
<p class='mb-1'><strong>Time Range:</strong> {$timeRange}</p>
</div>
<div class='col-md-6'>
<p class='mb-1'><strong>Total Queries:</strong> {$totalQueries}</p>
<p class='mb-1'><strong>Generated:</strong> " . now()->format('M j, Y H:i') . "</p>
</div>
</div>
</div>";

        $cards = "<div class='row'>";

        foreach ($queries as $index => $query) {
            $rank = (int) $index + 1;
            $queryText = htmlspecialchars($query['query']);
            $value = $query['value'] ?? 0;
            $type = ucfirst($query['type'] ?? 'suggestion');
            $source = ucfirst($query['source'] ?? 'autocomplete');
            $relevanceScore = round(($query['relevance_score'] ?? 0) * 100, 1);

            $typeColor = match($query['type'] ?? 'suggestion') {
                'rising' => 'bg-danger',
                'top' => 'bg-primary',
                default => 'bg-secondary'
            };

            $cards .= "
<div class='col-md-6 col-lg-4 mb-3'>
<div class='card h-100 trend-card'>
<div class='card-header d-flex justify-content-between align-items-center'>
<span class='badge {$typeColor}'>{$type}</span>
<small class='text-muted'>#{$rank}</small>
</div>
<div class='card-body'>
<h6 class='card-title'>{$queryText}</h6>
<div class='trend-stats'>
<p class='mb-1'><strong>Value:</strong> " . number_format($value) . "</p>
<p class='mb-1'><strong>Relevance:</strong> {$relevanceScore}%</p>
<p class='mb-0'><small class='text-muted'>Source: {$source}</small></p>
</div>
</div>
</div>
</div>";
        }

        $cards .= "</div>";

        $footer = "
<div class='trends-footer mt-4'>
<div class='alert alert-info'>
<h6><i class='fas fa-lightbulb me-2'></i>Content Ideas</h6>
<p class='mb-0'>Use these trending queries to create relevant content, optimize SEO keywords, or develop targeted marketing campaigns. Rising trends indicate growing interest, while top trends show consistent popularity.</p>
</div>
</div>";

        return $header . $cards . $footer;
    }

    /**
     * @param array<int|string, mixed> $trends
 * @param array<string|int, mixed> $options
 */
    protected function formatDailyTrendsHtml(array $trends, int $limit, array $options): string
    {
        $geo = $options['geo'] ?? 'US';

        $header = "
<div class='trends-header mb-4'>
<h4><i class='fas fa-calendar-day text-primary me-2'></i>Daily Search Trends</h4>
<div class='row'>
<div class='col-md-6'>
<p class='mb-1'><strong>Region:</strong> {$geo}</p>
<p class='mb-1'><strong>Date:</strong> " . now()->format('M j, Y') . "</p>
</div>
<div class='col-md-6'>
<p class='mb-1'><strong>Showing:</strong> Top {$limit} trends</p>
<p class='mb-1'><strong>Updated:</strong> " . now()->format('H:i') . "</p>
</div>
</div>
</div>";

        $trendsList = "<div class='daily-trends-list'>";

        if (isset($trends['trendingSearchesDays'])) {
            $trendingDay = $trends['trendingSearchesDays'][0] ?? [];
            $trendingSearches = $trendingDay['trendingSearches'] ?? [];

            $counter = 0;
            foreach ($trendingSearches as $trend) {
                if ($counter >= $limit) break;

                $title = htmlspecialchars($trend['title']['query'] ?? 'Unknown');
                $formattedTraffic = $trend['formattedTraffic'] ?? 'N/A';
                $articles = $trend['articles'] ?? [];

                $rank = $counter + 1;
                $trendsList .= "
<div class='card mb-3'>
<div class='card-header'>
<h5 class='mb-0'>#{$rank} {$title}</h5>
<small class='text-muted'>Traffic: {$formattedTraffic}</small>
</div>";

                if (!empty($articles)) {
                    $trendsList .= "<div class='card-body'><div class='row'>";

                    foreach (array_slice($articles, 0, 3) as $article) {
                        $articleTitle = htmlspecialchars($article['title'] ?? 'No title');
                        $source = htmlspecialchars($article['source'] ?? 'Unknown source');
                        $snippet = htmlspecialchars($article['snippet'] ?? '');

                        $trendsList .= "
<div class='col-md-4 mb-2'>
<h6 class='text-primary'>{$articleTitle}</h6>
<small class='text-muted'>Source: {$source}</small>
" . ($snippet ? "<p class='small mt-1'>{$snippet}</p>" : '') . "
</div>";
                    }

                    $trendsList .= "</div></div>";
                }

                $trendsList .= "</div>";
                $counter++;
            }
        }

        $trendsList .= "</div>";

        return $header . $trendsList;
    }

    /**
     * @param array<int|string, mixed> $trends
 * @param array<string|int, mixed> $options
 */
    protected function formatRealtimeTrendsHtml(array $trends, int $limit, array $options): string
    {
        $geo = $options['geo'] ?? 'US';

        $header = "
<div class='trends-header mb-4'>
<h4><i class='fas fa-bolt text-warning me-2'></i>Real-time Search Trends</h4>
<div class='row'>
<div class='col-md-6'>
<p class='mb-1'><strong>Region:</strong> {$geo}</p>
<p class='mb-1'><strong>Updated:</strong> " . now()->format('M j, Y H:i') . "</p>
</div>
<div class='col-md-6'>
<p class='mb-1'><strong>Showing:</strong> Top {$limit} trends</p>
<p class='mb-1'><strong>Status:</strong> <span class='badge bg-success'>Live</span></p>
</div>
</div>
</div>";

        $trendsList = "<div class='realtime-trends-list'>";

        if (isset($trends['storySummaries']['trendingStories'])) {
            $stories = array_slice($trends['storySummaries']['trendingStories'], 0, $limit);

            foreach ($stories as $index => $story) {
                $rank = (int) $index + 1;
                $title = htmlspecialchars($story['title'] ?? 'Unknown');
                $entityNames = $story['entityNames'] ?? [];
                $articles = $story['articles'] ?? [];

                $trendsList .= "
<div class='card mb-3 realtime-card'>
<div class='card-header d-flex justify-content-between'>
<h5 class='mb-0'>#{$rank} {$title}</h5>
<span class='badge bg-warning'>Trending Now</span>
</div>
<div class='card-body'>";

                if (!empty($entityNames)) {
                    $trendsList .= "<p><strong>Related:</strong> " . implode(', ', array_map('htmlspecialchars', $entityNames)) . "</p>";
                }

                if (!empty($articles)) {
                    $trendsList .= "<div class='articles-list'>";
                    foreach (array_slice($articles, 0, 2) as $article) {
                        $articleTitle = htmlspecialchars($article['title'] ?? 'No title');
                        $source = htmlspecialchars($article['source'] ?? 'Unknown');
                        $timeAgo = $article['timeAgo'] ?? '';

                        $trendsList .= "
<div class='article-item mb-2 p-2 bg-light rounded'>
<h6 class='text-primary mb-1'>{$articleTitle}</h6>
<small class='text-muted'>{$source}" . ($timeAgo ? " • {$timeAgo}" : '') . "</small>
</div>";
                    }
                    $trendsList .= "</div>";
                }

                $trendsList .= "</div></div>";
            }
        }

        $trendsList .= "</div>";

        return $header . $trendsList;
    }

    /**
     * @param array<int|string, mixed> $queries
 * @param array<string|int, mixed> $categories
 * @param array<string|int, mixed> $options
 */
    protected function formatProductQueriesHtml(array $queries, string $keyword, array $categories, array $options): string
    {
        $geo = $options['geo'] ?? 'US';
        $totalQueries = count($queries);
        $categoriesText = !empty($categories) ? implode(', ', $categories) : 'General';

        $header = "
<div class='trends-header mb-4'>
<h4><i class='fas fa-shopping-cart text-info me-2'></i>Product-Focused Trending Queries</h4>
<div class='row'>
<div class='col-md-6'>
<p class='mb-1'><strong>Base Keyword:</strong> {$keyword}</p>
<p class='mb-1'><strong>Categories:</strong> {$categoriesText}</p>
</div>
<div class='col-md-6'>
<p class='mb-1'><strong>Region:</strong> {$geo}</p>
<p class='mb-1'><strong>Total Queries:</strong> {$totalQueries}</p>
</div>
</div>
</div>";

        $cards = "<div class='row'>";

        foreach ($queries as $index => $query) {
            $rank = (int) $index + 1;
            $queryText = htmlspecialchars($query['query']);
            $originalTrend = htmlspecialchars($query['original_trend'] ?? '');
            $isProductFocused = $query['is_product_focused'] ?? false;
            $relevanceScore = round(($query['relevance_score'] ?? 0) * 100, 1);
            $trendValue = number_format($query['trend_value'] ?? 0);

            $focusedBadge = $isProductFocused ?
                "<span class='badge bg-success'>Product-Focused</span>" :
                "<span class='badge bg-secondary'>General</span>";

            $cards .= "
<div class='col-md-6 col-lg-4 mb-3'>
<div class='card h-100 product-query-card'>
<div class='card-header d-flex justify-content-between align-items-center'>
{$focusedBadge}
<small class='text-muted'>#{$rank}</small>
</div>
<div class='card-body'>
<h6 class='card-title'>{$queryText}</h6>
<div class='query-stats'>
<p class='mb-1'><strong>Original:</strong> {$originalTrend}</p>
<p class='mb-1'><strong>Trend Value:</strong> {$trendValue}</p>
<p class='mb-0'><strong>Relevance:</strong> {$relevanceScore}%</p>
</div>
</div>
</div>
</div>";
        }

        $cards .= "</div>";

        $footer = "
<div class='trends-footer mt-4'>
<div class='alert alert-success'>
<h6><i class='fas fa-store me-2'></i>E-commerce Ideas</h6>
<p class='mb-0'>These product-focused queries are perfect for creating product descriptions, e-commerce content, shopping guides, and targeted product marketing campaigns. Focus on the product-focused queries for better conversion potential.</p>
</div>
</div>";

        return $header . $cards . $footer;
    }

    protected function formatNoResultsMessage(string $type, ?string $keyword = null): string
    {
        $keywordText = $keyword ? " for \"{$keyword}\"" : '';
        return $this->handleError("No {$type} data found{$keywordText}. This could be due to API limitations, regional restrictions, or low search volume. Try a different keyword or geographic region.");
    }
}
