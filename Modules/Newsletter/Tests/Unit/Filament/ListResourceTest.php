<?php

namespace Modules\Newsletter\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\ListResource;
use Modules\Newsletter\Filament\Admin\Resources\ListResource\Pages\ManageLists;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class ListResourceTest extends TestCase
{
    use LazilyRefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('admin-newsletter');
    }

    protected function getResourceClass(): string
    {
        return ListResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ManageLists::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $lists = NewsletterList::factory()->count(3)->create();

        Livewire::test(ManageLists::class)
            ->loadTable()
            ->assertSuccessful();

        foreach ($lists as $list) {
            $this->assertDatabaseHas('newsletter_lists', ['id' => $list->id]);
        }
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        NewsletterList::factory()->count(15)->create();

        Livewire::test(ManageLists::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        $list = NewsletterList::factory()->create([
            'name' => 'Test List Search',
        ]);

        Livewire::test(ManageLists::class)
            ->searchTable('Test List')
            ->loadTable()
            ->assertCanSeeTableRecords([$list]);
    }

    #[Test]
    public function it_create_action_renders_form(): void
    {
        Livewire::test(ManageLists::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function it_create_action_saves_new_record(): void
    {
        Livewire::test(ManageLists::class)
            ->callTableAction('create', data: [
                'name' => 'Test Newsletter List',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('newsletter_lists', [
            'name' => 'Test Newsletter List',
        ]);
    }

    #[Test]
    public function it_edit_action_updates_record(): void
    {
        $list = NewsletterList::factory()->create([
            'name' => 'Original Name',
        ]);

        Livewire::test(ManageLists::class)
            ->callTableAction('edit', $list, data: [
                'name' => 'Updated Name',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('newsletter_lists', [
            'id' => $list->id,
            'name' => 'Updated Name',
        ]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $list = NewsletterList::factory()->create();

        Livewire::test(ManageLists::class)
            ->callTableAction('delete', $list);

        $this->assertDatabaseMissing('newsletter_lists', [
            'id' => $list->id,
        ]);
    }

    #[Test]
    public function it_table_shows_subscribers_count(): void
    {
        $list = NewsletterList::factory()->create();
        NewsletterSubscriber::factory()->count(5)->create()->each(function ($subscriber) use ($list) {
            $subscriber->lists()->attach($list->id);
        });

        Livewire::test(ManageLists::class)
            ->assertSuccessful()
            ->assertTableColumnExists('subscribersCount');
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ManageLists::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('subscribersCount');
    }

    #[Test]
    public function it_export_action_exists(): void
    {
        Livewire::test(ManageLists::class)
            ->assertTableActionExists('export');
    }

    #[Test]
    public function it_bulk_delete_removes_records(): void
    {
        $lists = NewsletterList::factory()->count(3)->create();

        Livewire::test(ManageLists::class)
            ->callTableBulkAction('delete', $lists);

        foreach ($lists as $list) {
            $this->assertDatabaseMissing('newsletter_lists', [
                'id' => $list->id,
            ]);
        }
    }

    #[Test]
    public function it_list_has_subscribers_relationship(): void
    {
        $list = NewsletterList::factory()->create();
        $subscriber = NewsletterSubscriber::factory()->create();
        $list->subscribers()->create([
            'subscriber_id' => $subscriber->id,
        ]);

        $this->assertTrue($list->subscribers()->exists());
    }
}
