<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Tools;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Modules\Ai\Tools\GoogleTrendsTool;

class GoogleTrendsToolTest extends ToolTestCase
{
    private GoogleTrendsTool $tool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tool = new GoogleTrendsTool();

        // Clear cache before each test
        Cache::flush();
    }

    /** @test */
    public function it_returns_error_for_missing_action(): void
    {
        $result = $this->tool->__invoke([]);

        $this->assertStringContainsString('Action parameter is required', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    /** @test */
    public function it_returns_error_for_invalid_action(): void
    {
        $result = $this->tool->__invoke(['action' => 'invalid_action']);

        $this->assertStringContainsString('Unknown action: invalid_action', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    /** @test */
    public function it_returns_error_for_trending_queries_without_keyword(): void
    {
        $result = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => '',
        ]);

        $this->assertStringContainsString('Keyword is required', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    /** @test */
    public function it_gets_trending_queries_successfully(): void
    {
        $this->mockGoogleTrendsResponses();

        $result = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => 'AI',
            'geo' => 'US',
            'limit' => 10,
        ]);

        $this->assertStringContainsString('Trending Queries', $result);
        $this->assertStringContainsString('AI', $result);
    }

    /** @test */
    public function it_gets_daily_trends_successfully(): void
    {
        $this->mockDailyTrendsResponse();

        $result = $this->tool->__invoke([
            'action' => 'daily_trends',
            'geo' => 'US',
            'limit' => 5,
        ]);

        $this->assertStringContainsString('Daily Search Trends', $result);
    }

    /** @test */
    public function it_gets_realtime_trends_successfully(): void
    {
        $this->mockRealtimeTrendsResponse();

        $result = $this->tool->__invoke([
            'action' => 'realtime_trends',
            'geo' => 'US',
            'limit' => 5,
        ]);

        $this->assertStringContainsString('Real-time Search Trends', $result);
        $this->assertStringContainsString('Live', $result);
    }

    /** @test */
    public function it_generates_product_queries(): void
    {
        $this->mockGoogleTrendsResponses();

        $result = $this->tool->__invoke([
            'action' => 'product_queries',
            'keyword' => 'laptop',
            'product_categories' => 'electronics,computers',
            'geo' => 'US',
            'limit' => 10,
        ]);

        $this->assertStringContainsString('Product-Focused Trending Queries', $result);
        $this->assertStringContainsString('laptop', $result);
    }

    /** @test */
    public function it_validates_limit_parameter(): void
    {
        $this->mockGoogleTrendsResponses();

        // Test limit too high
        $result = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => 'test',
            'limit' => 100,
        ]);

        // Should still work, limit capped at 50
        $this->assertStringContainsString('Trending Queries', $result);

        // Test limit too low
        $result = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => 'test',
            'limit' => 0,
        ]);

        // Should work with minimum of 1
        $this->assertStringContainsString('Trending Queries', $result);
    }

    /** @test */
    public function it_handles_http_errors(): void
    {
        Http::fake([
            'trends.google.com/*' => Http::response('Error', 500),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => 'error-test',
        ]);

        // Service handles HTTP errors gracefully and returns empty results
        // which get formatted as a "no results" message
        $this->assertStringContainsString('alert-danger', $result);
        // Should contain either error message or no results message
        $this->assertTrue(
            str_contains($result, 'Error accessing Google Trends') || 
            str_contains($result, 'No trending queries data found'),
            'Expected error message or no results message in output'
        );
    }

    /** @test */
    public function it_handles_empty_results(): void
    {
        Http::fake([
            'trends.google.com/trends/api/explore*' => Http::response(
                ')]}\', ' . json_encode(['widgets' => []]),
                200
            ),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => 'xyznonexistent',
        ]);

        $this->assertStringContainsString('No trending queries data found', $result);
    }

    /** @test */
    public function it_handles_malformed_json_response(): void
    {
        Http::fake([
            'trends.google.com/*' => Http::response('not valid json', 200),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => 'malformed',
        ]);

        // Should handle gracefully by returning empty results message
        // The service returns empty array when JSON decoding fails, 
        // which gets formatted as "no results found" - this is graceful handling
        $this->assertStringContainsString('No trending queries data found', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    /** @test */
    public function it_handles_network_exceptions(): void
    {
        Http::fake([
            'trends.google.com/*' => function () {
                throw new \Exception('Connection timeout');
            },
        ]);

        $result = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => 'network-error',
        ]);

        // The GoogleTrendsService catches exceptions internally and returns empty arrays
        // which then get formatted as "no results found" - this is graceful error handling
        $this->assertStringContainsString('No trending queries data found', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    /** @test */
    public function it_supports_different_geographic_regions(): void
    {
        $this->mockGoogleTrendsResponses();

        $regions = ['US', 'GB', 'DE', 'FR', 'JP'];

        foreach ($regions as $region) {
            $result = $this->tool->__invoke([
                'action' => 'trending_queries',
                'keyword' => 'technology',
                'geo' => $region,
            ]);

            $this->assertStringContainsString('Trending Queries', $result);
            $this->assertStringContainsString('Region:', $result);
        }
    }

    /** @test */
    public function it_supports_different_time_ranges(): void
    {
        $this->mockGoogleTrendsResponses();

        $timeRanges = ['now 1-H', 'now 7-d', 'today 12-m', 'today 5-y'];

        foreach ($timeRanges as $timeRange) {
            $result = $this->tool->__invoke([
                'action' => 'trending_queries',
                'keyword' => 'AI',
                'time_range' => $timeRange,
            ]);

            $this->assertStringContainsString('Trending Queries', $result);
        }
    }

    /** @test */
    public function it_caches_results(): void
    {
        $this->mockGoogleTrendsResponses();

        // First call
        $result1 = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => 'cached-keyword',
        ]);

        // Second call should use cache
        $result2 = $this->tool->__invoke([
            'action' => 'trending_queries',
            'keyword' => 'cached-keyword',
        ]);

        $this->assertStringContainsString('Trending Queries', $result1);
        $this->assertStringContainsString('Trending Queries', $result2);

        // The service makes 3 HTTP requests per call (explore, related searches, autocomplete)
        // With caching, both calls together should make only 3 requests total (not 6)
        Http::assertSentCount(3);
    }

    /**
     * Mock Google Trends API responses
     */
    private function mockGoogleTrendsResponses(): void
    {
        $exploreResponse = [
            'widgets' => [
                [
                    'id' => 'RELATED_QUERIES',
                    'token' => 'test_token_123',
                ],
            ],
        ];

        $relatedQueriesResponse = [
            'default' => [
                'rankedList' => [
                    [
                        'rankedKeyword' => [
                            ['query' => 'AI technology', 'value' => 100],
                            ['query' => 'AI tools', 'value' => 80],
                            ['query' => 'AI software', 'value' => 60],
                        ],
                    ],
                ],
            ],
        ];

        $autocompleteResponse = [
            'default' => [
                'topics' => [
                    ['title' => 'Artificial Intelligence', 'value' => 100],
                    ['title' => 'Machine Learning', 'value' => 80],
                ],
            ],
        ];

        Http::fake([
            'trends.google.com/trends/api/explore*' => Http::response(
                ')]}\', ' . json_encode($exploreResponse),
                200
            ),
            'trends.google.com/trends/api/widgetdata/relatedsearches*' => Http::response(
                ')]}\', ' . json_encode($relatedQueriesResponse),
                200
            ),
            'trends.google.com/trends/api/autocomplete*' => Http::response(
                ')]}\', ' . json_encode($autocompleteResponse),
                200
            ),
        ]);
    }

    /**
     * Mock daily trends response
     */
    private function mockDailyTrendsResponse(): void
    {
        $response = [
            'default' => [
                'trendingSearchesDays' => [
                    [
                        'trendingSearches' => [
                            [
                                'title' => ['query' => 'Trend 1'],
                                'formattedTraffic' => '100K+',
                                'articles' => [
                                    ['title' => 'Article 1', 'source' => 'Source 1'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        Http::fake([
            'trends.google.com/trends/api/dailytrends*' => Http::response(
                ')]}\', ' . json_encode($response),
                200
            ),
        ]);
    }

    /**
     * Mock real-time trends response
     */
    private function mockRealtimeTrendsResponse(): void
    {
        $response = [
            'storySummaries' => [
                'trendingStories' => [
                    [
                        'title' => 'Breaking News',
                        'entityNames' => ['Topic 1', 'Topic 2'],
                        'articles' => [
                            ['title' => 'Article 1', 'source' => 'Source 1', 'timeAgo' => '2 hours ago'],
                        ],
                    ],
                ],
            ],
        ];

        Http::fake([
            'trends.google.com/trends/api/realtimetrends*' => Http::response(
                ')]}\', ' . json_encode($response),
                200
            ),
        ]);
    }
}
