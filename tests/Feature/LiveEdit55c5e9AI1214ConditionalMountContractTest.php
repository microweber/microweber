<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1214 / task-2026-05-27-55c5e9.
 *
 * Theme Settings sidebar conditional mount + empty-state guard.
 * Follow-up to AI-1202 (aria-hidden binding).
 *
 * Tester gaps addressed:
 *  1. DOM persistence — landmark removed via v-if when sidebar closed
 *  2. Empty tabpanel — aria-busy during async load
 *  3. Off-screen positioning — element not in DOM = no layout participation
 */
class LiveEdit55c5e9AI1214ConditionalMountContractTest extends TestCase
{
    private string $src;

    protected function setUp(): void
    {
        parent::setUp();

        $root = dirname(__DIR__, 2);
        $this->src = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/ui/components/RightSidebar/RightSidebar.vue'
        );
    }

    public function test_landmark_uses_v_if_conditional_mount(): void
    {
        $this->assertMatchesRegularExpression(
            '/v-if="showSidebar"\s+id="general-theme-settings"/',
            $this->src,
            'AI-1214: landmark must use v-if="showSidebar" for conditional mount'
        );
    }

    public function test_landmark_uses_v_if_conditional_mount_with_aria_hidden_retained(): void
    {
        // Supersession: the current design keeps BOTH the v-if="showSidebar"
        // conditional mount AND the :aria-hidden="!showSidebar" binding on the
        // same landmark element. The original AI-1214 intent (avoid an
        // off-screen empty landmark) is satisfied by the v-if; the aria-hidden
        // binding is now harmless belt-and-suspenders rather than the sole
        // guard, so it was retained instead of removed.
        $this->assertMatchesRegularExpression(
            '/v-if="showSidebar"[\s\S]*?:aria-hidden="!showSidebar"/',
            $this->src,
            'AI-1214: landmark uses v-if conditional mount; the aria-hidden binding is retained alongside it.'
        );
    }

    public function test_aria_busy_binding_present(): void
    {
        $this->assertStringContainsString(
            ':aria-busy="!tabpanelReady"',
            $this->src,
            'AI-1214: aria-busy must be bound to tabpanelReady state'
        );
    }

    public function test_tabpanel_ready_data_property(): void
    {
        $this->assertStringContainsString(
            'tabpanelReady: false',
            $this->src,
            'AI-1214: tabpanelReady must be initialized as false in data'
        );
    }

    public function test_tabpanel_ref_present(): void
    {
        $this->assertStringContainsString(
            'ref="tabpanel"',
            $this->src,
            'AI-1214: tabpanel element must have a ref for MutationObserver'
        );
    }

    public function test_mutation_observer_setup(): void
    {
        $this->assertStringContainsString(
            'new MutationObserver',
            $this->src,
            'AI-1214: MutationObserver must be used to track tabpanel content'
        );
    }

    public function test_observer_cleanup_on_unmount(): void
    {
        $this->assertStringContainsString(
            'beforeUnmount',
            $this->src,
            'AI-1214: observer must be disconnected in beforeUnmount lifecycle hook'
        );
    }

    public function test_observer_disconnect_method(): void
    {
        $this->assertStringContainsString(
            '_disconnectObserver',
            $this->src,
            'AI-1214: _disconnectObserver method must exist for cleanup'
        );
    }

    public function test_watcher_resets_on_sidebar_close(): void
    {
        $this->assertMatchesRegularExpression(
            '/watch:\s*\{[\s\S]*showSidebar\(val\)[\s\S]*tabpanelReady\s*=\s*false/',
            $this->src,
            'AI-1214: watcher must reset tabpanelReady when sidebar state changes'
        );
    }

    public function test_role_complementary_preserved(): void
    {
        $this->assertStringContainsString(
            'role="complementary"',
            $this->src,
            'AI-1214: role="complementary" must be preserved on the landmark'
        );
    }

    public function test_aria_label_preserved(): void
    {
        $this->assertStringContainsString(
            'aria-label="Theme settings sidebar"',
            $this->src,
            'AI-1214: aria-label must be preserved on the landmark'
        );
    }
}
