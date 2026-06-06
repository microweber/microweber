<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI759 — one primary CTA per admin list screen.
 *
 * /admin/orders was already unified (AI-783): a single "+ Add order" primary
 * CreateAction with the shop settings grouped under a secondary gear
 * ActionGroup. This pins that state so it can't regress to the old
 * "Create Order" (header) + "Add order" (empty state) double CTA.
 *
 * /admin/comments was the open half: "New comment" (CreateAction) and
 * "Comments Settings" both rendered as filled primary pills. The settings
 * action is admin-meta config, not a primary user action, so it is demoted to
 * a secondary gray outlined button — leaving "New comment" as the sole primary.
 */
class AdminAI759SinglePrimaryCtaContractTest extends TestCase
{
    private function read(string $relative): string
    {
        $path = base_path($relative);
        $this->assertFileExists($path);

        return (string) file_get_contents($path);
    }

    #[Test]
    public function comments_settings_is_demoted_to_secondary(): void
    {
        $src = $this->read('Modules/Comments/Filament/Resources/CommentResource/Pages/ListComments.php');

        // The settings action is gray + outlined (secondary), not a primary pill.
        $this->assertMatchesRegularExpression(
            "/Action::make\('settings'\)[\s\S]*?->color\('gray'\)[\s\S]*?->outlined\(\)/",
            $src,
            'Comments Settings must be a secondary gray outlined button.'
        );
        // The CreateAction stays the single primary CTA.
        $this->assertMatchesRegularExpression(
            "/CreateAction::make\(\)[\s\S]{0,80}->color\('primary'\)/",
            $src,
            'New comment (CreateAction) must remain the single primary CTA.'
        );
        // Regression guard: settings must not be a primary/success filled pill.
        $this->assertDoesNotMatchRegularExpression(
            "/Action::make\('settings'\)[\s\S]*?->color\('(primary|success)'\)/",
            $src,
            'Comments Settings must not be a filled primary/success pill.'
        );
    }

    #[Test]
    public function orders_list_has_a_single_primary_create_cta(): void
    {
        $src = $this->read('Modules/Order/Filament/Admin/Resources/OrderResource/Pages/ListOrders.php');

        // Single unified primary create action.
        $this->assertMatchesRegularExpression(
            "/CreateAction::make\(\)[\s\S]*?->label\('\+ Add order'\)[\s\S]*?->color\('primary'\)/",
            $src,
            'Orders list must have one "+ Add order" primary CreateAction.'
        );
        // Settings are grouped under a secondary ActionGroup (gear), not a 2nd primary pill.
        $this->assertStringContainsString(
            'Actions\ActionGroup::make([',
            $src,
            'Orders shop-settings links must be grouped under a secondary ActionGroup.'
        );
    }
}
