<?php
namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsletterListTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_newsletter_list()
    {
        $data = [
            'name' => 'Test List',
            'description' => 'A test list',
            'is_public' => true,
        ];

        $list = NewsletterList::create($data);

        $this->assertDatabaseHas('newsletter_lists', [
            'id' => $list->id,
            'name' => 'Test List',
            'description' => 'A test list',
            'is_public' => true,
        ]);
    }

    #[Test]
    public function it_has_factory()
    {
        $list = NewsletterList::factory()->create();
        $this->assertInstanceOf(NewsletterList::class, $list);
        $this->assertDatabaseHas('newsletter_lists', [
            'id' => $list->id,
        ]);
    }

    #[Test]
    public function it_has_subscribers_relationship()
    {
        $list = NewsletterList::factory()->create();
        $subscriberList = NewsletterSubscriberList::factory()->create([
            'list_id' => $list->id,
        ]);

        $this->assertTrue($list->subscribers->contains($subscriberList));
    }

    #[Test]
    public function it_returns_subscribers_count_attribute()
    {
        $list = NewsletterList::factory()->create();
        NewsletterSubscriberList::factory()->count(3)->create([
            'list_id' => $list->id,
        ]);

        $this->assertEquals(3, $list->subscribers_count);
    }
}