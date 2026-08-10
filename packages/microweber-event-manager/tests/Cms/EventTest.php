<?php

namespace MicroweberPackages\Event\Tests\Cms;

use MicroweberPackages\Event\EventService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\Event\Facades\EventManager;

class EventTest extends TestCase
{
    #[Test]
    public function it_can_bind_and_trigger_event(): void
    {
        event_bind('some_event_cms', function ($params) {
            $this->assertArrayHasKey('wow1', $params);
        });

        event_trigger('some_event_cms', ['wow1' => 'waw!1']);
    }

    #[Test]
    public function it_can_bind_multiple_events(): void
    {
        event_bind('some_event_cms_a', function ($params) {
            $this->assertArrayHasKey('wow1', $params);
        });

        event_bind('some_event_cms_b', function ($params) {
            $this->assertArrayHasKey('wow2', $params);
        });

        event_trigger('some_event_cms_a', ['wow1' => 'waw!1']);
        event_trigger('some_event_cms_b', ['wow2' => 'waw!2']);
    }

    #[Test]
    public function it_can_use_response_method(): void
    {
        /** @var Event $eventManager */
        $eventManager = EventManager::getFacadeRoot();

        event_bind('modify_data_cms', function ($data) {
            return ['original_key' => $data['original_key']];
        });

        $result = $eventManager->response('modify_data_cms', ['original_key' => 'original_value']);
        $this->assertArrayHasKey('original_key', $result);
    }

    #[Test]
    public function unbind_works_in_cms_context(): void
    {
        event_bind('cms_removable', fn () => 'should not run');

        /** @var Event $eventManager */
        $eventManager = EventManager::getFacadeRoot();
        $this->assertTrue($eventManager->hasListeners('cms_removable'));

        event_unbind('cms_removable');
        $this->assertFalse($eventManager->hasListeners('cms_removable'));
    }
}