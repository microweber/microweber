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
        // Compare by PATH + query, not the absolute URL: the parsed template
        // builds links from the request/site host, which differs from route()'s
        // APP_URL host in CI, so an absolute-URL match is env-brittle (passes
        // locally, fails in CI). The path + ?email= is the stable contract.
        $unsubscribePath = parse_url(route('modules.newsletter.unsubscribe'), PHP_URL_PATH);
        $this->assertStringContainsString($unsubscribePath . '?email=taylor.reader@example.com', $parsed);
    }
}
