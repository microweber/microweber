<?php

namespace Modules\Newsletter\Tests\Unit;

use Tests\TestCase;
use Modules\Newsletter\Models\NewsletterCampaign;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsletterCampaignTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_campaign()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'subject' => 'Test Campaign',
            'status' => 'draft'
        ]);

        $this->assertDatabaseHas('newsletter_campaigns', [
            'id' => $campaign->id,
            'subject' => 'Test Campaign'
        ]);
    }

    /** @test */
    public function it_can_change_status()
    {
        $campaign = NewsletterCampaign::factory()->create(['status' => 'draft']);
        
        $campaign->update(['status' => 'sending']);
        
        $this->assertEquals('sending', $campaign->fresh()->status);
    }
}