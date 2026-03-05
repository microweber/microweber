<?php

namespace Modules\MailTemplate\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\ListMailTemplates;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\CreateMailTemplate;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\EditMailTemplate;
use Modules\MailTemplate\Models\MailTemplate;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MailTemplateResourceTest extends TestCase
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
        $user = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($user);
        return $user;
    }

    #[Test]
    public function test_index_page_loads_without_errors(): void
    {
        Livewire::test(ListMailTemplates::class)->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $templates = MailTemplate::factory()->count(3)->create();
        Livewire::test(ListMailTemplates::class)->assertCanSeeTableRecords($templates);
    }

    #[Test]
    public function test_create_page_saves_new_record(): void
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
    public function test_edit_page_updates_record(): void
    {
        $template = MailTemplate::factory()->create(['name' => 'Original']);
        Livewire::test(EditMailTemplate::class, ['record' => $template->id])
            ->fillForm(['name' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('mail_templates', ['id' => $template->id, 'name' => 'Updated']);
    }

    #[Test]
    public function test_delete_action_removes_record(): void
    {
        $template = MailTemplate::factory()->create();
        Livewire::test(ListMailTemplates::class)->callTableAction('delete', $template);
        $this->assertDatabaseMissing('mail_templates', ['id' => $template->id]);
    }

    #[Test]
    public function test_table_has_required_columns(): void
    {
        Livewire::test(ListMailTemplates::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('type')
            ->assertTableColumnExists('subject')
            ->assertTableColumnExists('is_active');
    }
}
