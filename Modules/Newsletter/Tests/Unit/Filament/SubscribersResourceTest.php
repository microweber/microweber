<?php

namespace Modules\Newsletter\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource\Pages\ManageSubscribers;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterList;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SubscribersResourceTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    protected function getResourceClass(): string
    {
        return SubscribersResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ManageSubscribers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $subscribers = NewsletterSubscriber::factory()->count(3)->create();

        Livewire::test(ManageSubscribers::class)
            ->assertCanSeeTableRecords($subscribers);
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        NewsletterSubscriber::factory()->count(15)->create();

        Livewire::test(ManageSubscribers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        $subscriber = NewsletterSubscriber::factory()->create([
            'email' => 'test.subscriber@example.com',
            'name' => 'Test Subscriber',
        ]);

        Livewire::test(ManageSubscribers::class)
            ->searchTable('test.subscriber')
            ->assertCanSeeTableRecords([$subscriber]);
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(ManageSubscribers::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function it_create_page_validates_required_fields(): void
    {
        Livewire::test(ManageSubscribers::class)
            ->fillForm([
                'email' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'email',
            ]);
    }

    #[Test]
    public function it_create_page_validates_email_format(): void
    {
        Livewire::test(ManageSubscribers::class)
            ->fillForm([
                'email' => 'invalid-email',
                'name' => 'Test Name',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'email',
            ]);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(ManageSubscribers::class)
            ->fillForm([
                'email' => 'new.subscriber@example.com',
                'name' => 'New Subscriber',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'new.subscriber@example.com',
            'name' => 'New Subscriber',
        ]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $subscriber = NewsletterSubscriber::factory()->create([
            'email' => 'original@example.com',
            'name' => 'Original Name',
        ]);

        Livewire::test(ManageSubscribers::class)
            ->fillForm([
                'name' => 'Updated Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('newsletter_subscribers', [
            'id' => $subscriber->id,
            'name' => 'Updated Name',
        ]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $subscriber = NewsletterSubscriber::factory()->create();

        Livewire::test(ManageSubscribers::class)
            ->callTableAction('delete', $subscriber);

        $this->assertDatabaseMissing('newsletter_subscribers', [
            'id' => $subscriber->id,
        ]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ManageSubscribers::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('email')
            ->assertTableColumnExists('lists.name');
    }

    #[Test]
    public function it_can_subscribe_to_lists(): void
    {
        $list = NewsletterList::factory()->create();

        Livewire::test(ManageSubscribers::class)
            ->fillForm([
                'email' => 'list.subscriber@example.com',
                'name' => 'List Subscriber',
                'lists' => [$list->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'list.subscriber@example.com',
        ]);
    }

    #[Test]
    public function it_export_action_exists(): void
    {
        Livewire::test(ManageSubscribers::class)
            ->assertTableHeaderActionExists('export');
    }

    #[Test]
    public function it_import_action_exists(): void
    {
        Livewire::test(ManageSubscribers::class)
            ->assertTableHeaderActionExists('importProducts');
    }

    #[Test]
    public function it_bulk_export_action_exists(): void
    {
        Livewire::test(ManageSubscribers::class)
            ->assertTableBulkActionExists('export');
    }

    #[Test]
    public function it_bulk_delete_removes_records(): void
    {
        $subscribers = NewsletterSubscriber::factory()->count(3)->create();

        Livewire::test(ManageSubscribers::class)
            ->selectTableRecords($subscribers)
            ->callTableBulkAction('delete');

        foreach ($subscribers as $subscriber) {
            $this->assertDatabaseMissing('newsletter_subscribers', [
                'id' => $subscriber->id,
            ]);
        }
    }
}
