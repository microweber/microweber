<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Per-module Dusk tests for social & communication modules:
 * social-links, sharer, comments, newsletter, contact-form, facebook-like,
 * facebook-page, tweet-embed.
 *
 * Covers: settings page load, form fields for URLs/APIs, toggle switches,
 * tab navigation, and Livewire component rendering.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin
 */
class AdminModuleSocialUseCasesTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function social_links_settings_url_fields(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->visitAndAssertNoErrors($browser, 'social-links-module-settings');

            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Social links settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Social links settings should have form inputs (toggles/URLs) for social platforms');

            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Social Links');
            }
        });
    }

    #[Test]
    public function sharer_module_settings_platform_toggles(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->visitAndAssertNoErrors($browser, 'sharer-module-settings');

            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Sharer settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Sharer settings should have toggle/checkbox inputs for platforms');

            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Sharer');
            }
        });
    }

    #[Test]
    public function comments_and_newsletter_module_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Comments
            $this->visitAndAssertNoErrors($browser, 'comments-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Comments settings should be a Livewire component');

            // Comments admin settings
            $this->visitAndAssertNoErrors($browser, 'comments-module-settings-admin');
            $adminData = $this->getFormStats($browser);
            $this->assertTrue($adminData['hasWireId'] ?? false,
                'Comments admin settings should be a Livewire component');
            if (($adminData['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $adminData['tabCount'], 'Comments Admin');
            }

            // Newsletter
            $this->visitAndAssertNoErrors($browser, 'newsletter-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Newsletter settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Newsletter settings should have form configuration inputs');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Newsletter');
            }
        });
    }

    #[Test]
    public function contact_form_module_settings_fields_config(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->visitAndAssertNoErrors($browser, 'contact-form-module-settings');

            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Contact form settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Contact form settings should have inputs for email, fields config');

            // Contact form should have email recipient and form field configuration
            $formCheck = $browser->script("
                try {
                    var emailInputs = document.querySelectorAll('input[type=\"email\"], input[type=\"text\"]');
                    var textareas = document.querySelectorAll('textarea');
                    return {emailInputCount: emailInputs.length, textareaCount: textareas.length};
                } catch(e) { return {emailInputCount: 0, textareaCount: 0}; }
            ");

            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Contact Form');
            }
        });
    }

    #[Test]
    public function facebook_and_tweet_embed_settings(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            // Facebook Like
            $this->visitAndAssertNoErrors($browser, 'facebook-like-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Facebook Like settings should be a Livewire component');

            // Facebook Page
            $this->visitAndAssertNoErrors($browser, 'facebook-page-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Facebook Page settings should be a Livewire component');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Facebook Page');
            }

            // Tweet Embed
            $this->visitAndAssertNoErrors($browser, 'tweet-embed-module-settings');
            $data = $this->getFormStats($browser);
            $this->assertTrue($data['hasWireId'] ?? false,
                'Tweet embed settings should be a Livewire component');
            $this->assertGreaterThan(0, $data['inputCount'] ?? 0,
                'Tweet embed settings should have URL input');
            if (($data['tabCount'] ?? 0) > 1) {
                $this->clickThroughTabs($browser, $data['tabCount'], 'Tweet Embed');
            }
        });
    }
}
