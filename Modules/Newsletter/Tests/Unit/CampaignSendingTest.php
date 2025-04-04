<?php

namespace Modules\Newsletter\Tests\Unit;


use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Modules\Newsletter\Mails\NewsletterMail;

class CampaignSendingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    #[Test]
    public function it_sends_to_active_subscribers()
    {
        $list = NewsletterList::factory()->create();
        $subscribers = NewsletterSubscriber::factory()
            ->count(3)
            ->create(['list_id' => $list->id, 'status' => 'active']);

        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
            'status' => 'sending'
        ]);

        // Trigger sending (would normally be a job)
        foreach($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterMail($campaign));
        }

        Mail::assertSent(NewsletterMail::class, 3);
    }

    #[Test]
    public function it_does_not_send_to_unsubscribed()
    {
        $list = NewsletterList::factory()->create();
        NewsletterSubscriber::factory()->create([
            'list_id' => $list->id,
            'status' => 'unsubscribed'
        ]);

        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id
        ]);

        // Should send 0 emails
        Mail::assertNothingSent();
    }
}
