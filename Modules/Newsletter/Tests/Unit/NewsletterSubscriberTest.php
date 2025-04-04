<?php

namespace Modules\Newsletter\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterList;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsletterSubscriberTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_subscribe_email()
    {
        $list = NewsletterList::factory()->create();
        $subscriber = NewsletterSubscriber::create([
            'email' => 'test@example.com',
            'status' => 'active'
        ]);
        
        $subscriber->lists()->attach($list->id);

        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'subscriber_id' => $subscriber->id,
            'list_id' => $list->id
        ]);
    }

    #[Test]
    public function it_enforces_unique_emails_per_list()
    {
        $list = NewsletterList::factory()->create();
        $subscriber = NewsletterSubscriber::create(['email' => 'test@example.com']);
        
        // First subscription works
        $subscriber->lists()->attach($list->id);
        
        // Trying to subscribe same email to same list should fail
        $this->expectException(\Illuminate\Database\QueryException::class);
        $subscriber->lists()->attach($list->id);
    }

    #[Test]
    public function it_allows_one_email_to_multiple_lists()
    {
        $list1 = NewsletterList::factory()->create();
        $list2 = NewsletterList::factory()->create();
        $subscriber = NewsletterSubscriber::create(['email' => 'test@example.com']);
        
        $subscriber->lists()->attach($list1->id);
        $subscriber->lists()->attach($list2->id);
        
        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'subscriber_id' => $subscriber->id,
            'list_id' => $list1->id
        ]);
        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'subscriber_id' => $subscriber->id, 
            'list_id' => $list2->id
        ]);
    }

    #[Test]
    public function it_counts_multiple_list_subscriptions_correctly()
    {
        $lists = NewsletterList::factory()->count(3)->create();
        $subscriber = NewsletterSubscriber::create(['email' => 'test@example.com']);
        
        foreach ($lists as $list) {
            $subscriber->lists()->attach($list->id);
        }
        
        $this->assertEquals(3, $subscriber->lists()->count());
    }
}