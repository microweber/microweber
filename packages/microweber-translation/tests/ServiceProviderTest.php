<?php

namespace MicroweberPackages\Translation\Tests;

use MicroweberPackages\Translation\Translator;
use MicroweberPackages\Translation\TranslationLoader;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_translator_as_custom_class(): void
    {
        $translator = app('translator');
        $this->assertInstanceOf(Translator::class, $translator);
    }

    #[Test]
    public function it_binds_translator_contract(): void
    {
        $translator = app(\Illuminate\Contracts\Translation\Translator::class);
        $this->assertInstanceOf(Translator::class, $translator);
    }

    #[Test]
    public function it_migrations_create_tables(): void
    {
        $this->assertTrue(\Schema::hasTable('translation_keys'));
        $this->assertTrue(\Schema::hasTable('translation_texts'));
    }

    #[Test]
    public function it_translation_keys_table_has_correct_columns(): void
    {
        $this->assertTrue(\Schema::hasColumn('translation_keys', 'id'));
        $this->assertTrue(\Schema::hasColumn('translation_keys', 'translation_key'));
        $this->assertTrue(\Schema::hasColumn('translation_keys', 'translation_namespace'));
        $this->assertTrue(\Schema::hasColumn('translation_keys', 'translation_group'));
        $this->assertTrue(\Schema::hasColumn('translation_keys', 'translation_value_default'));
    }

    #[Test]
    public function it_translation_texts_table_has_correct_columns(): void
    {
        $this->assertTrue(\Schema::hasColumn('translation_texts', 'id'));
        $this->assertTrue(\Schema::hasColumn('translation_texts', 'translation_key_id'));
        $this->assertTrue(\Schema::hasColumn('translation_texts', 'translation_text'));
        $this->assertTrue(\Schema::hasColumn('translation_texts', 'translation_locale'));
    }

    #[Test]
    public function it_config_is_loaded(): void
    {
        $config = config('microweber-translation');
        $this->assertNotNull($config);
        $this->assertArrayHasKey('use_database', $config);
        $this->assertArrayHasKey('auto_save_new_keys', $config);
    }
}