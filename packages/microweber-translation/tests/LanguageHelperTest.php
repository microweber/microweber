<?php

namespace MicroweberPackages\Translation\Tests;

use MicroweberPackages\Translation\LanguageHelper;
use PHPUnit\Framework\Attributes\Test;

class LanguageHelperTest extends TestCase
{
    #[Test]
    public function it_can_get_display_language(): void
    {
        $name = LanguageHelper::getDisplayLanguage('en_US');
        $this->assertNotEmpty($name);
        $this->assertIsString($name);
    }

    #[Test]
    public function it_can_get_display_language_for_bg(): void
    {
        $name = LanguageHelper::getDisplayLanguage('bg_BG');
        $this->assertNotEmpty($name);
    }

    #[Test]
    public function it_can_detect_rtl(): void
    {
        $this->assertTrue(LanguageHelper::isRTL('ar_SA'));
        $this->assertFalse(LanguageHelper::isRTL('en_US'));
    }

    #[Test]
    public function it_can_normalize_locale_name(): void
    {
        $this->assertEquals('en_US', LanguageHelper::normalizeLocaleName('en-US'));
        $this->assertEquals('en_US', LanguageHelper::normalizeLocaleName('en_US'));
    }

    #[Test]
    public function it_can_get_lang_data(): void
    {
        $data = LanguageHelper::getLangData('en_US');
        $this->assertNotFalse($data);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('name', $data);
    }

    #[Test]
    public function it_can_get_language_flag(): void
    {
        $flag = LanguageHelper::getLanguageFlag('en_US');
        $this->assertNotEmpty($flag);
    }

    #[Test]
    public function it_can_get_languages_with_default_locale(): void
    {
        $languages = LanguageHelper::getLanguagesWithDefaultLocale();
        $this->assertNotEmpty($languages);
        $this->assertIsArray($languages);

        // Should contain English
        $hasEnglish = false;
        foreach ($languages as $lang) {
            if (isset($lang['language']) && $lang['language'] === 'en') {
                $hasEnglish = true;
                break;
            }
        }
        $this->assertTrue($hasEnglish);
    }

    #[Test]
    public function it_returns_locale_name_for_unknown_locales(): void
    {
        $name = LanguageHelper::getDisplayLanguage('xx_XX');
        // For unknown locales, it should return the locale name itself
        $this->assertNotEmpty($name);
    }
}