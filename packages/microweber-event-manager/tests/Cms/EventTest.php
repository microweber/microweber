<?php

namespace MicroweberPackages\Event\Tests\Cms;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventTest extends TestCase
{
    #[Test]
    public function it_can_bind_and_trigger_event(): void
    {
        $unitTest = $this;

        event_bind('some_event_cms', function ($params) use ($unitTest) {
            $unitTest->assertArrayHasKey('wow1', $params);
        });

        event_trigger('some_event_cms', array('wow1' => 'waw!1'));
    }

    #[Test]
    public function it_can_bind_multiple_events(): void
    {
        $unitTest = $this;

        event_bind('some_event_cms_a', function ($params) use ($unitTest) {
            $unitTest->assertArrayHasKey('wow1', $params);
        });

        event_bind('some_event_cms_b', function ($params) use ($unitTest) {
            $unitTest->assertArrayHasKey('wow2', $params);
        });

        event_trigger('some_event_cms_a', array('wow1' => 'waw!1'));
        event_trigger('some_event_cms_b', array('wow2' => 'waw!2'));
    }

    #[Test]
    public function it_can_use_response_method(): void
    {
        $eventManager = app('event_manager');

        event_bind('modify_data_cms', function ($data) {
            return array_merge($data, ['added_key' => 'added_value']);
        });

        $result = $eventManager->response('modify_data_cms', ['original_key' => 'original_value']);
        $this->assertArrayHasKey('original_key', $result);
    }
}