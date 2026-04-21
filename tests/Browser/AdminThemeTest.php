<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Theme & UX Tests
 *
 * Validates the Microweber v3 Filament admin theme:
 *   - Design tokens (CSS variables) applied
 *   - wire:loading spinners hidden by default
 *   - Component styling (sidebar, body, topbar, sections)
 *   - Responsive design (mobile/tablet padding reduction)
 *   - Dark mode overrides
 *   - Interaction states (active sidebar, input focus)
 *   - Key pages render without errors
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminThemeTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function admin_theme_ux_audit(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $passed = 0;
            $failed = [];

            // ════════════════════════════════════════════════════════════
            // 1. Theme Stylesheet & Design Tokens
            // ════════════════════════════════════════════════════════════
            $browser->visit('/admin')->pause(1200);

            try {
                $hasTheme = $browser->script("
                    var links = document.querySelectorAll('link[rel=\"stylesheet\"]');
                    var found = false;
                    links.forEach(function(l) {
                        if (l.href && l.href.indexOf('microweber-filament-theme') > -1) found = true;
                    });
                    return found;
                ");
                $this->assertTrue($hasTheme[0], 'Theme stylesheet should be loaded');
                $passed++;
            } catch (\Exception $e) {
                $failed['theme_stylesheet_loaded'] = $e->getMessage();
            }

            try {
                $vars = $browser->script("
                    var root = getComputedStyle(document.documentElement);
                    return {
                        bgPage: root.getPropertyValue('--mw-bg-page').trim(),
                        bgSurface: root.getPropertyValue('--mw-bg-surface').trim(),
                        textPrimary: root.getPropertyValue('--mw-text-primary').trim(),
                        radiusLg: root.getPropertyValue('--mw-radius-lg').trim()
                    };
                ");
                $r = $vars[0];
                $this->assertEquals('#f6f8fb', $r['bgPage'], '--mw-bg-page = #f6f8fb');
                $this->assertEquals('#ffffff', $r['bgSurface'], '--mw-bg-surface = #ffffff');
                $this->assertEquals('#182433', $r['textPrimary'], '--mw-text-primary = #182433');
                $this->assertEquals('4px', $r['radiusLg'], '--mw-radius-lg = 4px');
                $passed++;
            } catch (\Exception $e) {
                $failed['css_variables_applied'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 2. Wire:Loading Spinners
            // ════════════════════════════════════════════════════════════
            try {
                $spinnerState = $browser->script("
                    var loadingEls = document.querySelectorAll('[wire\\\\:loading]');
                    var allHidden = true;
                    loadingEls.forEach(function(el) {
                        if (getComputedStyle(el).display !== 'none') allHidden = false;
                    });
                    return { count: loadingEls.length, allHidden: allHidden };
                ");
                $this->assertTrue($spinnerState[0]['allHidden'],
                    'wire:loading elements should be hidden by default');
                $passed++;
            } catch (\Exception $e) {
                $failed['wire_loading_hidden'] = $e->getMessage();
            }

            $browser->visit('/admin/pages')->pause(1200);

            try {
                $removeState = $browser->script("
                    var removeEls = document.querySelectorAll('[wire\\\\:loading\\\\.remove]');
                    var allVisible = true;
                    removeEls.forEach(function(el) {
                        if (getComputedStyle(el).display === 'none') allVisible = false;
                    });
                    return { count: removeEls.length, allVisible: allVisible };
                ");
                $this->assertTrue($removeState[0]['allVisible'],
                    'wire:loading.remove elements should be visible by default');
                $passed++;
            } catch (\Exception $e) {
                $failed['wire_loading_remove_visible'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 3. Component Styling
            // ════════════════════════════════════════════════════════════
            $browser->visit('/admin')->pause(1200);

            try {
                $sidebarBg = $browser->script("
                    var sidebar = document.querySelector('.fi-sidebar');
                    return sidebar ? getComputedStyle(sidebar).backgroundColor : null;
                ");
                $this->assertNotNull($sidebarBg[0], 'Sidebar should exist');
                $this->assertMatchesRegularExpression('/rgb\(255,\s*255,\s*255\)/', $sidebarBg[0],
                    'Sidebar background should be white');
                $passed++;
            } catch (\Exception $e) {
                $failed['sidebar_white_bg'] = $e->getMessage();
            }

            try {
                $bodyBg = $browser->script("
                    var body = document.querySelector('.fi-body');
                    return body ? getComputedStyle(body).backgroundColor : null;
                ");
                $this->assertNotNull($bodyBg[0], '.fi-body should exist');
                $this->assertMatchesRegularExpression('/rgb\(246,\s*248,\s*251\)/', $bodyBg[0],
                    'Body background should be MW v2 blue-gray #f6f8fb');
                $passed++;
            } catch (\Exception $e) {
                $failed['body_warm_gray_bg'] = $e->getMessage();
            }

            try {
                $border = $browser->script("
                    var topbar = document.querySelector('.fi-topbar > nav') || document.querySelector('.fi-topbar');
                    if (!topbar) return null;
                    var s = getComputedStyle(topbar);
                    return { width: s.borderBottomWidth, style: s.borderBottomStyle };
                ");
                $this->assertNotNull($border[0], 'Topbar should exist');
                // Topbar should have a visible border-bottom (1px solid)
                $this->assertNotEquals('0px', $border[0]['width'], 'Topbar should have a border-bottom');
                $passed++;
            } catch (\Exception $e) {
                $failed['topbar_border'] = $e->getMessage();
            }

            // Section containers on desktop
            $browser->visit('/admin/orders')->pause(1200);

            try {
                $radius = $browser->script("
                    var ctn = document.querySelector('.fi-section-content-ctn');
                    return ctn ? getComputedStyle(ctn).borderRadius : null;
                ");
                $this->assertNotNull($radius[0], '.fi-section-content-ctn should exist');
                $this->assertStringContainsString('4px', $radius[0],
                    'Section containers should use 4px radius (joined with header — asymmetric 0 0 4px 4px allowed)');
                $passed++;
            } catch (\Exception $e) {
                $failed['section_border_radius_desktop'] = $e->getMessage();
            }

            // Active sidebar item styling
            $browser->visit('/admin/pages')->pause(1200);

            try {
                $activeStyle = $browser->script("
                    var activeItem = document.querySelector('.fi-sidebar-item.fi-active');
                    var inactiveItem = document.querySelector('.fi-sidebar-item:not(.fi-active)');
                    if (!activeItem || !inactiveItem) return {found: false};
                    var activeBtn = activeItem.querySelector('.fi-sidebar-item-btn') || activeItem;
                    var inactiveBtn = inactiveItem.querySelector('.fi-sidebar-item-btn') || inactiveItem;
                    var activeLabel = activeItem.querySelector('.fi-sidebar-item-label') || activeBtn;
                    var inactiveLabel = inactiveItem.querySelector('.fi-sidebar-item-label') || inactiveBtn;
                    var abCs = getComputedStyle(activeBtn);
                    var ibCs = getComputedStyle(inactiveBtn);
                    var alCs = getComputedStyle(activeLabel);
                    var ilCs = getComputedStyle(inactiveLabel);
                    return {
                        found: true,
                        activeBg: abCs.backgroundColor,
                        inactiveBg: ibCs.backgroundColor,
                        activeColor: alCs.color,
                        inactiveColor: ilCs.color,
                        activeDecoration: alCs.textDecorationLine,
                        inactiveDecoration: ilCs.textDecorationLine,
                        activeWeight: alCs.fontWeight,
                        inactiveWeight: ilCs.fontWeight
                    };
                ");
                $s = $activeStyle[0] ?? ['found' => false];
                if ($s['found']) {
                    $isDistinct = $s['activeBg'] !== $s['inactiveBg']
                        || $s['activeColor'] !== $s['inactiveColor']
                        || $s['activeDecoration'] !== $s['inactiveDecoration']
                        || $s['activeWeight'] !== $s['inactiveWeight'];
                    $this->assertTrue($isDistinct,
                        'Active sidebar item should differ from inactive (bg, color, decoration, or weight). Got: ' . json_encode($s)
                    );
                    $passed++;
                }
            } catch (\Exception $e) {
                $failed['sidebar_active_styling'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 4. Responsive Design
            // ════════════════════════════════════════════════════════════

            // Mobile (375px)
            $browser->resize(375, 812);
            $browser->visit('/admin/orders')->pause(1200);

            try {
                $mobilePadding = $browser->script("
                    var h = document.querySelector('.fi-page-header-main-ctn');
                    if (!h) return null;
                    var s = getComputedStyle(h);
                    return { top: parseInt(s.paddingTop), bottom: parseInt(s.paddingBottom) };
                ");
                $this->assertNotNull($mobilePadding[0], '.fi-page-header-main-ctn should exist on mobile');
                $this->assertLessThanOrEqual(12, $mobilePadding[0]['top'],
                    'Mobile padding-top should be ≤12px');
                $this->assertLessThanOrEqual(12, $mobilePadding[0]['bottom'],
                    'Mobile padding-bottom should be ≤12px');
                $passed++;
            } catch (\Exception $e) {
                $failed['mobile_padding_reduced'] = $e->getMessage();
            }

            try {
                $mobileRadius = $browser->script("
                    var ctn = document.querySelector('.fi-section-content-ctn');
                    return ctn ? parseInt(getComputedStyle(ctn).borderRadius) : null;
                ");
                if ($mobileRadius[0] !== null) {
                    $this->assertLessThanOrEqual(8, $mobileRadius[0],
                        'Mobile border-radius should be ≤8px');
                    $passed++;
                }
            } catch (\Exception $e) {
                $failed['mobile_border_radius_reduced'] = $e->getMessage();
            }

            // Tablet (768px)
            $browser->resize(768, 1024);
            $browser->visit('/admin')->pause(1200);

            try {
                $tabletPadding = $browser->script("
                    var h = document.querySelector('.fi-page-header-main-ctn');
                    return h ? parseInt(getComputedStyle(h).paddingTop) : null;
                ");
                $this->assertNotNull($tabletPadding[0], '.fi-page-header-main-ctn should exist on tablet');
                $this->assertLessThanOrEqual(12, $tabletPadding[0],
                    'Tablet padding-top should be ≤12px');
                $passed++;
            } catch (\Exception $e) {
                $failed['tablet_padding_reduced'] = $e->getMessage();
            }

            // Desktop (1440px) — padding should be unchanged
            $browser->resize(1440, 900);
            $browser->visit('/admin/orders')->pause(1200);

            try {
                $desktopPadding = $browser->script("
                    var h = document.querySelector('.fi-page-header-main-ctn');
                    return h ? parseInt(getComputedStyle(h).paddingTop) : null;
                ");
                $this->assertNotNull($desktopPadding[0], '.fi-page-header-main-ctn should exist on desktop');
                $this->assertGreaterThanOrEqual(8, $desktopPadding[0],
                    'Desktop padding-top should be at least 8px (MW v3 uses compact spacing)');
                $passed++;
            } catch (\Exception $e) {
                $failed['desktop_padding_unchanged'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 5. Dark Mode
            // ════════════════════════════════════════════════════════════
            $browser->visit('/admin')->pause(1200);

            try {
                $browser->script("document.documentElement.classList.add('dark');");
                $browser->pause(500);

                $darkStyles = $browser->script("
                    var body = document.querySelector('.fi-body');
                    var sidebar = document.querySelector('.fi-sidebar');
                    if (!body || !sidebar) return null;
                    return {
                        bodyBg: getComputedStyle(body).backgroundColor,
                        sidebarBg: getComputedStyle(sidebar).backgroundColor
                    };
                ");
                $this->assertNotNull($darkStyles[0], 'Body and sidebar should exist in dark mode');
                $this->assertDoesNotMatchRegularExpression('/rgb\(255,\s*255,\s*255\)/',
                    $darkStyles[0]['bodyBg'], 'Dark mode body should not be white');
                $this->assertDoesNotMatchRegularExpression('/rgb\(255,\s*255,\s*255\)/',
                    $darkStyles[0]['sidebarBg'], 'Dark mode sidebar should not be white');

                $browser->script("document.documentElement.classList.remove('dark');");
                $passed++;
            } catch (\Exception $e) {
                $failed['dark_mode_overrides'] = $e->getMessage();
                $browser->script("document.documentElement.classList.remove('dark');");
            }

            // ════════════════════════════════════════════════════════════
            // 6. Empty State & Input Focus
            // ════════════════════════════════════════════════════════════
            $browser->visit('/admin/tags')->pause(1200);

            try {
                $emptyState = $browser->script("
                    var heading = document.querySelector('.fi-empty-state-heading');
                    if (!heading) return null;
                    return {
                        fontWeight: getComputedStyle(heading).fontWeight,
                        text: heading.textContent.trim()
                    };
                ");
                if ($emptyState[0] !== null) {
                    $this->assertNotEmpty($emptyState[0]['text'], 'Empty state should have heading text');
                    $this->assertGreaterThanOrEqual(600, intval($emptyState[0]['fontWeight']),
                        'Empty state heading should be bold (≥600)');
                    $passed++;
                }
            } catch (\Exception $e) {
                $failed['empty_state_styling'] = $e->getMessage();
            }

            $browser->visit('/admin/tags/create')->pause(1200);

            try {
                $browser->script("
                    var input = document.querySelector('input[id*=\"name\"]');
                    if (input) input.focus();
                ");
                $browser->pause(500);

                $focusStyle = $browser->script("
                    var focused = document.activeElement;
                    if (!focused) return null;
                    var wrapper = focused.closest('.fi-input-wrp');
                    if (!wrapper) return null;
                    return { boxShadow: getComputedStyle(wrapper).boxShadow };
                ");
                if ($focusStyle[0] !== null) {
                    $this->assertNotEquals('none', $focusStyle[0]['boxShadow'],
                        'Focused input should have a box-shadow focus ring');
                    $passed++;
                }
            } catch (\Exception $e) {
                $failed['input_focus_styling'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // 7. Key Pages Render Without Errors
            // ════════════════════════════════════════════════════════════
            $pages = [
                '/admin' => 'Dashboard',
                '/admin/pages' => 'Pages',
                '/admin/orders' => 'Orders',
                '/admin/users' => 'Users',
                '/admin/settings' => 'Settings',
                '/admin/comments' => 'Comments',
                '/admin/categories' => 'Categories',
            ];

            $pageErrors = [];
            foreach ($pages as $url => $name) {
                $browser->visit($url)->pause(1200);

                $currentUrl = $browser->driver->getCurrentURL();
                if (str_contains($currentUrl, '/login')) {
                    $this->loginAsAdmin($browser);
                    $browser->visit($url)->pause(1200);
                }

                $pageSource = $browser->driver->getPageSource();
                if (str_contains($pageSource, 'Internal Server Error')) {
                    $pageErrors[] = "{$name}: 500 error";
                }
                if (str_contains($pageSource, 'Database Server')) {
                    $pageErrors[] = "{$name}: showing installer";
                }
            }

            try {
                $this->assertEmpty($pageErrors,
                    "Pages with errors:\n" . implode("\n", $pageErrors));
                $passed++;
            } catch (\Exception $e) {
                $failed['key_pages_render'] = $e->getMessage();
            }

            // ════════════════════════════════════════════════════════════
            // Report
            // ════════════════════════════════════════════════════════════
            $total = $passed + count($failed);

            if (!empty($failed)) {
                $report = "Theme UX audit: {$passed}/{$total} checks passed.\nFailed:\n";
                foreach ($failed as $check => $error) {
                    $report .= "  - {$check}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->addToAssertionCount($passed);
        });
    }
}
