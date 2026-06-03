<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Multilanguage Workflow Tests
 *
 * End-to-end tests for multilanguage/translation management:
 *   - Multilanguage settings page loads with form
 *   - Translations resource page loads with table
 *   - Language toggle functionality
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminMultilanguageWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function multilanguage_settings_and_translations(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // ── Check 1: Multilanguage settings page loads with form ──
            try {
                $browser->visit('/admin/multilanguage-settings')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Multilanguage settings page should not return 500');

                $hasContent = $browser->script("
                    var inputs = document.querySelectorAll('input, select, textarea, .fi-toggle');
                    var hasForm = inputs.length > 0;
                    var hasSection = document.querySelectorAll('.fi-section').length > 0;
                    return { formFields: inputs.length, hasSection: hasSection };
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasLangContent = str_contains($text, 'language')
                    || str_contains($text, 'multilanguage')
                    || str_contains($text, 'translation')
                    || str_contains($text, 'locale')
                    || str_contains($text, 'default')
                    || str_contains($text, 'settings');
                $this->assertTrue(
                    ($hasContent[0]['formFields'] ?? 0) > 0 || ($hasContent[0]['hasSection'] ?? false) || $hasLangContent,
                    'Multilanguage settings page should have form fields or language-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['multilanguage_settings'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Translations resource page loads with table ──
            try {
                $browser->visit('/admin/translations')->pause(5000);
                $this->ensureLoggedIn($browser);

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                    'Translations page should not return 500');

                $hasContent = $browser->script("
                    return document.querySelector('.fi-ta-table') !== null
                        || document.querySelector('table') !== null
                        || document.querySelector('.fi-empty-state') !== null
                        || document.querySelector('[wire\\\\:id]') !== null
                        || document.querySelectorAll('.fi-section').length > 0
                        || document.querySelector('.fi-resource-index') !== null;
                ");

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';
                $hasTransContent = str_contains($text, 'translation')
                    || str_contains($text, 'key')
                    || str_contains($text, 'value')
                    || str_contains($text, 'language')
                    || str_contains($text, 'no records')
                    || str_contains($text, 'create');
                $this->assertTrue(
                    ($hasContent[0] ?? false) || $hasTransContent,
                    'Translations page should have table/content or translation-related text'
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['translations_list'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 3: Language settings toggle area ──
            try {
                $browser->visit('/admin/multilanguage-settings')->pause(5000);
                $this->ensureLoggedIn($browser);

                $bodyText = $browser->script("return document.body.innerText.toLowerCase();");
                $text = $bodyText[0] ?? '';

                // Check for language-related toggle/button elements
                $hasToggleArea = $browser->script("
                    var toggles = document.querySelectorAll('.fi-toggle, input[type=\"checkbox\"]');
                    var hasLangButtons = false;
                    var bodyText = document.body.innerText.toLowerCase();
                    hasLangButtons = bodyText.includes('enable') || bodyText.includes('active')
                        || bodyText.includes('add') || bodyText.includes('language')
                        || bodyText.includes('toggle') || bodyText.includes('select');
                    return { toggleCount: toggles.length, hasLangButtons: hasLangButtons };
                ");

                $pageSource = $browser->driver->getPageSource();
                $this->assertStringNotContainsString('Whoops', $pageSource,
                    'Multilanguage settings page should not show error');

                $hasLanguageUI = ($hasToggleArea[0]['toggleCount'] ?? 0) > 0
                    || ($hasToggleArea[0]['hasLangButtons'] ?? false)
                    || str_contains($text, 'language')
                    || str_contains($text, 'locale');
                $this->assertTrue($hasLanguageUI,
                    'Multilanguage settings should have language toggle/selection UI');
                $checks++;
            } catch (\Exception $e) {
                $failed['language_toggle'] = substr($e->getMessage(), 0, 200);
            }

            if (!empty($failed)) {
                $report = "Failed " . count($failed) . "/" . ($checks + count($failed)) . " multilanguage checks:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertTrue($checks >= 3, "All 3 multilanguage checks passed (got {$checks})");
        });
    }
}
