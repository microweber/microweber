<?php

namespace MicroweberPackages\Translation\Tests;

use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use PHPUnit\Framework\Attributes\Test;

class TranslationKeyModelTest extends TestCase
{
    #[Test]
    public function it_can_create_a_translation_key(): void
    {
        $key = TranslationKey::create([
            'translation_key' => 'Hello',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        $this->assertDatabaseHas('translation_keys', [
            'translation_key' => 'Hello',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);
        $this->assertNotNull($key->id);
    }

    #[Test]
    public function it_can_create_a_translation_text(): void
    {
        $key = TranslationKey::create([
            'translation_key' => 'Hello',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        $text = TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Hola',
            'translation_locale' => 'es_ES',
        ]);

        $this->assertDatabaseHas('translation_texts', [
            'translation_key_id' => $key->id,
            'translation_text' => 'Hola',
            'translation_locale' => 'es_ES',
        ]);
    }

    #[Test]
    public function it_key_has_many_texts(): void
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

        TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Bonjour',
            'translation_locale' => 'fr_FR',
        ]);

        $this->assertCount(2, $key->texts);
    }

    #[Test]
    public function it_text_belongs_to_key(): void
    {
        $key = TranslationKey::create([
            'translation_key' => 'Hello',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        $text = TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Hola',
            'translation_locale' => 'es_ES',
        ]);

        $this->assertEquals($key->id, $text->translationKey->id);
    }

    #[Test]
    public function it_can_get_namespaces(): void
    {
        TranslationKey::create([
            'translation_key' => 'key1',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        TranslationKey::create([
            'translation_key' => 'key2',
            'translation_namespace' => 'modules-shop',
            'translation_group' => '*',
        ]);

        $namespaces = TranslationKey::getNamespaces();

        $this->assertArrayHasKey('global', $namespaces);
        $this->assertArrayHasKey('modules-shop', $namespaces);
    }

    #[Test]
    public function it_can_get_grouped_translations(): void
    {
        $key1 = TranslationKey::create([
            'translation_key' => 'Hello',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        TranslationText::create([
            'translation_key_id' => $key1->id,
            'translation_text' => 'Hola',
            'translation_locale' => 'es_ES',
        ]);

        $key2 = TranslationKey::create([
            'translation_key' => 'Goodbye',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        TranslationText::create([
            'translation_key_id' => $key2->id,
            'translation_text' => 'Adiós',
            'translation_locale' => 'es_ES',
        ]);

        $result = TranslationKey::getGroupedTranslations([
            'translation_namespace' => '*',
            'all' => true,
        ]);

        $this->assertArrayHasKey('results', $result);
        $this->assertArrayHasKey('Hello', $result['results']);
        $this->assertArrayHasKey('Goodbye', $result['results']);
        $this->assertEquals('Hola', $result['results']['Hello']['es_ES']);
    }

    #[Test]
    public function it_can_search_grouped_translations(): void
    {
        $key1 = TranslationKey::create([
            'translation_key' => 'Hello World',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        $key2 = TranslationKey::create([
            'translation_key' => 'Goodbye',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        $result = TranslationKey::getGroupedTranslations([
            'translation_namespace' => '*',
            'search' => 'Hello',
            'all' => true,
        ]);

        $this->assertArrayHasKey('Hello World', $result['results']);
        $this->assertArrayNotHasKey('Goodbye', $result['results']);
    }

    #[Test]
    public function it_factory_creates_valid_key(): void
    {
        $key = TranslationKey::factory()->create();

        $this->assertNotNull($key->id);
        $this->assertNotEmpty($key->translation_key);
        $this->assertNotEmpty($key->translation_group);
    }

    #[Test]
    public function it_can_set_translation_value_default(): void
    {
        $key = TranslationKey::create([
            'translation_key' => 'greeting',
            'translation_namespace' => '*',
            'translation_group' => '*',
            'translation_value_default' => 'Hello there!',
        ]);

        $this->assertEquals('Hello there!', $key->translation_value_default);
    }
}