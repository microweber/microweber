<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Google Trends Service
 *
 * Fetches trending search queries and data from Google Trends API.
 */
class GoogleTrendsService
{
    private const GENERAL_ENDPOINT = 'https://trends.google.com/trends/api/explore';
    private const INTEREST_OVER_TIME_ENDPOINT = 'https://trends.google.com/trends/api/widgetdata/multiline';
    private const RELATED_QUERIES_ENDPOINT = 'https://trends.google.com/trends/api/widgetdata/relatedsearches';
    private const SUGGESTIONS_AUTOCOMPLETE_ENDPOINT = 'https://trends.google.com/trends/api/autocomplete';
    private const COMPARED_GEO_ENDPOINT = 'https://trends.google.com/trends/api/widgetdata/comparedgeo';
    private const CATEGORIES_ENDPOINT = 'https://trends.google.com/trends/api/explore/pickers/category';
    private const GEO_ENDPOINT = 'https://trends.google.com/trends/api/explore/pickers/geo';
    private const DAILY_SEARCH_TRENDS_ENDPOINT = 'https://trends.google.com/trends/api/dailytrends';
    private const REAL_TIME_SEARCH_TRENDS_ENDPOINT = 'https://trends.google.com/trends/api/realtimetrends';

    private array $options = [
        'hl' => 'en-US',
        'tz' => 0,
        'geo' => 'US',
        'time' => 'today 12-m',
        'category' => 0,
    ];

    private ?array $proxyConfigs = null;

    public function __construct(array $options = [])
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * Get trending search queries for a specific keyword
     */
    public function getTrendingQueries(string $keyword, array $options = []): array
    {
        $cacheKey = "trends_queries_{$keyword}_" . md5(json_encode($options));

        return Cache::remember($cacheKey, 3600, function () use ($keyword, $options) {
            try {
                $localOptions = array_merge($this->options, $options);

                // Get related queries
                $relatedQueries = $this->getRelatedQueries($keyword, $localOptions);

                // Get autocomplete suggestions
                $suggestions = $this->getSuggestionsAutocomplete($keyword);

                // Combine and format results
                $trendingQueries = $this->formatTrendingQueries($relatedQueries, $suggestions, $keyword);

                Log::info("Retrieved trending queries for keyword: {$keyword}", [
                    'queries_count' => count($trendingQueries),
                    'geo' => $localOptions['geo']
                ]);

                return $trendingQueries;

            } catch (\Exception $e) {
                Log::error("Failed to get trending queries for: {$keyword}", [
                    'error' => $e->getMessage()
                ]);
                return [];
            }
        });
    }

    /**
     * Get daily search trends
     */
    public function getDailySearchTrends(int $ns = 15): array
    {
        $cacheKey = "daily_trends_{$this->options['geo']}_{$ns}";

        return Cache::remember($cacheKey, 1800, function () use ($ns) {
            $payload = [
                'hl' => $this->options['hl'],
                'tz' => $this->options['tz'],
                'geo' => $this->options['geo'],
                'ns' => $ns,
            ];

            if ($data = $this->getData(self::DAILY_SEARCH_TRENDS_ENDPOINT, $payload)) {
                $decoded = json_decode(trim(substr($data, 5)), true);
                return $decoded['default'] ?? [];
            }
            return [];
        });
    }

    /**
     * Get real-time search trends
     */
    public function getRealTimeSearchTrends(
        string $cat = 'all',
        int $fi = 0,
        int $fs = 0,
        int $ri = 300,
        int $rs = 20,
        int $sort = 0
    ): array {
        $cacheKey = "realtime_trends_{$this->options['geo']}_{$cat}";

        return Cache::remember($cacheKey, 900, function () use ($cat, $fi, $fs, $ri, $rs, $sort) {
            $payload = [
                'hl' => $this->options['hl'],
                'tz' => $this->options['tz'],
                'cat' => $cat,
                'fi' => $fi,
                'fs' => $fs,
                'geo' => $this->options['geo'],
                'ri' => $ri,
                'rs' => $rs,
                'sort' => $sort,
            ];

            if ($data = $this->getData(self::REAL_TIME_SEARCH_TRENDS_ENDPOINT, $payload)) {
                return json_decode(trim(substr($data, 5)), true);
            }
            return [];
        });
    }

    /**
     * Get autocomplete suggestions for a keyword
     */
    public function getSuggestionsAutocomplete(string $keyword): array
    {
        $cacheKey = "autocomplete_{$keyword}_{$this->options['hl']}";

        return Cache::remember($cacheKey, 7200, function () use ($keyword) {
            $url = self::SUGGESTIONS_AUTOCOMPLETE_ENDPOINT . '/' . urlencode($keyword);
            $data = $this->getData($url, ['hl' => $this->options['hl']]);

            if ($data) {
                $decoded = json_decode(trim(substr($data, 5)), true);
                return $decoded['default']['topics'] ?? [];
            }
            return [];
        });
    }

