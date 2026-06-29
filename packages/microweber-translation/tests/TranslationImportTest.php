<?php

namespace MicroweberPackages\Translation\Tests;

use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use MicroweberPackages\Translation\TranslationImport;
use PHPUnit\Framework\Attributes\Test;

class TranslationImportTest extends TestCase
{
    #[Test]
    public function it_can_import_translations(): void
    {
        $translations = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Hola',
                'translation_locale' => 'es_ES',
            ],
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Goodbye',
                'translation_text' => 'Adiós',
                'translation_locale' => 'es_ES',
            ],
        ];

        $import = new TranslationImport();
        $result = $import->import($translations);

        $this->assertArrayHasKey('success', $result);
        $this->assertDatabaseHas('translation_keys', ['translation_key' => 'Hello']);
        $this->assertDatabaseHas('translation_keys', ['translation_key' => 'Goodbye']);
        $this->assertDatabaseHas('translation_texts', [
            'translation_text' => 'Hola',
            'translation_locale' => 'es_ES',
        ]);
    }

    #[Test]
    public function it_rejects_invalid_import_data(): void
    {
        $import = new TranslationImport();

        // Empty array
        $result = $import->import([]);
        $this->assertArrayHasKey('error', $result);

        // Missing required fields
        $result = $import->import([['translation_key' => 'Hello']]);
        $this->assertArrayHasKey('error', $result);
    }

    #[Test]
    public function it_can_replace_existing_translations(): void
    {
        $translations = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Hola',
                'translation_locale' => 'es_ES',
            ],
        ];

        $import = new TranslationImport();
        $import->import($translations);

        // Now import again with replace
        $translations[0]['translation_text'] = 'Hola Mundo';
        $import2 = new TranslationImport();
        $import2->replaceTexts(true);
        $result = $import2->import($translations);

        $this->assertArrayHasKey('success', $result);

        $key = TranslationKey::where('translation_key', 'Hello')->first();
        $text = TranslationText::where('translation_key_id', $key->id)
            ->where('translation_locale', 'es_ES')
            ->first();

        $this->assertEquals('Hola Mundo', $text->translation_text);
    }

    #[Test]
    public function it_does_not_replace_without_flag(): void
    {
        $translations = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Hola',
                'translation_locale' => 'es_ES',
            ],
        ];

        $import = new TranslationImport();
        $import->import($translations);

        // Import again without replace — should not overwrite
        $translations[0]['translation_text'] = 'Changed';
        $import2 = new TranslationImport();
        $import2->replaceTexts(false);
        $import2->import($translations);

        $key = TranslationKey::where('translation_key', 'Hello')->first();
        $text = TranslationText::where('translation_key_id', $key->id)
            ->where('translation_locale', 'es_ES')
            ->first();

        $this->assertEquals('Hola', $text->translation_text);
    }

    #[Test]
    public function it_handles_duplicate_keys_in_import(): void
    {
        $translations = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Hola 1',
                'translation_locale' => 'es_ES',
            ],
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Hola 2',
                'translation_locale' => 'es_ES',
            ],
        ];

        $import = new TranslationImport();
        $result = $import->import($translations);

        $this->assertArrayHasKey('success', $result);

        // Should only have one key
        $keys = TranslationKey::where('translation_key', 'Hello')->count();
        $this->assertEquals(1, $keys);
    }

    #[Test]
    public function it_can_import_multiple_locales_for_same_key(): void
    {
        $translations = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Hola',
                'translation_locale' => 'es_ES',
            ],
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Bonjour',
                'translation_locale' => 'fr_FR',
            ],
        ];

        $import = new TranslationImport();
        $result = $import->import($translations);

        $this->assertArrayHasKey('success', $result);

        $key = TranslationKey::where('translation_key', 'Hello')->first();
        $this->assertCount(2, $key->texts);

        $locales = $key->texts->pluck('translation_locale')->toArray();
        $this->assertContains('es_ES', $locales);
        $this->assertContains('fr_FR', $locales);
    }

    #[Test]
    public function it_trims_input_data(): void
    {
        $translations = [
            [
                'translation_namespace' => '  *  ',
                'translation_group' => '  *  ',
                'translation_key' => '  Hello  ',
                'translation_text' => '  Hola  ',
                'translation_locale' => '  es_ES  ',
            ],
        ];

        $import = new TranslationImport();
        $result = $import->import($translations);

        $this->assertArrayHasKey('success', $result);
        $this->assertDatabaseHas('translation_keys', ['translation_key' => 'Hello']);
    }

    #[Test]
    public function it_skips_empty_translation_keys(): void
    {
        // When the first item has an empty key, validation fails
        $translations = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => '',
                'translation_text' => 'Hola',
                'translation_locale' => 'es_ES',
            ],
        ];

        $import = new TranslationImport();
        $result = $import->import($translations);

        $this->assertArrayHasKey('error', $result);

        // When valid item comes first, empty keys are skipped during preparation
        $translations2 = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Hola',
                'translation_locale' => 'es_ES',
            ],
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => '',
                'translation_text' => 'Empty',
                'translation_locale' => 'es_ES',
            ],
        ];

        $import2 = new TranslationImport();
        $result2 = $import2->import($translations2);

        $this->assertArrayHasKey('success', $result2);
        $this->assertEquals(1, TranslationKey::count());
    }

    #[Test]
    public function it_can_import_with_different_namespaces(): void
    {
        $translations = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Hello',
                'translation_text' => 'Hola',
                'translation_locale' => 'es_ES',
            ],
            [
                'translation_namespace' => 'modules-shop',
                'translation_group' => '*',
                'translation_key' => 'Add to Cart',
                'translation_text' => 'Añadir al carrito',
                'translation_locale' => 'es_ES',
            ],
        ];

        $import = new TranslationImport();
        $result = $import->import($translations);

        $this->assertArrayHasKey('success', $result);
        $this->assertEquals(2, TranslationKey::count());
    }
}