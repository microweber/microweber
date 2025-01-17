<?php

namespace Modules\GoogleAnalytics\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\GoogleAnalytics\Support\DispatchGoogleEventsJs;
use Tests\TestCase;

class GoogleAnalyticsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_code_is_not_rendered_when_disabled()
    {
        // Arrange
        save_option('google-measurement-enabled', 'n', 'website');

        // Act
        $module = app()->make('Modules\GoogleAnalytics\Microweber\GoogleAnalyticsModule');
        $output = $module->render();

        // Assert
        $this->assertEmpty($output);
    }

    public function test_tracking_code_is_rendered_when_enabled()
    {
        // Arrange
        save_option('google-measurement-enabled', 'y', 'website');
        save_option('google-measurement-id', 'G-TEST123', 'website');

        // Act
        $module = app()->make('Modules\GoogleAnalytics\Microweber\GoogleAnalyticsModule');
        $output = $module->render();

        // Assert
        $this->assertStringContainsString('G-TEST123', $output);
        $this->assertStringContainsString('googletagmanager.com/gtag/js', $output);
    }

    public function test_events_are_converted_correctly()
    {
        // Arrange
        save_option('google-measurement-enabled', 'y', 'website');
        save_option('google-measurement-id', 'G-TEST123', 'website');

        $dispatcher = new DispatchGoogleEventsJs();

        // Create a test event
        $event = \MicroweberPackages\Modules\SiteStats\Models\StatsEvent::create([
            'event_action' => 'LOGIN',
            'event_data' => '{}',
            'utm_visitor_id' => '123',
            'is_sent' => null
        ]);

        // Act
        $output = $dispatcher->convertEvents();

        // Assert
        $this->assertStringContainsString('gtag(\'event\'', $output);
        $this->assertTrue($event->fresh()->is_sent);
    }

    public function test_enhanced_conversions_are_handled_correctly()
    {
        // Arrange
        save_option('google-measurement-enabled', 'y', 'website');
        save_option('google-enhanced-conversions-enabled', 'y', 'website');
        save_option('google-enhanced-conversion-id', 'TEST_ID', 'website');
        save_option('google-enhanced-conversion-label', 'TEST_LABEL', 'website');

        $dispatcher = new DispatchGoogleEventsJs();

        // Create a test conversion event
        $event = \MicroweberPackages\Modules\SiteStats\Models\StatsEvent::create([
            'event_action' => 'CONVERSION',
            'event_data' => json_encode([
                'order' => [
                    'email' => 'test@example.com',
                    'transaction_id' => '12345'
                ]
            ]),
            'utm_visitor_id' => '123',
            'is_sent' => null
        ]);

        // Act
        $output = $dispatcher->convertEvents();

        // Assert
        $this->assertStringContainsString('TEST_ID/TEST_LABEL', $output);
        $this->assertTrue($event->fresh()->is_sent);
    }
}