    /**
     * Get related queries for a keyword
     */
    private function getRelatedQueries(string $keyword, array $options): array
    {
        try {
            // First, get the explore token
            $exploreToken = $this->getExploreToken($keyword, $options);

            if (!$exploreToken) {
                Log::warning("Could not get explore token for related queries: {$keyword}");
                return ['rising' => [], 'top' => []];
            }

            // Now fetch related queries using the token
            $relatedQueriesData = $this->fetchRelatedQueriesWithToken($exploreToken);

            return $relatedQueriesData;

        } catch (\Exception $e) {
            return ['rising' => [], 'top' => []];
        }
    }

    /**
     * Get explore token for a keyword
     */
    private function getExploreToken(string $keyword, array $options): ?string
    {
        try {
            $payload = [
                'hl' => $options['hl'] ?? $this->options['hl'],
                'tz' => $this->options['tz'],
                'req' => json_encode([
                    'comparisonItem' => [
                        [
                            'keyword' => $keyword,
                            'geo' => $this->options['geo'],
                            'time' => $this->options['time']
                        ]
                    ],
                    'category' => $this->options['category'],
                    'property' => ''
                ])
            ];

            $data = $this->getData(self::GENERAL_ENDPOINT, $payload);

            if ($data) {
                $decoded = json_decode(trim(substr($data, 5)), true);

                if (isset($decoded['widgets'])) {
                    foreach ($decoded['widgets'] as $widget) {
                        if ($widget['id'] === 'RELATED_QUERIES') {
                            return $widget['token'];
                        }
                    }
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::error("Failed to get explore token", [
                'keyword' => $keyword,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Fetch related queries using token
     */
    private function fetchRelatedQueriesWithToken(string $token): array
    {
        try {
            $payload = [
                'hl' => $this->options['hl'],
                'tz' => $this->options['tz'],
                'req' => json_encode([
                    'restriction' => [
                        'geo' => [],
                        'time' => $this->options['time'],
                        'originalTimeRangeForExploreUrl' => $this->options['time']
                    ],
                    'keywordType' => 'QUERY',
                    'metric' => ['TOP', 'RISING'],
                    'trendinessSettings' => [
                        'compareTime' => $this->options['time']
                    ],
                    'requestOptions' => [
                        'property' => '',
                        'backend' => 'IZG',
                        'category' => $this->options['category']
                    ]
                ]),
                'token' => $token
            ];

            $data = $this->getData(self::RELATED_QUERIES_ENDPOINT, $payload);

            if ($data) {
                $decoded = json_decode(trim(substr($data, 5)), true);

                $result = ['rising' => [], 'top' => []];

                if (isset($decoded['default']['rankedList'])) {
                    foreach ($decoded['default']['rankedList'] as $rankedList) {
                        $rankedKeyword = $rankedList['rankedKeyword'] ?? [];

                        foreach ($rankedKeyword as $item) {
                            $query = $item['query'] ?? '';
                            $value = $item['value'] ?? 0;

                            if ($query) {
                                // Determine if it's rising or top based on the data structure
                                $type = 'top'; // Default to top
                                if (isset($item['hasData']) && $item['hasData'] && $value > 1000) {
                                    $type = 'rising';
                                }

                                $result[$type][] = [
                                    'query' => $query,
                                    'value' => $value
                                ];
                            }
                        }
                    }
                }

                return $result;
            }

            return ['rising' => [], 'top' => []];

        } catch (\Exception $e) {
            Log::error("Failed to fetch related queries with token", [
                'token' => $token,
                'error' => $e->getMessage()
            ]);
            return ['rising' => [], 'top' => []];
        }
    }

    /**
     * Format trending queries into a standardized structure
     */
    private function formatTrendingQueries(array $relatedQueries, array $suggestions, string $originalKeyword): array
    {
        $queries = [];

        // Process related queries
        foreach (['rising', 'top'] as $type) {
            if (isset($relatedQueries[$type])) {
                foreach ($relatedQueries[$type] as $query) {
                    $queries[] = [
                        'query' => $query['query'] ?? '',
                        'value' => $query['value'] ?? 0,
                        'type' => $type,
                        'source' => 'related',
                        'relevance_score' => $this->calculateRelevanceScore($query['query'] ?? '', $originalKeyword)
                    ];
                }
            }
        }

        // Process suggestions
        foreach ($suggestions as $suggestion) {
            $title = $suggestion['title'] ?? '';
            if ($title && !$this->isDuplicate($title, $queries)) {
                $queries[] = [
                    'query' => $title,
                    'value' => $suggestion['value'] ?? 0,
                    'type' => 'suggestion',
                    'source' => 'autocomplete',
                    'relevance_score' => $this->calculateRelevanceScore($title, $originalKeyword)
                ];
            }
        }

        // Sort by relevance score and value
        usort($queries, function ($a, $b) {
            if ($a['relevance_score'] === $b['relevance_score']) {
                return $b['value'] <=> $a['value'];
            }
            return $b['relevance_score'] <=> $a['relevance_score'];
        });

        return array_slice($queries, 0, 50); // Limit to top 50 results
    }

    /**
     * Calculate relevance score based on keyword similarity
     */
    private function calculateRelevanceScore(string $query, string $originalKeyword): float
    {
        $query = strtolower(trim($query));
        $original = strtolower(trim($originalKeyword));

        // Simple similarity calculation
        $similarity = 0;

        // Exact match gets highest score
        if ($query === $original) {
            return 1.0;
        }

        // Contains original keyword
        if (strpos($query, $original) !== false) {
            $similarity += 0.8;
        }

        // Word overlap
        $queryWords = explode(' ', $query);
        $originalWords = explode(' ', $original);
        $commonWords = array_intersect($queryWords, $originalWords);

        if (count($originalWords) > 0) {
            $similarity += (count($commonWords) / count($originalWords)) * 0.6;
        }

        // Length similarity (shorter is often better for search)
        $lengthDiff = abs(strlen($query) - strlen($original));
        $similarity -= min($lengthDiff / 100, 0.3);

        return max(0, min(1, $similarity));
    }

    /**
     * Check if a query is already in the results
     */
    private function isDuplicate(string $query, array $existingQueries): bool
    {
        $query = strtolower(trim($query));

        foreach ($existingQueries as $existing) {
            if (strtolower(trim($existing['query'])) === $query) {
                return true;
            }
        }

        return false;
    }

    /**
     * Make HTTP request to Google Trends API
     */
    private function getData(string $url, array $query): string
    {
        try {
            $queryString = http_build_query($query);
            $fullUrl = $url . '?' . $queryString;

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'application/json, text/plain, */*',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ])
            ->timeout(30)
            ->retry(3, 1000)
            ->get($fullUrl);

            if ($response->successful()) {
                return $response->body();
            }

            Log::warning("Google Trends API request failed", [
                'url' => $fullUrl,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return '';

        } catch (\Exception $e) {
            Log::error("Google Trends API request error", [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }

    /**
     * Generate product search queries from trending data
     */
    public function generateProductQueries(array $trendingQueries, array $productCategories = []): array
    {
        $productQueries = [];

        foreach ($trendingQueries as $trend) {
            $baseQuery = $trend['query'];

            // Generate variations for product searches
            $variations = [
                $baseQuery,
                $baseQuery . ' buy',
                $baseQuery . ' price',
                $baseQuery . ' review',
                $baseQuery . ' best',
                'best ' . $baseQuery,
                'cheap ' . $baseQuery,
                $baseQuery . ' sale',
                $baseQuery . ' deal',
                $baseQuery . ' discount'
            ];

            // Add category-specific variations
            foreach ($productCategories as $category) {
                $variations[] = $baseQuery . ' ' . $category;
                $variations[] = $category . ' ' . $baseQuery;
            }

            foreach ($variations as $variation) {
                $productQueries[] = [
                    'query' => trim($variation),
                    'original_trend' => $baseQuery,
                    'trend_value' => $trend['value'] ?? 0,
                    'trend_type' => $trend['type'] ?? 'suggestion',
                    'source' => $trend['source'] ?? 'autocomplete',
                    'relevance_score' => $trend['relevance_score'] ?? 0,
                    'is_product_focused' => $this->isProductFocused($variation)
                ];
            }
        }

        // Remove duplicates and sort by relevance
        $productQueries = array_unique($productQueries, SORT_REGULAR);

        usort($productQueries, function ($a, $b) {
            if ($a['is_product_focused'] !== $b['is_product_focused']) {
                return $b['is_product_focused'] <=> $a['is_product_focused'];
            }
            return $b['relevance_score'] <=> $a['relevance_score'];
        });

        return $productQueries;
    }

    /**
     * Determine if a query is product-focused
     */
    private function isProductFocused(string $query): bool
    {
        $productKeywords = [
            'buy', 'price', 'review', 'best', 'cheap', 'sale', 'deal',
            'discount', 'store', 'shop', 'purchase', 'order', 'amazon',
            'product', 'brand', 'model', 'quality', 'compare'
        ];

        $query = strtolower($query);

        foreach ($productKeywords as $keyword) {
            if (strpos($query, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    public function setProxyConfigs(array $proxy): self
    {
        $this->proxyConfigs = $proxy;
        return $this;
    }
}
