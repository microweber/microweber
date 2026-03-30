<?php

namespace Modules\Faq\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Faq\Filament\Resources\FaqModuleResource;
use Modules\Faq\Filament\Resources\FaqModuleResource\Pages\ListFaqs;
use Modules\Faq\Filament\Resources\FaqModuleResource\Pages\CreateFaq;
use Modules\Faq\Filament\Resources\FaqModuleResource\Pages\EditFaq;
use Modules\Faq\Models\Faq;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FaqModuleResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        DB::table('faqs')->delete();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListFaqs::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $faq = Faq::factory()->create(['question' => 'UniqueTestFaq_' . uniqid() . '?']);

        Livewire::test(ListFaqs::class)
            ->searchTable($faq->question)
            ->assertCanSeeTableRecords([$faq]);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreateFaq::class)
            ->fillForm([
                'question' => 'Test Question?',
                'answer' => 'Test Answer',
                'position' => 1,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', ['question' => 'Test Question?']);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $faq = Faq::factory()->create(['question' => 'Original?']);
        Livewire::test(EditFaq::class, ['record' => $faq->id])
            ->fillForm(['question' => 'Updated?'])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', ['id' => $faq->id, 'question' => 'Updated?']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $faq = Faq::factory()->create();
        Livewire::test(ListFaqs::class)->callTableAction('delete', $faq);
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListFaqs::class)
            ->assertTableColumnExists('question')
            ->assertTableColumnExists('answer')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function it_can_sort_by_position(): void
    {
        Faq::factory()->create(['position' => 1]);
        Faq::factory()->create(['position' => 2]);

        Livewire::test(ListFaqs::class)
            ->sortTable('position', 'asc')
            ->assertSuccessful();
    }
}
