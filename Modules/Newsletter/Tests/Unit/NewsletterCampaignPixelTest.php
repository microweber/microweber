<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsletterCampaignPixelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_campaign_pixel()
    {
        $data = [
            'campaign_id' => 1,
            'email' => 'test@example.com',
            'ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $pixel = NewsletterCampaignPixel::create($data);

        $this->assertDatabaseHas('newsletter_campaigns_pixel', [
            'id' => $pixel->id,
            'campaign_id' => 1,
            'email' => 'test@example.com',
        ]);
    }

    #[Test]
    public function it_has_factory()
    {
        $pixel = NewsletterCampaignPixel::factory()->create();
        $this->assertInstanceOf(NewsletterCampaignPixel::class, $pixel);
        $this->assertDatabaseHas('newsletter_campaigns_pixel', [
            'id' => $pixel->id,
        ]);
    }
}