<?php

namespace Modules\Newsletter\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates;
use Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TemplatesResourceTest extends TestCase
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
        return TemplatesResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ManageTemplates::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $templates = NewsletterTemplate::factory()->count(3)->create();

        Livewire::test(ManageTemplates::class)
            ->assertCanSeeTableRecords($templates);
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        NewsletterTemplate::factory()->count(15)->create();

        Livewire::test(ManageTemplates::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        $template = NewsletterTemplate::factory()->create([
            'title' => 'Test Template Search',
        ]);

        Livewire::test(ManageTemplates::class)
            ->searchTable('Test Template')
            ->assertCanSeeTableRecords([$template]);
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(ManageTemplates::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function it_edit_action_navigates_to_template_editor(): void
    {
        $template = NewsletterTemplate::factory()->create([
            'title' => 'Test Template',
        ]);

        Livewire::test(ManageTemplates::class)
            ->callTableAction('Edit', $template)
            ->assertRedirect();
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $template = NewsletterTemplate::factory()->create();

        Livewire::test(ManageTemplates::class)
            ->callTableAction('delete', $template);

        $this->assertDatabaseMissing('newsletter_templates', [
            'id' => $template->id,
        ]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ManageTemplates::class)
            ->assertTableColumnExists('title')
            ->assertTableColumnExists('created_at');
    }

    #[Test]
    public function it_can_sort_by_title(): void
    {
        NewsletterTemplate::factory()->create(['title' => 'Alpha Template']);
        NewsletterTemplate::factory()->create(['title' => 'Beta Template']);

        Livewire::test(ManageTemplates::class)
            ->sortTable('title', 'asc')
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_sort_by_created_at(): void
    {
        NewsletterTemplate::factory()->create();
        NewsletterTemplate::factory()->create();

        Livewire::test(ManageTemplates::class)
            ->sortTable('created_at', 'desc')
            ->assertSuccessful();
    }

    #[Test]
    public function it_bulk_delete_removes_records(): void
    {
        $templates = NewsletterTemplate::factory()->count(3)->create();

        Livewire::test(ManageTemplates::class)
            ->selectTableRecords($templates)
            ->callTableBulkAction('delete');

        foreach ($templates as $template) {
            $this->assertDatabaseMissing('newsletter_templates', [
                'id' => $template->id,
            ]);
        }
    }

    #[Test]
    public function it_template_has_required_attributes(): void
    {
        $template = NewsletterTemplate::factory()->create([
            'title' => 'Test Template',
        ]);

        $this->assertNotNull($template->title);
    }
}
