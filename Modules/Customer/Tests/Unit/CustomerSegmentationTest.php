<?php

namespace Modules\Customer\Tests\Unit;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Customer\Models\Customer;
use Modules\Customer\Services\CustomerSegmentationService;
use Modules\Tag\Models\Tag;
use Modules\Order\Models\Order;

class CustomerSegmentationTest extends TestCase
{
    protected CustomerSegmentationService $segmentationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->segmentationService = new CustomerSegmentationService();
        
        // Clean up before each test - using tagging_tags (the actual table name)
        DB::table('customer_tags')->delete();
        DB::table('customers')->delete();
        DB::table('tagging_tags')->delete();
        DB::table('tagging_tagged')->delete();
    }

    #[Test]
    public function it_can_add_tags_to_customer(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        $tag = Tag::create([
            'name' => 'VIP',
            'slug' => 'vip',
        ]);

        $customer->addTags([$tag->id]);

        $this->assertTrue($customer->tags->contains('id', $tag->id));
        $this->assertEquals(1, $customer->tags->count());
    }

    #[Test]
    public function it_can_sync_tags_for_customer(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        $tag1 = Tag::create(['name' => 'Tag 1', 'slug' => 'tag-1']);
        $tag2 = Tag::create(['name' => 'Tag 2', 'slug' => 'tag-2']);
        $tag3 = Tag::create(['name' => 'Tag 3', 'slug' => 'tag-3']);

        // Initially add tags 1 and 2
        $customer->addTags([$tag1->id, $tag2->id]);
        $this->assertEquals(2, $customer->tags->count());

        // Sync to tags 2 and 3 (should remove tag 1)
        $customer->syncTags([$tag2->id, $tag3->id]);
        $customer->refresh();

        $this->assertEquals(2, $customer->tags->count());
        $this->assertFalse($customer->tags->contains('id', $tag1->id));
        $this->assertTrue($customer->tags->contains('id', $tag2->id));
        $this->assertTrue($customer->tags->contains('id', $tag3->id));
    }

    #[Test]
    public function it_can_remove_tags_from_customer(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        $tag1 = Tag::create(['name' => 'Tag 1', 'slug' => 'tag-1']);
        $tag2 = Tag::create(['name' => 'Tag 2', 'slug' => 'tag-2']);

        $customer->addTags([$tag1->id, $tag2->id]);
        $this->assertEquals(2, $customer->tags->count());

        $customer->removeTags([$tag1->id]);
        $customer->refresh();

        $this->assertEquals(1, $customer->tags->count());
        $this->assertFalse($customer->tags->contains('id', $tag1->id));
        $this->assertTrue($customer->tags->contains('id', $tag2->id));
    }

    #[Test]
    public function it_gets_tag_list_attribute(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        $tag1 = Tag::create(['name' => 'VIP', 'slug' => 'vip']);
        $tag2 = Tag::create(['name' => 'Premium', 'slug' => 'premium']);

        $customer->addTags([$tag1->id, $tag2->id]);
        $customer->refresh();

        $this->assertStringContainsString('VIP', $customer->tag_list);
        $this->assertStringContainsString('Premium', $customer->tag_list);
    }

    #[Test]
    public function it_gets_tag_ids_attribute(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        $tag = Tag::create(['name' => 'VIP', 'slug' => 'vip']);
        $customer->addTags([$tag->id]);
        $customer->refresh();

        $this->assertContains($tag->id, $customer->tag_ids);
    }

    #[Test]
    public function it_scopes_customers_by_single_tag(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'customer1@example.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'customer2@example.com']);
        $customer3 = Customer::create(['name' => 'Customer 3', 'email' => 'customer3@example.com']);

        $tag = Tag::create(['name' => 'VIP', 'slug' => 'vip']);

        $customer1->addTags([$tag->id]);
        $customer2->addTags([$tag->id]);

        $vipCustomers = Customer::hasTag($tag->id)->get();

        $this->assertEquals(2, $vipCustomers->count());
        $this->assertTrue($vipCustomers->contains('id', $customer1->id));
        $this->assertTrue($vipCustomers->contains('id', $customer2->id));
        $this->assertFalse($vipCustomers->contains('id', $customer3->id));
    }

    #[Test]
    public function it_scopes_customers_with_any_tag(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'customer1@example.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'customer2@example.com']);
        $customer3 = Customer::create(['name' => 'Customer 3', 'email' => 'customer3@example.com']);

        $tag1 = Tag::create(['name' => 'Tag 1', 'slug' => 'tag-1']);
        $tag2 = Tag::create(['name' => 'Tag 2', 'slug' => 'tag-2']);

        $customer1->addTags([$tag1->id]);
        $customer2->addTags([$tag2->id]);
        $customer3->addTags([$tag1->id, $tag2->id]);

        $customers = Customer::hasAnyTag([$tag1->id, $tag2->id])->get();

        $this->assertEquals(3, $customers->count());
    }

    #[Test]
    public function it_scopes_customers_with_all_tags(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'customer1@example.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'customer2@example.com']);
        $customer3 = Customer::create(['name' => 'Customer 3', 'email' => 'customer3@example.com']);

        $tag1 = Tag::create(['name' => 'Tag 1', 'slug' => 'tag-1']);
        $tag2 = Tag::create(['name' => 'Tag 2', 'slug' => 'tag-2']);

        $customer1->addTags([$tag1->id]); // Only tag 1
        $customer2->addTags([$tag2->id]); // Only tag 2
        $customer3->addTags([$tag1->id, $tag2->id]); // Both tags

        $customers = Customer::hasAllTags([$tag1->id, $tag2->id])->get();

        $this->assertEquals(1, $customers->count());
        $this->assertTrue($customers->contains('id', $customer3->id));
    }

    #[Test]
    public function it_gets_customers_without_tags(): void
    {
        $customer1 = Customer::create(['name' => 'Tagged', 'email' => 'tagged@example.com']);
        $customer2 = Customer::create(['name' => 'Untagged', 'email' => 'untagged@example.com']);

        $tag = Tag::create(['name' => 'Tag', 'slug' => 'tag']);
        $customer1->addTags([$tag->id]);

        $customersWithoutTags = Customer::doesntHave('tags')->get();

        $this->assertEquals(1, $customersWithoutTags->count());
        $this->assertTrue($customersWithoutTags->contains('id', $customer2->id));
    }

    #[Test]
    public function it_allows_duplicate_tag_prevention(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        $tag = Tag::create(['name' => 'VIP', 'slug' => 'vip']);

        // Add the same tag twice
        $customer->addTags([$tag->id]);
        $customer->addTags([$tag->id]);
        $customer->refresh();

        // Should still only have 1 tag
        $this->assertEquals(1, $customer->tags->count());
    }

    // Service Tests

    #[Test]
    public function service_returns_customers_by_single_tag(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'c1@test.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'c2@test.com']);
        
        $tag = Tag::create(['name' => 'Premium', 'slug' => 'premium']);
        
        $customer1->addTags([$tag->id]);

        $customers = $this->segmentationService->getCustomersByTags($tag->id);

        $this->assertEquals(1, $customers->count());
        $this->assertTrue($customers->contains('id', $customer1->id));
    }

    #[Test]
    public function service_returns_customers_by_multiple_tags_with_any_match(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'c1@test.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'c2@test.com']);
        $customer3 = Customer::create(['name' => 'Customer 3', 'email' => 'c3@test.com']);

        $tag1 = Tag::create(['name' => 'Tag 1', 'slug' => 'tag-1']);
        $tag2 = Tag::create(['name' => 'Tag 2', 'slug' => 'tag-2']);

        $customer1->addTags([$tag1->id]);
        $customer2->addTags([$tag2->id]);

        $customers = $this->segmentationService->getCustomersByTags([$tag1->id, $tag2->id], false);

        $this->assertEquals(2, $customers->count());
        $this->assertTrue($customers->contains('id', $customer1->id));
        $this->assertTrue($customers->contains('id', $customer2->id));
        $this->assertFalse($customers->contains('id', $customer3->id));
    }

    #[Test]
    public function service_returns_customers_by_multiple_tags_with_all_match(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'c1@test.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'c2@test.com']);

        $tag1 = Tag::create(['name' => 'Tag 1', 'slug' => 'tag-1']);
        $tag2 = Tag::create(['name' => 'Tag 2', 'slug' => 'tag-2']);

        $customer1->addTags([$tag1->id, $tag2->id]); // Has both
        $customer2->addTags([$tag1->id]); // Has only tag 1

        $customers = $this->segmentationService->getCustomersByTags([$tag1->id, $tag2->id], true);

        $this->assertEquals(1, $customers->count());
        $this->assertTrue($customers->contains('id', $customer1->id));
    }

    #[Test]
    public function service_returns_customers_without_tags(): void
    {
        $customer1 = Customer::create(['name' => 'Tagged', 'email' => 'tagged@test.com']);
        $customer2 = Customer::create(['name' => 'Untagged', 'email' => 'untagged@test.com']);

        $tag = Tag::create(['name' => 'Tag', 'slug' => 'tag']);
        $customer1->addTags([$tag->id]);

        $customers = $this->segmentationService->getCustomersWithoutTags();

        $this->assertEquals(1, $customers->count());
        $this->assertTrue($customers->contains('id', $customer2->id));
    }

    #[Test]
    public function service_returns_tag_analytics(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'c1@test.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'c2@test.com']);
        $customer3 = Customer::create(['name' => 'Customer 3', 'email' => 'c3@test.com']);

        $tag = Tag::create(['name' => 'VIP', 'slug' => 'vip']);

        $customer1->addTags([$tag->id]);
        $customer2->addTags([$tag->id]);

        $analytics = $this->segmentationService->getTagAnalytics();

        $this->assertArrayHasKey('total_tags', $analytics);
        $this->assertArrayHasKey('customers_with_tags', $analytics);
        $this->assertArrayHasKey('customers_without_tags', $analytics);
        $this->assertArrayHasKey('top_tags', $analytics);
        $this->assertArrayHasKey('tag_distribution', $analytics);

        $this->assertEquals(1, $analytics['total_tags']);
        $this->assertEquals(2, $analytics['customers_with_tags']);
        $this->assertEquals(1, $analytics['customers_without_tags']);
    }

    #[Test]
    public function service_bulk_assigns_tags(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'c1@test.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'c2@test.com']);

        $tag = Tag::create(['name' => 'Bulk Tag', 'slug' => 'bulk-tag']);

        $count = $this->segmentationService->bulkAssignTags(
            [$customer1->id, $customer2->id],
            [$tag->id]
        );

        $this->assertEquals(2, $count);

        $customer1->refresh();
        $customer2->refresh();

        $this->assertTrue($customer1->tags->contains('id', $tag->id));
        $this->assertTrue($customer2->tags->contains('id', $tag->id));
    }

    #[Test]
    public function service_bulk_removes_tags(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'c1@test.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'c2@test.com']);

        $tag = Tag::create(['name' => 'Remove Tag', 'slug' => 'remove-tag']);

        $customer1->addTags([$tag->id]);
        $customer2->addTags([$tag->id]);

        $count = $this->segmentationService->bulkRemoveTags(
            [$customer1->id, $customer2->id],
            [$tag->id]
        );

        $this->assertEquals(2, $count);

        $customer1->refresh();
        $customer2->refresh();

        $this->assertEquals(0, $customer1->tags->count());
        $this->assertEquals(0, $customer2->tags->count());
    }

    #[Test]
    public function service_creates_segment_with_criteria(): void
    {
        $customer1 = Customer::create([
            'name' => 'Active Customer',
            'email' => 'active@test.com',
            'status' => 'active',
        ]);
        $customer2 = Customer::create([
            'name' => 'Inactive Customer',
            'email' => 'inactive@test.com',
            'status' => 'inactive',
        ]);

        $tag = Tag::create(['name' => 'VIP', 'slug' => 'vip']);
        $customer1->addTags([$tag->id]);

        $segment = $this->segmentationService->createSegment('VIP Active Customers', [
            'tags' => [$tag->id],
            'tag_match' => 'any',
            'status' => 'active',
        ]);

        $this->assertEquals('VIP Active Customers', $segment['name']);
        $this->assertEquals(1, $segment['count']);
        $this->assertTrue(in_array($customer1->id, $segment['customer_ids']));
    }

    #[Test]
    public function service_gets_similar_customers(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'c1@test.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'c2@test.com']);
        $customer3 = Customer::create(['name' => 'Customer 3', 'email' => 'c3@test.com']);

        $tag1 = Tag::create(['name' => 'Tag 1', 'slug' => 'tag-1']);
        $tag2 = Tag::create(['name' => 'Tag 2', 'slug' => 'tag-2']);

        $customer1->addTags([$tag1->id, $tag2->id]);
        $customer2->addTags([$tag1->id, $tag2->id]);
        $customer3->addTags([$tag1->id]);

        $similar = $this->segmentationService->getSimilarCustomers($customer1->id);

        // Both customer2 and customer3 have at least one matching tag
        $this->assertGreaterThanOrEqual(1, $similar->count());
    }

    #[Test]
    public function service_applies_additional_filters(): void
    {
        $customer1 = Customer::create([
            'name' => 'Customer 1',
            'email' => 'c1@test.com',
            'status' => 'active',
        ]);
        $customer2 = Customer::create([
            'name' => 'Customer 2',
            'email' => 'c2@test.com',
            'status' => 'inactive',
        ]);

        $tag = Tag::create(['name' => 'Tag', 'slug' => 'tag']);
        $customer1->addTags([$tag->id]);
        $customer2->addTags([$tag->id]);

        $customers = $this->segmentationService->getCustomersByTags(
            [$tag->id],
            false,
            ['status' => 'active']
        );

        $this->assertEquals(1, $customers->count());
        $this->assertTrue($customers->contains('id', $customer1->id));
    }

    #[Test]
    public function service_handles_empty_tag_list(): void
    {
        $customers = $this->segmentationService->getCustomersByTags([]);

        $this->assertEquals(0, $customers->count());
    }

    #[Test]
    public function service_resolves_tag_names_to_ids(): void
    {
        $customer = Customer::create(['name' => 'Customer', 'email' => 'customer@test.com']);
        $tag = Tag::create(['name' => 'Premium', 'slug' => 'premium']);
        $customer->addTags([$tag->id]);

        $customers = $this->segmentationService->getCustomersByTags(['Premium']);

        $this->assertEquals(1, $customers->count());
        $this->assertTrue($customers->contains('id', $customer->id));
    }

    #[Test]
    public function filter_applies_tag_filter(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'c1@test.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'c2@test.com']);

        $tag = Tag::create(['name' => 'VIP', 'slug' => 'vip']);
        $customer1->addTags([$tag->id]);

        $customers = Customer::filter(['tags' => [$tag->id]])->get();

        $this->assertEquals(1, $customers->count());
        $this->assertTrue($customers->contains('id', $customer1->id));
    }

    #[Test]
    public function filter_applies_tags_any_filter(): void
    {
        $customer1 = Customer::create(['name' => 'Customer 1', 'email' => 'c1@test.com']);
        $customer2 = Customer::create(['name' => 'Customer 2', 'email' => 'c2@test.com']);
        $customer3 = Customer::create(['name' => 'Customer 3', 'email' => 'c3@test.com']);

        $tag1 = Tag::create(['name' => 'Tag 1', 'slug' => 'tag-1']);
        $tag2 = Tag::create(['name' => 'Tag 2', 'slug' => 'tag-2']);

        $customer1->addTags([$tag1->id]);
        $customer2->addTags([$tag2->id]);

        $customers = Customer::filter(['tagsAny' => [$tag1->id, $tag2->id]])->get();

        $this->assertEquals(2, $customers->count());
        $this->assertTrue($customers->contains('id', $customer1->id));
        $this->assertTrue($customers->contains('id', $customer2->id));
    }

    #[Test]
    public function filter_applies_without_tags_filter(): void
    {
        $customer1 = Customer::create(['name' => 'Tagged', 'email' => 'tagged@test.com']);
        $customer2 = Customer::create(['name' => 'Untagged', 'email' => 'untagged@test.com']);

        $tag = Tag::create(['name' => 'Tag', 'slug' => 'tag']);
        $customer1->addTags([$tag->id]);

        $customers = Customer::filter(['withoutTags' => true])->get();

        $this->assertEquals(1, $customers->count());
        $this->assertTrue($customers->contains('id', $customer2->id));
    }
}
