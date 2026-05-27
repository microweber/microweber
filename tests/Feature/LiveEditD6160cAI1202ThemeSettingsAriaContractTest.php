<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1202 / task-2026-05-27-d6160c.
 *
 * The Theme Settings sidebar renders a role="complementary" ARIA landmark
 * with aria-label="Theme settings sidebar". When the sidebar is closed
 * (showSidebar is false), the landmark must carry aria-hidden so screen
 * readers skip the empty off-screen region. When the sidebar is open,
 * aria-hidden is removed (set to false) so AT users can navigate it.
 *
 * Fix: :aria-hidden="!showSidebar" binding on the landmark div.
 */
class LiveEditD6160cAI1202ThemeSettingsAriaContractTest extends TestCase
{
    private string $vueSource;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        $this->vueSource = (string) file_get_contents(
            self::projectRoot() . '/packages/frontend-assets/resources/assets/ui/components/RightSidebar/RightSidebar.vue'
        );
    }

    public function test_landmark_has_aria_hidden_binding(): void
    {
        $this->assertStringContainsString(
            ':aria-hidden="!showSidebar"',
            $this->vueSource
        );
    }

    public function test_landmark_has_complementary_role(): void
    {
        $this->assertStringContainsString(
            'role="complementary"',
            $this->vueSource
        );
    }

    public function test_landmark_has_aria_label(): void
    {
        $this->assertStringContainsString(
            'aria-label="Theme settings sidebar"',
            $this->vueSource
        );
    }

    public function test_aria_hidden_and_role_on_same_element(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div[^>]*role="complementary"[^>]*:aria-hidden="!showSidebar"/',
            $this->vueSource
        );
    }

    public function test_show_sidebar_defaults_to_false(): void
    {
        $this->assertMatchesRegularExpression(
            '/showSidebar:\s*false/',
            $this->vueSource
        );
    }

    public function test_sidebar_class_binding_uses_show_sidebar(): void
    {
        $this->assertMatchesRegularExpression(
            '/:class="\[showSidebar\s*==\s*true\s*\?\s*\'active\'/',
            $this->vueSource
        );
    }
}
