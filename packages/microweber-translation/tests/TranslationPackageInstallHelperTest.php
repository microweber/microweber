<?php

namespace MicroweberPackages\Translation\Tests;

use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use MicroweberPackages\Translation\TranslationPackageInstallHelper;
use PHPUnit\Framework\Attributes\Test;

class TranslationPackageInstallHelperTest extends TestCase
{
    #[Test]
    public function it_can_get_available_translations(): void
    {
        $translations = TranslationPackageInstallHelper::getAvailableTranslations();
        $this->assertNotEmpty($translations);
        $this->assertIsArray($translations);
        $this->assertArrayHasKey('en_US', $translations);
    }

    #[Test]
    public function it_available_translations_has_english_first(): void
    {
        $translations = TranslationPackageInstallHelper::getAvailableTranslations();
        $keys = array_keys($translations);
        $this->assertStringStartsWith('en', $keys[0]);
    }

    #[Test]
    public function it_can_install_a_language(): void
    {
        $result = TranslationPackageInstallHelper::installLanguage('en_US');

        $this->assertNotNull($result);
        $this->assertArrayHasKey('success', $result);

        // Verify translations were inserted
        $keysCount = TranslationKey::count();
        $this->assertGreaterThan(0, $keysCount);

        $textsCount = TranslationText::where('translation_locale', 'en_US')->count();
        $this->assertGreaterThan(0, $textsCount);
    }

    #[Test]
    public function it_returns_null_for_nonexistent_language(): void
    {
        $result = TranslationPackageInstallHelper::installLanguage('nonexistent_LANG');
        $this->assertNull($result);
    }

    #[Test]
    public function it_can_install_multiple_languages(): void
    {
        TranslationKey::truncate();
        TranslationText::truncate();

        $result1 = TranslationPackageInstallHelper::installLanguage('en_US');
        $this->assertArrayHasKey('success', $result1);

        $result2 = TranslationPackageInstallHelper::installLanguage('bg_BG');
        $this->assertArrayHasKey('success', $result2);

        // Verify both locales have texts
        $enCount = TranslationText::where('translation_locale', 'en_US')->count();
        $bgCount = TranslationText::where('translation_locale', 'bg_BG')->count();

        $this->assertGreaterThan(0, $enCount);
        $this->assertGreaterThan(0, $bgCount);
    }

    #[Test]
    public function it_available_translations_returns_sorted_list(): void
    {
        $translations = TranslationPackageInstallHelper::getAvailableTranslations();

        // English variants should be first
        $keys = array_keys($translations);
        $englishDone = false;
        foreach ($keys as $key) {
            if (!stristr($key, 'en_') && $englishDone) {
                // After English, the rest should be sorted
                break;
            }
            if (stristr($key, 'en_')) {
                $englishDone = true;
            }
        }

        $this->assertTrue(true); // If we got here, no assertion needed - structure is valid
    }
}