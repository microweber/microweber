<?php

namespace MicroweberPackages\Translation\Tests;

use MicroweberPackages\Translation\Http\Controllers\TranslationController;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use MicroweberPackages\Translation\TranslationImport;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;

class TranslationControllerTest extends TestCase
{
    #[Test]
    public function it_can_list_translations(): void
    {
        $key = TranslationKey::create([
            'translation_key' => 'Hello',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Hola',
            'translation_locale' => 'es_ES',
        ]);

        $controller = new TranslationController();
        $request = new Request(['namespace' => '*', 'all' => true]);

        $result = $controller->index($request);

        $this->assertArrayHasKey('results', $result);
        $this->assertArrayHasKey('Hello', $result['results']);
    }

    #[Test]
    public function it_can_save_translations(): void
    {
        $controller = new TranslationController();

        $translationsData = [
            'translations' => [
                [
                    'en_US' => [
                        'translation_key' => 'Test Key',
                        'translation_text' => 'Test Value',
                        'translation_group' => '*',
                        'translation_namespace' => '*',
                    ],
                ],
            ],
        ];

        $request = new Request();
        $request->merge(['translations' => $translationsData]);

        $result = $controller->save($request);

        $this->assertArrayHasKey('success', $result);
        $this->assertDatabaseHas('translation_keys', ['translation_key' => 'Test Key']);
    }

    #[Test]
    public function it_can_export_translations(): void
    {
        $key = TranslationKey::create([
            'translation_key' => 'Export Test',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Export Value',
            'translation_locale' => 'en_US',
        ]);

        $controller = new TranslationController();
        $request = new Request([
            'namespace' => '*',
            'locale' => 'en_US',
            'format' => 'json',
        ]);

        $response = $controller->export($request);

        $data = json_decode($response->getContent(), true);
        $this->assertNotEmpty($data);

        $found = false;
        foreach ($data as $item) {
            if ($item['translation_key'] === 'Export Test') {
                $found = true;
                $this->assertEquals('Export Value', $item['translation_text']);
            }
        }
        $this->assertTrue($found);
    }

    #[Test]
    public function it_can_import_from_json(): void
    {
        $controller = new TranslationController();

        $translations = [
            [
                'translation_namespace' => '*',
                'translation_group' => '*',
                'translation_key' => 'Import Test',
                'translation_text' => 'Import Value',
                'translation_locale' => 'en_US',
            ],
        ];

        $request = new Request();
        $request->merge([
            'translations' => $translations,
            'replace_values' => 0,
        ]);

        $result = $controller->importFromJson($request);

        $this->assertArrayHasKey('success', $result);
        $this->assertDatabaseHas('translation_keys', ['translation_key' => 'Import Test']);
    }

    #[Test]
    public function it_can_list_available_languages(): void
    {
        $controller = new TranslationController();
        $result = $controller->availableLanguages();

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('en_US', $result);
    }

    #[Test]
    public function it_can_install_language(): void
    {
        $controller = new TranslationController();
        $request = new Request();
        $request->merge(['locale' => 'en_US']);

        $result = $controller->installLanguage($request);

        $this->assertArrayHasKey('success', $result);
    }

    #[Test]
    public function it_install_language_requires_locale(): void
    {
        $controller = new TranslationController();
        $request = new Request();

        $result = $controller->installLanguage($request);

        $this->assertArrayHasKey('error', $result);
    }
}