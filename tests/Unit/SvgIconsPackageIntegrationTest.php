<?php

declare(strict_types=1);

namespace Tests\Unit;

use BladeUI\Icons\Factory;
use Illuminate\Support\Facades\Blade;
use MicroweberPackages\SvgIcons\SvgIconsServiceProvider;
use Tests\TestCase;

/**
 * Integration tests confirming the microweber-svg-icons package is
 * correctly wired into the CMS and all icons render.
 */
class SvgIconsPackageIntegrationTest extends TestCase
{
    /** @test */
    public function mw_icon_set_is_registered(): void
    {
        $factory = app(Factory::class);
        $sets = $factory->all();

        $this->assertArrayHasKey('mw', $sets, 'The "mw" Blade-Icons set must be registered.');
    }

    /** @test */
    public function available_icons_list_is_not_empty(): void
    {
        $icons = SvgIconsServiceProvider::availableIcons();
        $this->assertNotEmpty($icons);
    }

    /**
     * @test
     * @dataProvider coreIconProvider
     */
    public function core_icon_renders_via_blade(string $icon): void
    {
        $html = Blade::render("@svg('mw-{$icon}', 'h-6 w-6')");

        $this->assertStringContainsString('<svg', $html, "mw-{$icon} did not render.");
    }

    /**
     * @test
     * @dataProvider emptyStateIconProvider
     */
    public function empty_state_icon_renders(string $icon): void
    {
        $html = Blade::render("@svg('mw-{$icon}', 'w-48 h-48')");

        $this->assertStringContainsString('<svg', $html, "Empty-state icon mw-{$icon} did not render.");
    }

    /** @test */
    public function all_icon_names_use_dashes_not_underscores(): void
    {
        foreach (SvgIconsServiceProvider::availableIcons() as $icon) {
            $this->assertStringNotContainsString('_', $icon, "Icon '{$icon}' uses underscores.");
        }
    }

    /**
     * Core admin icons used by CustomFieldTypes, settings pages, etc.
     *
     * @return iterable<string, array{string}>
     */
    public static function coreIconProvider(): iterable
    {
        $icons = [
            'text', 'numbers', 'checkbox', 'dropdown', 'email', 'hidden',
            'radio-checked', 'info', 'add-plus', 'general', 'image-edit',
            'media-item-edit-small', 'media-item-delete-small', 'dashboard',
            'settings', 'users', 'shop', 'payments', 'shipping', 'taxes',
        ];

        foreach ($icons as $icon) {
            yield $icon => [$icon];
        }
    }

    /**
     * Empty-state illustration icons from the Big template.
     *
     * @return iterable<string, array{string}>
     */
    public static function emptyStateIconProvider(): iterable
    {
        $icons = [
            'no-content', 'no-pages', 'no-products', 'no-orders',
            'no-categories', 'no-clients', 'no-invoices', 'no-notifications',
        ];

        foreach ($icons as $icon) {
            yield $icon => [$icon];
        }
    }
}
