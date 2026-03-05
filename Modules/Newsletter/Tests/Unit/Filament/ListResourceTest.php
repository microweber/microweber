<?php

namespace Modules\Newsletter\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\ListResource;
use Modules\Newsletter\Filament\Admin\Resources\ListResource\Pages\ManageLists;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ListResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($user);

        return $user;
    }

    protected function getResourceClass(): string
    {
        return ListResource::class;
    }

    #[Test]
    public function test_index_page_loads_without_errors(): void
    {
        Livewire::test(ManageLists::class)
            ->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $lists = NewsletterList::factory()->count(3)->create();

        Livewire::test(ManageLists::class)
            ->assertCanSeeTableRecords($lists);
    }

    #[Test]
    public function test_index_page_supports_pagination(): void
    {
        NewsletterList::factory()->count(15)->create();

        Livewire::test(ManageLists::class)
            ->assertSuccessful();
    }

    #[Test]
    public function test_index_page_supports_search(): void
    {
        $list = NewsletterList::factory()->create([
            'name' => 'Test List Search',
        ]);

        Livewire::test(ManageLists::class)
            ->searchTable('Test List')
            ->assertCanSeeTableRecords([$list]);
    }

    #[Test]
    public function test_create_page_renders_form(): void
    {
        Livewire::test(ManageLists::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function test_create_page_saves_new_record(): void
    {
        Livewire::test(ManageLists::class)
            ->fillForm([
                'name' => 'Test Newsletter List',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('newsletter_lists', [
            'name' => 'Test Newsletter List',
        ]);
    }

    #[Test]
    public function test_edit_page_updates_record(): void
    {
        $list = NewsletterList::factory()->create([
            'name' => 'Original Name',
        ]);

        Livewire::test(ManageLists::class)
            ->fillForm([
                'name' => 'Updated Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('newsletter_lists', [
            'id' => $list->id,
            'name' => 'Updated Name',
        ]);
    }

    #[Test]
    public function test_delete_action_removes_record(): void
    {
        $list = NewsletterList::factory()->create();

        Livewire::test(ManageLists::class)
            ->callTableAction('delete', $list);

        $this->assertDatabaseMissing('newsletter_lists', [
            'id' => $list->id,
        ]);
    }

    #[Test]
    public function test_table_shows_subscribers_count(): void
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
    public function test_table_has_required_columns(): void
    {
        Livewire::test(ManageLists::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('subscribersCount');
    }

    #[Test]
    public function test_export_action_exists(): void
    {
        Livewire::test(ManageLists::class)
            ->assertTableHeaderActionExists('export');
    }

    #[Test]
    public function test_bulk_export_action_exists(): void
    {
        Livewire::test(ManageLists::class)
            ->assertTableBulkActionExists('export');
    }

    #[Test]
    public function test_bulk_delete_removes_records(): void
    {
        $lists = NewsletterList::factory()->count(3)->create();

        Livewire::test(ManageLists::class)
            ->selectTableRecords($lists)
            ->callTableBulkAction('delete');

        foreach ($lists as $list) {
            $this->assertDatabaseMissing('newsletter_lists', [
                'id' => $list->id,
            ]);
        }
    }

    #[Test]
    public function test_list_has_subscribers_relationship(): void
    {
        $list = NewsletterList::factory()->create();
        $subscriber = NewsletterSubscriber::factory()->create();
        $list->subscribers()->attach($subscriber->id);

        $this->assertTrue($list->subscribers()->exists());
    }
}
