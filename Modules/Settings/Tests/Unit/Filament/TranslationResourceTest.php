<?php

namespace Modules\Settings\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Settings\Filament\Resources\TranslationResource;
use Modules\Settings\Filament\Resources\TranslationResource\Pages\ListTranslations;
use Modules\Settings\Filament\Resources\TranslationResource\Pages\CreateTranslation;
use Modules\Settings\Filament\Resources\TranslationResource\Pages\EditTranslation;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TranslationResourceTest extends TestCase
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
        Livewire::test(ListTranslations::class)->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $translations = TranslationKey::factory()->count(3)->create();
        Livewire::test(ListTranslations::class)->assertCanSeeTableRecords($translations);
    }

    #[Test]
    public function test_create_page_saves_new_record(): void
    {
        Livewire::test(CreateTranslation::class)
            ->fillForm([
                'translation_key' => 'test.key',
                'translation_namespace' => 'test',
                'translation_group' => 'default',
                'translation_value_default' => 'Test Value',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('translation_keys', ['translation_key' => 'test.key']);
    }

    #[Test]
    public function test_edit_page_updates_record(): void
    {
        $key = TranslationKey::factory()->create(['translation_key' => 'original.key']);
        Livewire::test(EditTranslation::class, ['record' => $key->id])
            ->fillForm(['translation_key' => 'updated.key'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('translation_keys', ['id' => $key->id, 'translation_key' => 'updated.key']);
    }

    #[Test]
    public function test_delete_action_removes_record(): void
    {
        $key = TranslationKey::factory()->create();
        Livewire::test(ListTranslations::class)->callTableAction('delete', $key);
        $this->assertDatabaseMissing('translation_keys', ['id' => $key->id]);
    }

    #[Test]
    public function test_table_has_required_columns(): void
    {
        Livewire::test(ListTranslations::class)
            ->assertTableColumnExists('translation_key')
            ->assertTableColumnExists('translation_namespace')
            ->assertTableColumnExists('translation_group')
            ->assertTableColumnExists('translation_value_default');
    }

    #[Test]
    public function test_bulk_add_translation_exists(): void
    {
        Livewire::test(ListTranslations::class)->assertTableBulkActionExists('add_translation');
    }

    #[Test]
    public function test_sorting_by_column_changes_order(): void
    {
        // Create translations with different keys for sorting
        $translationA = TranslationKey::factory()->create([
            'translation_key' => 'alpha.key',
            'translation_namespace' => 'test',
            'translation_group' => 'default',
        ]);
        $translationB = TranslationKey::factory()->create([
            'translation_key' => 'beta.key',
            'translation_namespace' => 'test',
            'translation_group' => 'default',
        ]);
        $translationC = TranslationKey::factory()->create([
            'translation_key' => 'charlie.key',
            'translation_namespace' => 'test',
            'translation_group' => 'default',
        ]);

        // Test sorting by translation_key ascending
        Livewire::test(ListTranslations::class)
            ->sortTable('translation_key', 'asc')
            ->assertCanSeeTableRecords([$translationA, $translationB, $translationC], inOrder: true);

        // Test sorting by translation_key descending
        Livewire::test(ListTranslations::class)
            ->sortTable('translation_key', 'desc')
            ->assertCanSeeTableRecords([$translationC, $translationB, $translationA], inOrder: true);

        // Test sorting by translation_namespace ascending
        Livewire::test(ListTranslations::class)
            ->sortTable('translation_namespace', 'asc')
            ->assertCanSeeTableRecords([$translationA, $translationB, $translationC], inOrder: true);
    }

    #[Test]
    public function test_bulk_delete_removes_selected_records(): void
    {
        $translation1 = TranslationKey::factory()->create(['translation_key' => 'key.1']);
        $translation2 = TranslationKey::factory()->create(['translation_key' => 'key.2']);
        $translation3 = TranslationKey::factory()->create(['translation_key' => 'key.3']);

        // Select and bulk delete first two translations
        Livewire::test(ListTranslations::class)
            ->callTableBulkAction('delete', [$translation1, $translation2])
            ->assertHasNoTableBulkActionErrors();

        // Assert deleted records are gone
        $this->assertDatabaseMissing('translation_keys', ['id' => $translation1->id]);
        $this->assertDatabaseMissing('translation_keys', ['id' => $translation2->id]);

        // Assert third translation still exists
        $this->assertDatabaseHas('translation_keys', ['id' => $translation3->id]);
    }
}
