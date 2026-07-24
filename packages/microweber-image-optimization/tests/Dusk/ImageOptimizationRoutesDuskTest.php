<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Dusk;

use Tests\DuskTestCase;

/**
 * Dusk / browser smoke tests for image-optimization package routes.
 *
 * When a real Chrome/Chromium binary is available, exercises routes via
 * the browser. Otherwise falls back to HTTP client smoke assertions so
 * CI still validates the routes without a browser stack.
 */
class ImageOptimizationRoutesDuskTest extends DuskTestCase
{
    protected function hasRealChromeBinary(): bool
    {
        $candidates = array_filter([
            getenv('CHROME_PATH') ?: null,
            '/usr/bin/google-chrome',
            '/usr/bin/google-chrome-stable',
            '/usr/bin/chromium',
            '/snap/bin/chromium',
            '/usr/bin/chromium-browser',
        ]);

        foreach ($candidates as $bin) {
            if (!is_string($bin) || $bin === '' || !file_exists($bin)) {
                continue;
            }
            // Snap stub wrappers are not usable without snapd
            if (is_link($bin)) {
                $target = readlink($bin) ?: '';
                if (str_contains($target, 'snap')) {
                    continue;
                }
            }
            $head = @file_get_contents($bin, false, null, 0, 200) ?: '';
            if (str_contains($head, 'requires the chromium snap') || str_contains($head, 'snap install')) {
                continue;
            }
            if (is_executable($bin)) {
                return true;
            }
        }

        return false;
    }

    /**
     * HTTP fallback smoke — always runs when browser is unavailable.
     */
    protected function httpSmoke(string $path, int|array $expectedStatus, ?string $see = null): void
    {
        $base = rtrim($this->siteUrl ?? 'http://127.0.0.1:8000/', '/');
        $url = $base . $path;

        $ctx = stream_context_create([
            'http' => [
                'timeout' => 5,
                'ignore_errors' => true,
                'method' => 'GET',
                'header' => "Accept: text/html,application/json\r\n",
            ],
        ]);
        $body = @file_get_contents($url, false, $ctx);
        if ($body === false) {
            // Fall back to in-process HTTP kernel (no external server required)
            $response = $this->get($path);
            $statuses = is_array($expectedStatus) ? $expectedStatus : [$expectedStatus];
            $this->assertContains($response->status(), $statuses);
            if ($see !== null) {
                $response->assertSee($see, false);
            }

            return;
        }

        $status = 0;
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
            $status = (int) $m[1];
        }
        $statuses = is_array($expectedStatus) ? $expectedStatus : [$expectedStatus];
        $this->assertContains($status, $statuses, "Unexpected status for {$path}");
        if ($see !== null) {
            $this->assertStringContainsString($see, (string) $body);
        }
    }

    public function test_stats_route_smoke(): void
    {
        if ($this->hasRealChromeBinary()) {
            $this->browse(function ($browser) {
                $browser->visit('/image-optimization/stats')
                    ->assertSee('total_files');
            });

            return;
        }

        $this->httpSmoke('/image-optimization/stats', 200, 'total_files');
    }

    public function test_webp_route_missing_src_smoke(): void
    {
        if ($this->hasRealChromeBinary()) {
            $this->browse(function ($browser) {
                $browser->visit('/image-optimization/webp')
                    ->assertSee('Missing src');
            });

            return;
        }

        $this->httpSmoke('/image-optimization/webp', 422, 'Missing src');
    }

    public function test_convert_api_missing_src_smoke(): void
    {
        if ($this->hasRealChromeBinary()) {
            $this->browse(function ($browser) {
                $browser->visit('/api/image-optimization/convert')
                    ->assertSee('Missing src');
            });

            return;
        }

        $this->httpSmoke('/api/image-optimization/convert', 422, 'Missing src');
    }
}
