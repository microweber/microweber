<?php

namespace Modules\GoogleAnalytics\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\GoogleAnalytics\Microweber\GoogleAnalyticsModule;
use Modules\GoogleAnalytics\Support\DispatchGoogleEventsJs;
use Modules\SiteStats\Models\StatsEvent;
use Tests\TestCase;

class GoogleAnalyticsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_code_is_not_rendered_when_disabled()
    {
        // Arrange
        save_option('google-measurement-enabled', 'n', 'website');
        $checkOption = get_option('google-measurement-enabled', 'website');
        $this->assertEquals($checkOption, 'n');
        // Act
        $module = app()->make(GoogleAnalyticsModule::class);
        $module->setParams(['id' => 'GoogleAnalyticsModule']);
        $output = $module->render();

        // Assert
        $this->assertEmpty($output);
    }

    public function test_tracking_code_is_rendered_when_enabled()
    {
        // Arrange
        save_option('google-measurement-enabled', 'y', 'website');
        save_option('google-measurement-id', 'G-TEST123', 'website');

        $checkOption = get_option('google-measurement-enabled', 'website');
        $this->assertEquals($checkOption, 'y');
        $checkOption = get_option('google-measurement-id', 'website');
        $this->assertEquals($checkOption, 'G-TEST123');

        // Act
        $module = app()->make(GoogleAnalyticsModule::class);
        $module->setParams(['id' => 'GoogleAnalyticsModule']);
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

        // Create and verify test event
        $event = StatsEvent::create([
            'event_action' => 'LOGIN', 
            'event_data' => '{}',
            'utm_visitor_id' => '123',
            'is_sent' => null
        ]);
        
        echo "\nCREATED EVENT:\n";
        print_r($event->toArray());
        echo "\n";

        // Act
        $output = $dispatcher->convertEvents();

        // Debug
        echo "\n\nACTUAL OUTPUT:\n";
        echo $output;
        echo "\nEND OUTPUT\n\n";

        // Debug and assert
        $freshEvent = $event->fresh();
        echo "\nEVENT STATE AFTER PROCESSING:\n";
        print_r($freshEvent->toArray());
        echo "\n";

        $this->assertStringContainsString('gtag(\'event\'', $output);
        $this->assertEquals(1, $freshEvent->is_sent, 'Event should be marked as sent (1)');
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
        $event = StatsEvent::create([
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

        // Debug and assert
        echo "\n\nENHANCED CONVERSION OUTPUT:\n";
        echo $output;
        echo "\nEND OUTPUT\n\n";

        $this->assertStringContainsString('"send_to":"TEST_ID\/TEST_LABEL"', $output);
        $freshEvent = $event->fresh();
echo "\nEVENT STATUS AFTER PROCESSING:\n";
var_dump($freshEvent->is_sent);
echo "\n";
$this->assertSame(1, $freshEvent->is_sent, 'Event should be marked as sent (1)');
    }
}
