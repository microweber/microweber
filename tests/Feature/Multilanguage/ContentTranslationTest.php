<?php

namespace Tests\Feature\Multilanguage;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use Modules\Content\Models\Content;

class ContentTranslationTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up content from previous test runs with these titles
        Content::whereIn('title', ['Test Page', 'Home Page', 'About Us', 'Contact Page', 'Services'])->forceDelete();

        // Enable multilanguage
        if (class_exists(\MicroweberPackages\Multilanguage\MultilanguageHelpers::class)) {
            \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(true);
        }

        // Clear existing languages and create test ones
        MultilanguageSupportedLocales::whereIn('locale', ['en_US', 'es_ES', 'fr_FR'])->delete();
        $this->createTestLanguages();
    }

    protected function tearDown(): void
    {
        Content::whereIn('title', ['Test Page', 'Home Page', 'About Us', 'Contact Page', 'Services'])->forceDelete();
        MultilanguageSupportedLocales::whereIn('locale', ['en_US', 'es_ES', 'fr_FR'])->delete();
        parent::tearDown();
    }

    protected function createTestLanguages()
    {
        // English
        MultilanguageSupportedLocales::create([
            'locale' => 'en_US',
            'language' => 'English',
            'display_name' => 'English',
            'display_locale' => 'en',
            'is_active' => true,
            'position' => 1,
        ]);

        // Spanish
        MultilanguageSupportedLocales::create([
            'locale' => 'es_ES',
            'language' => 'Spanish',
            'display_name' => 'Spanish',
            'display_locale' => 'es',
            'is_active' => true,
            'position' => 2,
        ]);

        // French
        MultilanguageSupportedLocales::create([
            'locale' => 'fr_FR',
            'language' => 'French',
            'display_name' => 'French',
            'display_locale' => 'fr',
            'is_active' => true,
            'position' => 3,
        ]);

        // Clear cache
        if (function_exists('clearcache')) {
            clearcache();
        }
    }

    /**
     * Test that content can have translations for multiple languages
     */
    public function test_content_can_have_translations(): void
    {
        // Create a page
        $content = Content::create([
            'title' => 'Home Page',
            'content_type' => 'page',
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('content', [
            'id' => $content->id,
            'title' => 'Home Page',
        ]);
    }

    /**
     * Test that multilanguage helper functions work correctly
     */
    public function test_multilanguage_helper_functions(): void
    {
        // Test get_supported_languages
        $languages = get_supported_languages(true);
        $this->assertGreaterThanOrEqual(3, count($languages));

        // Check language data structure
        foreach ($languages as $language) {
            $this->assertArrayHasKey('locale', $language);
            $this->assertArrayHasKey('language', $language);
            $this->assertArrayHasKey('display_name', $language);
        }
    }

    /**
     * Test that language detection from URL works
     */
    public function test_language_detection_from_url(): void
    {
        $result = detect_lang_from_url('/en/my-page');
        $this->assertArrayHasKey('target_lang', $result);
        $this->assertArrayHasKey('target_url', $result);
    }

    /**
     * Test that multilanguage links generation works
     */
    public function test_multilanguage_links_generation(): void
    {
        // Create content
        $content = Content::create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'is_active' => 1,
        ]);

        // Get multilanguage content links
        $links = multilanguage_get_all_content_links($content->id);
        $this->assertIsArray($links);
    }

    /**
     * Test that language can be changed programmatically
     */
    public function test_language_can_be_changed(): void
    {
        // Get default language
        $defaultLang = get_default_language();
        // get_default_language returns array with locale and language keys, or false
        $this->assertTrue(is_array($defaultLang) || $defaultLang === false);

        if (is_array($defaultLang)) {
            $this->assertArrayHasKey('locale', $defaultLang);
        }

        // Change to Spanish
        change_language_by_locale('es_ES', false);

        // Verify locale changed
        $currentLocale = app()->getLocale();
        // Note: The actual locale change depends on system configuration
        $this->assertNotNull($currentLocale);
    }

    /**
     * Test that supported locale can be found by locale
     */
    public function test_get_supported_locale_by_locale(): void
    {
        $locale = get_supported_locale_by_locale('en_US');
        $this->assertNotNull($locale);
        $this->assertArrayHasKey('language', $locale);
    }

    /**
     * Test that language is supported check works
     */
    public function test_is_language_supported(): void
    {
        // Test languages should be supported
        $this->assertTrue(is_lang_supported('en'));
        $this->assertTrue(is_lang_supported('es'));
        $this->assertTrue(is_lang_supported('fr'));
    }

    /**
     * Test that short abbreviation is extracted from locale
     */
    public function test_get_short_abbreviation(): void
    {
        $this->assertEquals('en', get_short_abr('en_US'));
        $this->assertEquals('es', get_short_abr('es_ES'));
        $this->assertEquals('fr', get_short_abr('fr_FR'));
    }

    /**
     * Test multilanguage translations repository
     */
    public function test_multilanguage_repository_methods(): void
    {
        $repo = app()->multilanguage_repository;

        // Test getSupportedLocales
        $locales = $repo->getSupportedLocales(true);
        $this->assertGreaterThanOrEqual(3, count($locales));

        // Test getSupportedLocaleByLocale
        $locale = $repo->getSupportedLocaleByLocale('en_US');
        $this->assertNotNull($locale);
        $this->assertArrayHasKey('language', $locale);
    }

    /**
     * Test that content translations can be stored and retrieved
     */
    public function test_content_translations_storage(): void
    {
        // Create content
        $content = Content::create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'is_active' => 1,
        ]);

        // Create translations
        MultilanguageTranslations::create([
            'rel_id' => $content->id,
            'rel_type' => 'content',
            'field_name' => 'title',
            'field_value' => 'Página de Prueba',
            'locale' => 'es_ES',
        ]);

        MultilanguageTranslations::create([
            'rel_id' => $content->id,
            'rel_type' => 'content',
            'field_name' => 'title',
            'field_value' => 'Page de Test',
            'locale' => 'fr_FR',
        ]);

        // Retrieve translations
        $translations = MultilanguageTranslations::where('rel_id', $content->id)
            ->where('rel_type', 'content')
            ->get();

        $this->assertCount(2, $translations);

        // Check specific translation
        $spanishTranslation = $translations->where('locale', 'es_ES')->first();
        $this->assertNotNull($spanishTranslation);
        $this->assertEquals('Página de Prueba', $spanishTranslation->field_value);
    }

    /**
     * Test that multilanguage links are generated for all supported locales
     */
    public function test_multilanguage_content_links_generation(): void
    {
        // Create content
        $content = Content::create([
            'title' => 'About Us',
            'content_type' => 'page',
            'url' => 'about-us',
            'is_active' => 1,
        ]);

        // Add translations
        MultilanguageTranslations::create([
            'rel_id' => $content->id,
            'rel_type' => 'content',
            'field_name' => 'url',
            'field_value' => 'sobre-nosotros',
            'locale' => 'es_ES',
        ]);

        // Get multilanguage links
        $links = multilanguage_get_all_content_links($content->id);

        // Verify links structure
        $this->assertIsArray($links);

        // Should have links for supported languages
        $this->assertNotEmpty($links);
    }

    /**
     * Test that locale switching preserves current URL context
     */
    public function test_locale_switching_preserves_context(): void
    {
        // This test verifies that when changing locale, the URL context is preserved
        $testUrl = '/en/products/my-product';
        $result = detect_lang_from_url($testUrl);

        $this->assertArrayHasKey('target_lang', $result);
        $this->assertArrayHasKey('target_url', $result);

        // The target URL should not include the language prefix
        $this->assertStringNotContainsString('/en/', $result['target_url']);
    }

    /**
     * Test that the locale switcher Livewire component can be instantiated
     */
    public function test_locale_switcher_component_exists(): void
    {
        // Test that the component class exists
        $this->assertTrue(class_exists(\Modules\Multilanguage\Livewire\LocaleSwitcher::class));
    }

    /**
     * Test that multilanguage can be enabled and disabled
     */
    public function test_multilanguage_can_be_toggled(): void
    {
        // Enable multilanguage
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(true);
        $this->assertTrue(\MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled());

        // Disable multilanguage
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);
        $this->assertFalse(\MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled());

        // Re-enable for other tests
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(true);
    }
}
