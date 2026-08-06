<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Tests\Unit;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use MicroweberPackages\Notification\Channels\AppMailChannel;
use MicroweberPackages\Notification\Mail\SimpleHtmlEmail;
use MicroweberPackages\Notification\Tests\TestCase;

class AppMailChannelTest extends TestCase
{
    public function test_channel_class_exists(): void
    {
        $this->assertTrue(class_exists(AppMailChannel::class));
    }

    public function test_send_skips_when_no_mail_driver(): void
    {
        Config::set('mail.default', null);
        Config::set('mail.driver', null);

        $channel = $this->app->make(AppMailChannel::class);
        $result = $channel->send(new \stdClass(), new class extends Notification
        {
            public function via(mixed $notifiable): array
            {
                return [];
            }
        });

        $this->assertNull($result);
    }

    public function test_simple_html_email_build(): void
    {
        $mailable = new SimpleHtmlEmail('<p>Hello</p>');
        $this->assertSame('<p>Hello</p>', $mailable->htmlBody);
        $built = $mailable->build();
        $this->assertInstanceOf(SimpleHtmlEmail::class, $built);
    }
}
