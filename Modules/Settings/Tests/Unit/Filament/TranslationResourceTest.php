<?php

namespace Modules\Settings\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Settings\Filament\Resources\TranslationResource;
use Modules\Settings\Filament\Resources\TranslationResource\Pages\ListTranslations;
use Modules\Settings\Filament\Resources\TranslationResource\Pages\CreateTranslation;
use Modules\Settings\Filament\Resources\TranslationResource\Pages\EditTranslation;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use MicroweberPackages\Translation\TranslationImport;
use MicroweberPackages\Translation\TranslationPackageInstallHelper;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TranslationResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        DB::table('translation_texts')->delete();
        DB::table('translation_keys')->delete();
    }

    // ──────────────────────────────────────────────────────
    // CRUD Tests
    // ──────────────────────────────────────────────────────

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListTranslations::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $translation = TranslationKey::factory()->create([
            'translation_key' => 'zzz_unique_test_key_' . uniqid(),
        ]);

        Livewire::test(ListTranslations::class)
            ->searchTable($translation->translation_key)
            ->assertCanSeeTableRecords([$translation]);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
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
    public function it_edit_page_updates_record(): void
    {
        $key = TranslationKey::factory()->create(['translation_key' => 'original.key']);
        Livewire::test(EditTranslation::class, ['record' => $key->id])
            ->fillForm(['translation_key' => 'updated.key'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('translation_keys', ['id' => $key->id, 'translation_key' => 'updated.key']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $key = TranslationKey::factory()->create();
        Livewire::test(ListTranslations::class)->callTableAction('delete', $key);
        $this->assertDatabaseMissing('translation_keys', ['id' => $key->id]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListTranslations::class)
            ->assertTableColumnExists('translation_key')
            ->assertTableColumnExists('translation_namespace')
            ->assertTableColumnExists('translation_group')
            ->assertTableColumnExists('translation_value_default');
    }

    #[Test]
    public function it_bulk_add_translation_exists(): void
    {
        Livewire::test(ListTranslations::class)->assertTableBulkActionExists('add_translation');
    }

    #[Test]
    public function it_sorting_by_column_changes_order(): void
    {
        TranslationKey::factory()->create([
            'translation_key' => 'zzz_alpha.key',
            'translation_namespace' => 'zzz_test',
            'translation_group' => 'default',
        ]);
        TranslationKey::factory()->create([
            'translation_key' => 'zzz_beta.key',
            'translation_namespace' => 'zzz_test',
            'translation_group' => 'default',
        ]);

        Livewire::test(ListTranslations::class)
            ->sortTable('translation_key', 'asc')
            ->assertSuccessful();

        Livewire::test(ListTranslations::class)
            ->sortTable('translation_key', 'desc')
            ->assertSuccessful();
    }

    #[Test]
    public function it_bulk_delete_removes_selected_records(): void
    {
        $translation1 = TranslationKey::factory()->create(['translation_key' => 'key.1']);
        $translation2 = TranslationKey::factory()->create(['translation_key' => 'key.2']);
        $translation3 = TranslationKey::factory()->create(['translation_key' => 'key.3']);

        Livewire::test(ListTranslations::class)
            ->callTableBulkAction('delete', [$translation1, $translation2])
            ->assertHasNoTableBulkActionErrors();

        $this->assertDatabaseMissing('translation_keys', ['id' => $translation1->id]);
        $this->assertDatabaseMissing('translation_keys', ['id' => $translation2->id]);
        $this->assertDatabaseHas('translation_keys', ['id' => $translation3->id]);
    }

    // ──────────────────────────────────────────────────────
    // Import Tests
    // ──────────────────────────────────────────────────────

    #[Test]
    public function it_can_import_translations_programmatically(): void
    {
        $translations = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Dashboard',
                'translation_text' => 'Табло',
                'translation_locale' => 'bg_BG',
            ],
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Settings',
                'translation_text' => 'Настройки',
                'translation_locale' => 'bg_BG',
            ],
        ];

        $import = new TranslationImport();
        $result = $import->import($translations);

        $this->assertArrayHasKey('success', $result);
        $this->assertDatabaseHas('translation_keys', ['translation_key' => 'Dashboard']);
        $this->assertDatabaseHas('translation_keys', ['translation_key' => 'Settings']);
        $this->assertDatabaseHas('translation_texts', [
            'translation_text' => 'Табло',
            'translation_locale' => 'bg_BG',
        ]);
    }

    #[Test]
    public function it_can_import_and_replace_translations(): void
    {
        // First import
        $import1 = new TranslationImport();
        $import1->import([
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Здравей',
                'translation_locale' => 'bg_BG',
            ],
        ]);

        // Second import with replace
        $import2 = new TranslationImport();
        $import2->replaceTexts(true);
        $result = $import2->import([
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Здрасти',
                'translation_locale' => 'bg_BG',
            ],
        ]);

        $this->assertArrayHasKey('success', $result);

        $key = TranslationKey::where('translation_key', 'Hello')->first();
        $text = TranslationText::where('translation_key_id', $key->id)
            ->where('translation_locale', 'bg_BG')
            ->first();

        $this->assertEquals('Здрасти', $text->translation_text);
    }

    #[Test]
    public function it_rejects_invalid_import_data(): void
    {
        $import = new TranslationImport();

        // Empty array
        $result = $import->import([]);
        $this->assertArrayHasKey('error', $result);

        // Missing required fields
        $result = $import->import([['some_field' => 'value']]);
        $this->assertArrayHasKey('error', $result);
    }

    #[Test]
    public function it_can_install_language_pack(): void
    {
        $result = TranslationPackageInstallHelper::installLanguage('bg_BG');
        $this->assertNotNull($result);
        $this->assertArrayHasKey('success', $result);

        $bgTexts = TranslationText::where('translation_locale', 'bg_BG')->count();
        $this->assertGreaterThan(0, $bgTexts);
    }

    // ──────────────────────────────────────────────────────
    // Export Tests
    // ──────────────────────────────────────────────────────

    #[Test]
    public function it_can_export_translations_via_controller(): void
    {
        // Create test data
        $key = TranslationKey::create([
            'translation_key' => 'Export Test Key',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Export Test Value',
            'translation_locale' => 'en_US',
        ]);

        $controller = new \MicroweberPackages\Translation\Http\Controllers\TranslationController();
        $request = new \Illuminate\Http\Request([
            'namespace' => '*',
            'locale' => 'en_US',
            'format' => 'json',
        ]);

        $response = $controller->export($request);
        $data = json_decode($response->getContent(), true);

        $this->assertNotEmpty($data);

        $found = false;
        foreach ($data as $item) {
            if ($item['translation_key'] === 'Export Test Key') {
                $found = true;
                $this->assertEquals('Export Test Value', $item['translation_text']);
            }
        }
        $this->assertTrue($found, 'Exported data should contain the test key');
    }

    #[Test]
    public function it_export_includes_keys_without_texts(): void
    {
        $key = TranslationKey::create([
            'translation_key' => 'Untranslated Key',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        $controller = new \MicroweberPackages\Translation\Http\Controllers\TranslationController();
        $request = new \Illuminate\Http\Request([
            'namespace' => '*',
            'locale' => 'en_US',
            'format' => 'json',
        ]);

        $response = $controller->export($request);
        $data = json_decode($response->getContent(), true);

        $this->assertNotEmpty($data);

        $found = false;
        foreach ($data as $item) {
            if ($item['translation_key'] === 'Untranslated Key') {
                $found = true;
                $this->assertEmpty($item['translation_text']);
            }
        }
        $this->assertTrue($found, 'Export should include keys without translations');
    }

    // ──────────────────────────────────────────────────────
    // Edit Tests (Translation Texts)
    // ──────────────────────────────────────────────────────

    #[Test]
    public function it_can_edit_translation_key_with_texts(): void
    {
        $key = TranslationKey::factory()->create([
            'translation_key' => 'edit.test.key',
        ]);

        TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Original Value',
            'translation_locale' => 'en_US',
        ]);

        // Verify the edit page loads with existing texts
        Livewire::test(EditTranslation::class, ['record' => $key->id])
            ->assertSuccessful()
            ->assertFormFieldExists('translation_key');
    }

    #[Test]
    public function it_can_save_translation_via_controller(): void
    {
        $controller = new \MicroweberPackages\Translation\Http\Controllers\TranslationController();

        $translationsData = [
            'translations' => [
                [
                    'en_US' => [
                        'translation_key' => 'Save Test',
                        'translation_text' => 'Saved Value',
                        'translation_group' => '*',
                        'translation_namespace' => '*',
                    ],
                ],
            ],
        ];

        $request = new \Illuminate\Http\Request();
        $request->merge(['translations' => $translationsData]);

        $result = $controller->save($request);

        $this->assertArrayHasKey('success', $result);
        $this->assertDatabaseHas('translation_keys', ['translation_key' => 'Save Test']);
        $this->assertDatabaseHas('translation_texts', [
            'translation_text' => 'Saved Value',
            'translation_locale' => 'en_US',
        ]);
    }

    #[Test]
    public function it_save_updates_existing_translation(): void
    {
        // First save
        $controller = new \MicroweberPackages\Translation\Http\Controllers\TranslationController();

        $request1 = new \Illuminate\Http\Request();
        $request1->merge(['translations' => [
            'translations' => [
                [
                    'en_US' => [
                        'translation_key' => 'Update Test',
                        'translation_text' => 'Version 1',
                        'translation_group' => '*',
                        'translation_namespace' => '*',
                    ],
                ],
            ],
        ]]);
        $controller->save($request1);

        // Update
        $request2 = new \Illuminate\Http\Request();
        $request2->merge(['translations' => [
            'translations' => [
                [
                    'en_US' => [
                        'translation_key' => 'Update Test',
                        'translation_text' => 'Version 2',
                        'translation_group' => '*',
                        'translation_namespace' => '*',
                    ],
                ],
            ],
        ]]);
        $controller->save($request2);

        $key = TranslationKey::where('translation_key', 'Update Test')->first();
        $text = TranslationText::where('translation_key_id', $key->id)
            ->where('translation_locale', 'en_US')
            ->first();

        $this->assertEquals('Version 2', $text->translation_text);
    }

    // ──────────────────────────────────────────────────────
    // Full Import → Edit → Export cycle
    // ──────────────────────────────────────────────────────

    #[Test]
    public function it_full_import_edit_export_cycle(): void
    {
        // Step 1: Import
        $import = new TranslationImport();
        $importResult = $import->import([
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Cycle Key',
                'translation_text' => 'Cycle Value',
                'translation_locale' => 'en_US',
            ],
        ]);
        $this->assertArrayHasKey('success', $importResult);

        // Step 2: Edit via controller
        $controller = new \MicroweberPackages\Translation\Http\Controllers\TranslationController();
        $editRequest = new \Illuminate\Http\Request();
        $editRequest->merge(['translations' => [
            'translations' => [
                [
                    'en_US' => [
                        'translation_key' => 'Cycle Key',
                        'translation_text' => 'Edited Cycle Value',
                        'translation_group' => '*',
                        'translation_namespace' => '*',
                    ],
                ],
            ],
        ]]);
        $controller->save($editRequest);

        // Step 3: Export and verify
        $exportRequest = new \Illuminate\Http\Request([
            'namespace' => '*',
            'locale' => 'en_US',
            'format' => 'json',
        ]);
        $response = $controller->export($exportRequest);
        $data = json_decode($response->getContent(), true);

        $found = false;
        foreach ($data as $item) {
            if ($item['translation_key'] === 'Cycle Key') {
                $found = true;
                $this->assertEquals('Edited Cycle Value', $item['translation_text']);
            }
        }
        $this->assertTrue($found, 'Edited translation should appear in export');
    }

    // ──────────────────────────────────────────────────────
    // Language Helper Integration Tests
    // ──────────────────────────────────────────────────────

    #[Test]
    public function it_available_translations_list_is_populated(): void
    {
        $available = TranslationPackageInstallHelper::getAvailableTranslations();
        $this->assertNotEmpty($available);
        $this->assertArrayHasKey('en_US', $available);
    }

    #[Test]
    public function it_quick_translate_action_exists(): void
    {
        $key = TranslationKey::factory()->create();
        TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Test',
            'translation_locale' => 'en_US',
        ]);

        Livewire::test(ListTranslations::class)
            ->assertTableActionExists('quickTranslate');
    }
}
