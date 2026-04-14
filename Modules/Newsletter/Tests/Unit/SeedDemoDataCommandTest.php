<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterTemplate;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SeedDemoDataCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        NewsletterCampaign::query()->delete();
        NewsletterSubscriber::query()->delete();
        NewsletterTemplate::query()->delete();
        NewsletterSenderAccount::query()->delete();
        NewsletterList::query()->delete();
    }

    #[Test]
    public function it_seeds_a_small_realistic_newsletter_dataset(): void
    {
        $result = Artisan::call('newsletter:seed-demo-data', ['--fresh' => true]);

        $this->assertSame(0, $result);
        $this->assertSame(4, NewsletterList::count());
        $this->assertSame(12, NewsletterSubscriber::count());
        $this->assertSame(3, NewsletterTemplate::count());
        $this->assertSame(6, NewsletterCampaign::count());
        $this->assertSame(1, NewsletterSenderAccount::count());

        $this->assertDatabaseHas('newsletter_lists', [
            'name' => 'VIP Customers',
        ]);
        $this->assertDatabaseHas('newsletter_campaigns', [
            'name' => 'Abandoned Cart Recovery',
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
        ]);
        $this->assertDatabaseHas('newsletter_templates', [
            'title' => 'Customer Education Digest',
        ]);
    }

    #[Test]
    public function it_is_idempotent_when_run_multiple_times(): void
    {
        Artisan::call('newsletter:seed-demo-data', ['--fresh' => true]);
        Artisan::call('newsletter:seed-demo-data');

        $this->assertSame(4, NewsletterList::count());
        $this->assertSame(12, NewsletterSubscriber::count());
        $this->assertSame(3, NewsletterTemplate::count());
        $this->assertSame(6, NewsletterCampaign::count());
        $this->assertSame(1, NewsletterSenderAccount::count());
    }
}
