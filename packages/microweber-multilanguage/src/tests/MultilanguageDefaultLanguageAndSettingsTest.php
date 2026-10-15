<?php

namespace MicroweberPackages\Multilanguage\tests;

use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

/**
 * Regression tests for two multilanguage bugs fixed on 2026-10-15:
 *  - get_default_language() lowercased the locale before the canonical
 *    code-table lookup, so it always returned false.
 *  - The admin "Multilanguage is active" toggle used the statePath
 *    options.multilanguage.is_active, which wrote the wrong option key.
 */
class MultilanguageDefaultLanguageAndSettingsTest extends MultilanguageTestBase
{
    #[Test]
    public function get_default_language_resolves_canonical_locale_case_insensitively(): void
    {
        MultilanguageHelpers::setMultilanguageEnabled(1);

        $locale = 'en_US';
        add_supported_language($locale, 'English (United States)');

        save_option([
            'option_key' => 'language',
            'option_value' => $locale,
            'option_group' => 'website',
        ]);
        app()->lang_helper->set_current_lang($locale);

        $default = get_default_language();

        // Pre-fix this returned false because the code table is keyed by the
        // canonical locale (en_US) but the lookup lowercased it to en_us.
        $this->assertIsArray($default);
        $this->assertSame('en_US', $default['locale']);
        $this->assertNotEmpty($default['language']);
    }

    #[Test]
    public function multilanguage_active_toggle_targets_the_is_active_option_key(): void
    {
        $file = base_path('Modules/Multilanguage/Filament/MultilanguageSettings.php');
        $this->assertFileExists($file);

        $source = (string) file_get_contents($file);

        // The LiveEditModuleSettings save path (array_undot_str) expects exactly
        // one segment after "options." — options.<key>. An extra level writes
        // an array as the option_key and reads a nonexistent key, so the admin
        // toggle silently fails to enable multilanguage.
        $this->assertStringContainsString("Toggle::make('options.is_active')", $source);
        $this->assertStringNotContainsString("options.multilanguage.is_active", $source);
    }
}
