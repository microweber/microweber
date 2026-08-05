<?php

declare(strict_types=1);

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use MicroweberPackages\PackageManagerClient\PackageManagerClient;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Dusk coverage: marketplace admin uses the new package manager client.
 *
 * Prerequisites: running app server + admin credentials (AdminLoginTrait) + Chrome.
 */
class PackageManagerClientMarketplaceTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Use running server DB
    }

    #[Test]
    public function marketplace_page_uses_package_manager_client(): void
    {
        // Always assert the client is available (works without a browser).
        $this->assertTrue(
            class_exists(PackageManagerClient::class),
            'PackageManagerClient must be available'
        );

        if (!$this->chromeAvailable()) {
            $this->markTestSkipped('Chrome binary not available — browser portion skipped');
        }

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/marketplace')->pause(4000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString(
                'Internal Server Error',
                $pageSource,
                'Marketplace must not 500 with new package manager client'
            );
            $this->assertStringNotContainsString(
                'Class "MicroweberPackages\\Package\\MicroweberComposerClient" not found',
                $pageSource
            );
            $this->assertStringNotContainsString(
                'Class "MicroweberPackages\\ComposerClient\\Client" not found',
                $pageSource
            );

            $bodyText = $browser->script('return document.body.innerText.toLowerCase();');
            $text = $bodyText[0] ?? '';
            $hasContent = str_contains($text, 'marketplace')
                || str_contains($text, 'module')
                || str_contains($text, 'template')
                || str_contains($text, 'package')
                || str_contains($text, 'install');

            $this->assertTrue(
                $hasContent || str_contains($pageSource, 'fi-'),
                'Marketplace page should render marketplace-related content'
            );
        });
    }

    private function chromeAvailable(): bool
    {
        $candidates = [
            '/usr/bin/google-chrome',
            '/usr/bin/google-chrome-stable',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser',
            '/usr/bin/chrome',
        ];
        foreach ($candidates as $bin) {
            if (is_executable($bin)) {
                return true;
            }
        }
        $which = @shell_exec('command -v google-chrome chromium chromium-browser 2>/dev/null');

        return is_string($which) && trim($which) !== '';
    }
}
