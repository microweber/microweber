<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Content\Models\Content;
use MicroweberPackages\Template\ContentJson\ContentJsonInstaller;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

/**
 * Covers the fonts-install pass: a content.json "fonts" array (written by the
 * template exporter from the picked Live-Edit fonts) must be enabled on install,
 * so an activated template renders with its author's chosen fonts and not just the
 * design CSS's var() fallbacks. The real font manager would download woff2/CSS, so
 * here we bind a recording double and assert the installer drives it.
 */
class ContentJsonInstallerFontsTest extends TestCase
{
    private string $tpl = 'FontsInstallTpl';

    protected function tearDown(): void
    {
        Content::where('active_site_template', $this->tpl)->forceDelete();
        parent::tearDown();
    }

    /**
     * A recording stand-in for TemplateFontsManager (no network). It subclasses the
     * real manager so it satisfies the strict return type of TemplateFonts::manager()
     * (reached by clearcache() during install) while overriding only enableFont().
     */
    private function fakeManager(): TemplateFontsManager
    {
        return new class extends TemplateFontsManager {
            /** @var list<array{family:string,provider:string,category:?string}> */
            public array $enabled = [];

            public function enableFont(string $family, string $provider = 'google', ?string $category = null): bool
            {
                $this->enabled[] = ['family' => $family, 'provider' => $provider, 'category' => $category];
                return true;
            }
        };
    }

    #[Test]
    public function it_enables_each_font_from_content_json_on_install(): void
    {
        $fake = $this->fakeManager();
        $this->app->instance(TemplateFontsManager::class, $fake);

        (new ContentJsonInstaller())
            ->setTemplate($this->tpl)
            ->setData([
                'template' => $this->tpl,
                'pages' => [[
                    'url' => 'fonts-home-' . uniqid(),
                    'title' => 'Home',
                    'active_site_template' => $this->tpl,
                    'html' => '<section class="element"><h1>Hi</h1></section>',
                ]],
                'fonts' => [
                    ['family' => 'Poppins', 'provider' => 'google'],
                    ['family' => 'Inter'],            // provider defaults to google
                    'Roboto Slab',                     // bare-string family form
                ],
            ])
            ->install();

        $families = array_column($fake->enabled, 'family');
        $this->assertContains('Poppins', $families);
        $this->assertContains('Inter', $families);
        $this->assertContains('Roboto Slab', $families, 'A bare-string font entry must still be enabled.');

        $inter = $fake->enabled[array_search('Inter', $families, true)];
        $this->assertSame('google', $inter['provider'], 'provider must default to google when omitted.');
    }

    #[Test]
    public function install_succeeds_when_no_fonts_are_present(): void
    {
        $fake = $this->fakeManager();
        $this->app->instance(TemplateFontsManager::class, $fake);

        $result = (new ContentJsonInstaller())
            ->setTemplate($this->tpl)
            ->setData([
                'template' => $this->tpl,
                'pages' => [[
                    'url' => 'fonts-none-' . uniqid(),
                    'title' => 'Home',
                    'active_site_template' => $this->tpl,
                    'html' => '<section class="element"><h1>Hi</h1></section>',
                ]],
            ])
            ->install();

        $this->assertTrue($result['done']);
        $this->assertSame([], $fake->enabled, 'No fonts key means the manager is never called.');
    }
}
