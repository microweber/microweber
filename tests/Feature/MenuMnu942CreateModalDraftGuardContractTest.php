<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-mnu942 / AI-942 — Live-edit Create modals: unsaved-draft
 * protection. Jira: https://microweber.atlassian.net/browse/AI-942
 *
 * The content-create modal (page/post/product/category) already guards against
 * accidental dismissal via ->closeModalByClickingAway(false) +
 * ->closeModalByEscaping(false) (AdminLiveEditPage, task-2026-05-02-354958).
 * The Menu module-settings Create/Edit modals — which render in the same
 * live-edit surface and ask the user to type a menu item Title + Link — lacked
 * that protection. After task-2026-06-05-mnu-modal restored a real, clickable
 * backdrop on this surface, a stray click-away or Escape would silently discard
 * typed content. This pins the option-3 mitigation (disable backdrop-click +
 * Escape dismissal) on every form-bearing Menu action.
 *
 * The destructive CONFIRM actions (delete, deleteMenu) deliberately KEEP the
 * default dismiss-to-cancel behaviour: dismissing a delete confirmation is the
 * safe outcome, so forcing those open would be a regression.
 */
class MenuMnu942CreateModalDraftGuardContractTest extends TestCase
{
    private string $source;

    protected function setUp(): void
    {
        parent::setUp();
        $this->source = (string) file_get_contents(base_path(
            'Modules/Menu/Livewire/Admin/MenusList.php'
        ));
    }

    /**
     * Extract a public method body by name via brace-balance counting from the
     * method's opening brace, so inner blocks/closures don't truncate the slice.
     */
    private function methodBody(string $name): string
    {
        $needle = 'public function ' . $name . '(';
        $pos = strpos($this->source, $needle);
        $this->assertNotFalse($pos, "Method {$name}() must exist in MenusList.php.");

        $brace = strpos($this->source, '{', $pos);
        $this->assertNotFalse($brace);

        $depth = 0;
        $len = strlen($this->source);
        for ($i = $brace; $i < $len; $i++) {
            $ch = $this->source[$i];
            if ($ch === '{') {
                $depth++;
            } elseif ($ch === '}') {
                $depth--;
                if ($depth === 0) {
                    return substr($this->source, $brace, $i - $brace + 1);
                }
            }
        }

        $this->fail("Could not balance braces for {$name}().");
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function formBearingActions(): array
    {
        return [
            'addMenuItemAction' => ['addMenuItemAction'],
            'createAction' => ['createAction'],
            'editAction' => ['editAction'],
            'renameMenuAction' => ['renameMenuAction'],
        ];
    }

    #[Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('formBearingActions')]
    public function form_bearing_action_disables_clickaway_dismiss(string $method): void
    {
        $body = $this->methodBody($method);
        $this->assertStringContainsString('->closeModalByClickingAway(false)', $body,
            "{$method}() must disable backdrop-click dismissal so typed content is not lost.");
    }

    #[Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('formBearingActions')]
    public function form_bearing_action_disables_escape_dismiss(string $method): void
    {
        $body = $this->methodBody($method);
        $this->assertStringContainsString('->closeModalByEscaping(false)', $body,
            "{$method}() must disable Escape-key dismissal so typed content is not lost.");
    }

    #[Test]
    public function destructive_confirm_actions_keep_safe_dismiss(): void
    {
        // Dismissing a delete confirmation should CANCEL the delete — keeping
        // the default dismiss behaviour is the safe outcome here.
        foreach (['deleteAction', 'deleteMenuAction'] as $method) {
            $body = $this->methodBody($method);
            $this->assertStringNotContainsString('->closeModalByClickingAway(false)', $body,
                "{$method}() is a destructive confirmation — it must keep dismiss-to-cancel.");
            $this->assertStringNotContainsString('->closeModalByEscaping(false)', $body,
                "{$method}() is a destructive confirmation — it must keep dismiss-to-cancel.");
        }
    }
}
