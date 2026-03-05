<?php

namespace Modules\Faq\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Faq\Filament\Resources\FaqModuleResource;
use Modules\Faq\Filament\Resources\FaqModuleResource\Pages\ListFaqs;
use Modules\Faq\Filament\Resources\FaqModuleResource\Pages\CreateFaq;
use Modules\Faq\Filament\Resources\FaqModuleResource\Pages\EditFaq;
use Modules\Faq\Models\Faq;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FaqModuleResourceTest extends TestCase
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
        Livewire::test(ListFaqs::class)->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $faqs = Faq::factory()->count(3)->create();
        Livewire::test(ListFaqs::class)->assertCanSeeTableRecords($faqs);
    }

    #[Test]
    public function test_create_page_saves_new_record(): void
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
    public function test_edit_page_updates_record(): void
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
    public function test_delete_action_removes_record(): void
    {
        $faq = Faq::factory()->create();
        Livewire::test(ListFaqs::class)->callTableAction('delete', $faq);
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    #[Test]
    public function test_table_has_required_columns(): void
    {
        Livewire::test(ListFaqs::class)
            ->assertTableColumnExists('question')
            ->assertTableColumnExists('answer')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function test_can_sort_by_position(): void
    {
        Faq::factory()->create(['position' => 1]);
        Faq::factory()->create(['position' => 2]);

        Livewire::test(ListFaqs::class)
            ->sortTable('position', 'asc')
            ->assertSuccessful();
    }
}
