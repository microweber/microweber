<?php

namespace Modules\SiteStats\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Modules\SiteStats\Support\Tracker;
use Modules\SiteStats\Events\PingStatsEvent;
use Illuminate\Support\Facades\Event;

class StatsControllerTest extends TestCase
{


    #[Test]


    public function it_ping_stats_tracks_valid_request(): void {
   Event::fake();

        $response = $this->post('api/pingstats', [], [
            'referer' => site_url() . '/page',
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        //$response->assertStatus(200);


      //  $response->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);

        $this->assertEquals('text/javascript; charset=utf-8', $actual);

        Event::assertDispatched(PingStatsEvent::class);
    }

    #[Test]

    public function it_ping_stats_sets_utm_cookies(): void {
        $utmParams = [
            'utm_source' => 'test_source',
            'utm_medium' => 'test_medium',
            'utm_campaign' => 'test_campaign',
            'utm_term' => 'test_term',
            'utm_content' => 'test_content'
        ];

        $referer = site_url() . '/page?' . http_build_query($utmParams);

        $response = $this->postJson('api/pingstats', [], [
            'referer' => $referer,
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $cookies = ($response->headers->getCookies());

        // Verify cookies are set in response
        foreach ($utmParams as $key => $value) {
            $cookieExists = false;
            foreach ($cookies as $cookie) {
                if ($cookie->getName() === $key) {
                    $cookieExists = true;
                    break;
                }
            }
            $this->assertTrue($cookieExists || (isset($_COOKIE[$key]) && $_COOKIE[$key] === $value));
        }
    }

    #[Test]

    public function it_ping_stats_sets_visitor_data_cookie(): void {


        $response = $this->postJson('api/pingstats', [], [
            'referer' => site_url() . '/page',
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);

        // Verify visitor data cookie is set
        $cookies = ($response->headers->getCookies());
        $cookieExists = false;

        foreach ($cookies as $cookie) {
            if ($cookie->getName() === '_mw_stats_visitor_data') {
                $cookieExists = true;
                break;
            }
        }

        $this->assertTrue($cookieExists || isset($_COOKIE['_mw_stats_visitor_data']));
    }

    #[Test]

    public function it_ping_stats_response_headers(): void {


        $response = $this->postJson('api/pingstats', [], [
            'referer' => site_url() . '/page',
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200)
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT')
            ->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private');


        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);

        $this->assertEquals('text/javascript; charset=utf-8', $actual);

    }

    #[Test]

    public function it_ping_stats_handles_buffered_tracking(): void {


        // Mock the get_option helper
        $this->app->singleton('helper', function () {
            return new class {
                public function get_option($key, $group = null)
                {
                    return $key === 'stats_is_buffered' ? 1 : null;
                }
            };
        });

        $response = $this->postJson('api/pingstats', [], [
            'referer' => site_url() . '/page',
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
    }
}
