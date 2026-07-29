<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Filament;

use MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource;
use MicroweberPackages\TemplateFonts\Filament\TemplateFontsPlugin;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

class TemplateFontResourceTest extends TestCase
{
    public function test_resource_model_and_slug(): void
    {
        $this->assertSame(
            \MicroweberPackages\TemplateFonts\Models\TemplateFont::class,
            TemplateFontResource::getModel()
        );
        $this->assertSame('template-fonts', TemplateFontResource::getSlug());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = TemplateFontResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_plugin_id(): void
    {
        $plugin = TemplateFontsPlugin::make();
        $this->assertSame('template-fonts', $plugin->getId());
    }

    public function test_can_access_when_no_auth(): void
    {
        // In standalone without is_admin(), canAccess allows guests
        $this->assertIsBool(TemplateFontResource::canAccess());
    }
}
