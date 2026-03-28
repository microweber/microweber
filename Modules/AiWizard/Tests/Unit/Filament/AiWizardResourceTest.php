<?php

namespace Modules\AiWizard\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\AiWizard\Filament\Admin\AiWizardResource;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\ListAiWizardPages;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\CreateAiWizardPage;
use Modules\Content\Models\Content;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AiWizardResourceTest extends TestCase
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
        Livewire::test(ListAiWizardPages::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $pages = Content::factory()->count(3)->create(['content_type' => 'page']);
        Livewire::test(ListAiWizardPages::class)->assertCanSeeTableRecords($pages);
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(CreateAiWizardPage::class)->assertSuccessful()->assertFormExists();
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreateAiWizardPage::class)
            ->fillForm([
                'title' => 'AI Generated Page',
                'description' => 'Test description',
                'content_type' => 'page',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('content', ['title' => 'AI Generated Page']);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListAiWizardPages::class)
            ->assertTableColumnExists('title')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function it_pages_exist(): void
    {
        $pages = AiWizardResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
        $this->assertArrayHasKey('design', $pages);
    }
}
