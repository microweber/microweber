<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-145 / AI-167 — novice signposting for the "Add content" picker.
 *
 * The UX-engineer audit found mobile users tap the "Add content" button
 * expecting to add a text block to the page they are already editing.
 * Then they see only Page/Post/Category/Product/Image options and bounce
 * — none of those is what they wanted. The fix is signposting, NOT
 * adding the in-canvas block-add flow inside the picker (that flow
 * runs in the canvas iframe via JS, has no server route, and embedding
 * it in the picker modal would conflict with the Vue toolbar / canvas
 * iframe roots).
 *
 * Cycle-145 ships a 6th picker entry "Add to this page" that opens a
 * Filament notification explaining the in-canvas workflow ("tap Insert
 * layout in the toolbar / drag from the left rail"). One tap, the
 * picker closes, a toast appears with the runbook, and the user is
 * left looking at the canvas with the right next-step in-mind.
 *
 * This test pins:
 *   1. The picker entry "Add to this page" exists in the actions
 *      array with the correct title/description/icon.
 *   2. addToCurrentPageAction() method exists and triggers a
 *      Notification with the workflow text.
 *   3. The cycle-145 anchor + AI-167 reference stay inline.
 */
class Ai167AddContentSignpostContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function picker_lists_an_add_to_this_page_signpost_entry(): void
    {
        $src = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        $this->assertMatchesRegularExpression(
            '/[\'"]title[\'"]\s*=>\s*[\'"]Add to this page[\'"]/',
            $src,
            'AdminLiveEditPage::addContentAction MUST include an '
            . '"Add to this page" entry so novice mobile users who '
            . 'tap the picker hoping to add a block to the page they '
            . 'are editing find a tappable affordance instead of '
            . 'bouncing.'
        );

        $this->assertMatchesRegularExpression(
            '/[\'"]action[\'"]\s*=>\s*[\'"]addToCurrentPageAction[\'"]/',
            $src,
            'The "Add to this page" picker entry MUST route to '
            . 'addToCurrentPageAction so the tap actually shows the '
            . 'workflow notification.'
        );
    }

    #[Test]
    public function picker_entry_uses_a_recognisable_pointer_icon(): void
    {
        $src = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        // The pointer/cursor icon visually distinguishes this entry
        // from the page/post/category/product icons (which are all
        // document-shaped) and the photo entry (which is image-shaped).
        // It hints at the "tap something on the canvas" workflow.
        $this->assertMatchesRegularExpression(
            '/[\'"]title[\'"]\s*=>\s*[\'"]Add to this page[\'"][\s\S]{0,400}[\'"]icon[\'"]\s*=>\s*[\'"]heroicon-o-cursor-arrow-rays[\'"]/',
            $src,
            'The "Add to this page" entry MUST use heroicon-o-cursor-'
            . 'arrow-rays (or another recognisable pointer-style glyph) '
            . 'so novices read it as a "tap on the canvas" affordance.'
        );
    }

    #[Test]
    public function add_to_current_page_action_method_exists(): void
    {
        $src = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        $this->assertMatchesRegularExpression(
            '/public function addToCurrentPageAction\(\)\s*:\s*Action/',
            $src,
            'AdminLiveEditPage MUST declare a public addToCurrentPage'
            . 'Action(): Action method so the picker entry resolves at '
            . 'mount time.'
        );

        $this->assertStringContainsString(
            "Action::make('addToCurrentPageAction')",
            $src,
            'addToCurrentPageAction MUST construct a Filament Action '
            . 'with the matching name so the picker\'s '
            . 'replaceMountedAction call succeeds.'
        );
    }

    #[Test]
    public function action_fires_a_notification_with_the_workflow_text(): void
    {
        $src = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        // The action body MUST send a Filament Notification — that's
        // the novice-UX hand-off. The notification body must mention
        // "Insert layout" so the user knows which toolbar button to tap.
        $this->assertMatchesRegularExpression(
            '/addToCurrentPageAction[\s\S]*?Notification::make\(\)[\s\S]*?->title\([^)]*Insert\s*layout/m',
            $src,
            'addToCurrentPageAction MUST call Notification::make() '
            . 'with a title pointing the user at the "Insert layout" '
            . 'toolbar button.'
        );

        $this->assertMatchesRegularExpression(
            '/addToCurrentPageAction[\s\S]*?->send\(\)/m',
            $src,
            'addToCurrentPageAction MUST end the notification chain '
            . 'with ->send() so the toast actually appears.'
        );
    }

    #[Test]
    public function ai_167_anchor_documents_the_signposting_decision(): void
    {
        $src = $this->read('src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php');

        $this->assertStringContainsString(
            'AI-167',
            $src,
            'AdminLiveEditPage MUST carry the AI-167 anchor inline so '
            . 'the cycle-145 signposting decision is discoverable at '
            . 'refactor time.'
        );
        $this->assertStringContainsString(
            'cycle-145',
            $src,
            'AdminLiveEditPage MUST carry the cycle-145 anchor inline.'
        );
    }
}
