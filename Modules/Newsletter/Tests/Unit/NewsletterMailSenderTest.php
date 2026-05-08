<?php

namespace Modules\Newsletter\Tests\Unit;

use Modules\Newsletter\Senders\NewsletterMailSender;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsletterMailSenderTest extends TestCase
{
    #[Test]
    public function it_prefers_campaign_level_sender_overrides()
    {
        $sender = new NewsletterMailSender();
        $sender->setCampaign([
            'name' => 'Campaign Name',
            'from_name' => 'Campaign From',
            'from_email' => 'campaign@example.com',
            'reply_email' => 'campaign-reply@example.com',
        ]);
        $sender->setSender([
            'from_name' => 'Account From',
            'from_email' => 'account@example.com',
            'reply_email' => 'account-reply@example.com',
        ]);

        $this->assertSame([
            'from_name' => 'Campaign From',
            'from_email' => 'campaign@example.com',
            'reply_email' => 'campaign-reply@example.com',
        ], $sender->getResolvedSender());
    }

    #[Test]
    public function it_parses_safe_and_legacy_placeholders()
    {
        $sender = new NewsletterMailSender();
        $sender->setCampaign([
            'id' => 123,
            'name' => 'Campaign Name',
            'subject' => 'Campaign Subject',
        ]);
        $sender->setSender([
            'from_name' => 'Account From',
            'from_email' => 'account@example.com',
            'reply_email' => 'account-reply@example.com',
        ]);
        $sender->setSubscriber([
            'name' => 'Taylor Reader',
            'email' => 'taylor.reader@example.com',
        ]);
        $sender->setTemplate([
            'text' => 'Hello [[name]]! Your email is {{email}}. Unsubscribe: [[unsubscribe_url]]',
        ]);

        $parsed = $sender->getParsedTemplate();

        $this->assertStringContainsString('Taylor Reader', $parsed);
        $this->assertStringContainsString('taylor.reader@example.com', $parsed);
        $this->assertStringContainsString(route('modules.newsletter.unsubscribe').'?email=taylor.reader@example.com', $parsed);
    }
}
