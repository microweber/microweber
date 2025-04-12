<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsletterCampaignClickedLinkTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_campaign_clicked_link()
    {
        $data = [
            'campaign_id' => 1,
            'email' => 'test@example.com',
            'ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'link' => 'https://example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $clickedLink = NewsletterCampaignClickedLink::create($data);

        $this->assertDatabaseHas('newsletter_campaigns_clicked_link', [
            'id' => $clickedLink->id,
            'campaign_id' => 1,
            'email' => 'test@example.com',
            'link' => 'https://example.com',
        ]);
    }

    #[Test]
    public function it_has_factory()
    {
        $clickedLink = NewsletterCampaignClickedLink::factory()->create();
        $this->assertInstanceOf(NewsletterCampaignClickedLink::class, $clickedLink);
        $this->assertDatabaseHas('newsletter_campaigns_clicked_link', [
            'id' => $clickedLink->id,
        ]);
    }
}