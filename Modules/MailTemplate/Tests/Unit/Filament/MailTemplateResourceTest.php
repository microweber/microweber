<?php

namespace Modules\MailTemplate\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\ListMailTemplates;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\CreateMailTemplate;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\EditMailTemplate;
use Modules\MailTemplate\Models\MailTemplate;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MailTemplateResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListMailTemplates::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $templates = MailTemplate::factory()->count(3)->create();
        Livewire::test(ListMailTemplates::class)->assertCanSeeTableRecords($templates);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreateMailTemplate::class)
            ->fillForm([
                'name' => 'Test Template',
                'type' => 'welcome',
                'from_name' => 'Test Sender',
                'from_email' => 'test@example.com',
                'subject' => 'Test Subject',
                'message' => '<p>Test message</p>',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('mail_templates', ['name' => 'Test Template']);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $template = MailTemplate::factory()->create(['name' => 'Original']);
        Livewire::test(EditMailTemplate::class, ['record' => $template->id])
            ->fillForm(['name' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('mail_templates', ['id' => $template->id, 'name' => 'Updated']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $template = MailTemplate::factory()->create();
        Livewire::test(ListMailTemplates::class)->callTableAction('delete', $template);
        $this->assertDatabaseMissing('mail_templates', ['id' => $template->id]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListMailTemplates::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('type')
            ->assertTableColumnExists('subject')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function it_sorting_by_column_changes_order(): void
    {
        // Create templates with different attributes for sorting
        $templateA = MailTemplate::factory()->create([
            'name' => 'Alpha Template',
            'type' => 'welcome',
            'subject' => 'A Subject',
            'created_at' => now()->subDays(5),
        ]);
        $templateB = MailTemplate::factory()->create([
            'name' => 'Beta Template',
            'type' => 'order',
            'subject' => 'B Subject',
            'created_at' => now()->subDays(3),
        ]);
        $templateC = MailTemplate::factory()->create([
            'name' => 'Charlie Template',
            'type' => 'notification',
            'subject' => 'C Subject',
            'created_at' => now()->subDays(1),
        ]);

        // Test sorting by name ascending
        Livewire::test(ListMailTemplates::class)
            ->sortTable('name', 'asc')
            ->assertCanSeeTableRecords([$templateA, $templateB, $templateC], inOrder: true);

        // Test sorting by name descending
        Livewire::test(ListMailTemplates::class)
            ->sortTable('name', 'desc')
            ->assertCanSeeTableRecords([$templateC, $templateB, $templateA], inOrder: true);

        // Test sorting by created_at descending (default)
        Livewire::test(ListMailTemplates::class)
            ->sortTable('created_at', 'desc')
            ->assertCanSeeTableRecords([$templateC, $templateB, $templateA], inOrder: true);
    }

    #[Test]
    public function it_filter_by_boolean_field(): void
    {
        // Create templates with different active statuses
        $activeTemplate = MailTemplate::factory()->create([
            'name' => 'Active Template',
            'is_active' => true,
        ]);
        $inactiveTemplate = MailTemplate::factory()->create([
            'name' => 'Inactive Template',
            'is_active' => false,
        ]);

        // Filter by active status
        Livewire::test(ListMailTemplates::class)
            ->filterTable('is_active', true)
            ->assertCanSeeTableRecords([$activeTemplate])
            ->assertCanNotSeeTableRecords([$inactiveTemplate]);

        // Filter by inactive status
        Livewire::test(ListMailTemplates::class)
            ->filterTable('is_active', false)
            ->assertCanSeeTableRecords([$inactiveTemplate])
            ->assertCanNotSeeTableRecords([$activeTemplate]);
    }

    #[Test]
    public function it_bulk_delete_removes_selected_records(): void
    {
        $template1 = MailTemplate::factory()->create(['name' => 'Template 1']);
        $template2 = MailTemplate::factory()->create(['name' => 'Template 2']);
        $template3 = MailTemplate::factory()->create(['name' => 'Template 3']);

        // Select and bulk delete first two templates
        Livewire::test(ListMailTemplates::class)
            ->callTableBulkAction('delete', [$template1, $template2])
            ->assertHasNoTableBulkActionErrors();

        // Assert deleted records are gone
        $this->assertDatabaseMissing('mail_templates', ['id' => $template1->id]);
        $this->assertDatabaseMissing('mail_templates', ['id' => $template2->id]);

        // Assert third template still exists
        $this->assertDatabaseHas('mail_templates', ['id' => $template3->id]);
    }
}
