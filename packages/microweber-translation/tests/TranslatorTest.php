<?php

namespace MicroweberPackages\Translation\Tests;

use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use MicroweberPackages\Translation\Translator;
use PHPUnit\Framework\Attributes\Test;

class TranslatorTest extends TestCase
{
    #[Test]
    public function it_translator_is_custom_instance(): void
    {
        $translator = app('translator');
        $this->assertInstanceOf(Translator::class, $translator);
    }

    #[Test]
    public function it_tracks_new_keys(): void
    {
        $translator = app('translator');
        $translator->clearNewKeys();

        // Translate a key that doesn't exist — should track it
        $result = $translator->get('test_new_key_' . uniqid());

        $newKeys = $translator->getNewKeys();
        $this->assertNotEmpty($newKeys);
    }

    #[Test]
    public function it_returns_key_when_no_translation_found(): void
    {
        $translator = app('translator');

        $result = $translator->get('NonExistentKey');

        $this->assertEquals('NonExistentKey', $result);
    }

    #[Test]
    public function it_can_clear_new_keys(): void
    {
        $translator = app('translator');
        $translator->clearNewKeys();

        $translator->get('some_new_key');

        $this->assertNotEmpty($translator->getNewKeys());

        $translator->clearNewKeys();

        $this->assertEmpty($translator->getNewKeys());
    }

    #[Test]
    public function it_does_not_track_empty_keys(): void
    {
        $translator = app('translator');
        $translator->clearNewKeys();

        $translator->get('');
        $translator->get(' ');

        $this->assertEmpty($translator->getNewKeys());
    }

    #[Test]
    public function it_translates_from_database(): void
    {
        // Create a translation in the database
        $key = TranslationKey::create([
            'translation_key' => 'db_test_greeting',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Hello from DB',
            'translation_locale' => 'en',
        ]);

        // Get a fresh translator to pick up DB changes
        $this->app->forgetInstance('translator');
        $translator = app('translator');

        $result = $translator->get('db_test_greeting', [], 'en');

        $this->assertEquals('Hello from DB', $result);
    }

    #[Test]
    public function it_supports_replacements(): void
    {
        $key = TranslationKey::create([
            'translation_key' => 'greeting_with_name',
            'translation_namespace' => '*',
            'translation_group' => '*',
        ]);

        TranslationText::create([
            'translation_key_id' => $key->id,
            'translation_text' => 'Hello :name!',
            'translation_locale' => 'en',
        ]);

        $this->app->forgetInstance('translator');
        $translator = app('translator');

        $result = $translator->get('greeting_with_name', ['name' => 'World'], 'en');

        $this->assertEquals('Hello World!', $result);
    }
}