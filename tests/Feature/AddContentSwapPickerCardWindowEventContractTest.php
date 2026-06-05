<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-addcontent-swap — +ADD picker cards open the create form.
 *
 * The live-edit +ADD picker (addContentAction) is teleported to .fi-layout
 * (iframe-page.blade.php) to escape a stacking-context trap that otherwise makes
 * its inputs un-clickable. That teleport moves the modal OUT of the Livewire
 * wire:id subtree, so a `wire:click` on a picker card cannot resolve its
 * component and silently does nothing — clicking Page / Post / Product / Category
 * / Image left the picker open and never opened the create form (the
 * "+ADD card does nothing" report; confirmed in-browser: the card's own
 * window.dispatchEvent fired but its $wire call was a no-op).
 *
 * Fix: the cards dispatch a `liveEditOpenCreateContent` window CustomEvent —
 * window events DO cross the teleport boundary — and a page-root listener (in
 * the iframe-page x-init, where $wire is intact) runs swapAction(action) to
 * unmount the picker and mount the create action fresh. Verified in-browser:
 * the Post and Page cards now open "Create post" / "Create page".
 */
class AddContentSwapPickerCardWindowEventContractTest extends TestCase
{
    private string $iframePage;
    private string $modal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->iframePage = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php'
        ));
        $this->modal = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        ));
    }

    #[Test]
    public function page_root_listener_swaps_to_the_create_action(): void
    {
        $this->assertMatchesRegularExpression(
            "/window\.addEventListener\(\s*'liveEditOpenCreateContent'[\s\S]{0,260}swapAction\(\s*act\s*,\s*\{\s*\}\s*\)/",
            $this->iframePage,
            'iframe-page must register a liveEditOpenCreateContent listener that calls swapAction(act, {}).'
        );
    }

    #[Test]
    public function cards_dispatch_the_window_event_not_wire_click(): void
    {
        $this->assertStringContainsString(
            "window.dispatchEvent(new CustomEvent('liveEditOpenCreateContent'",
            $this->modal,
            'The picker cards must dispatch the liveEditOpenCreateContent window event.'
        );

        // Strip Blade comments so the negative assertion does not self-match on
        // the explanatory comment that names the old pattern.
        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->modal);
        $this->assertStringNotContainsString(
            "wire:click=\"replaceMountedAction(",
            $stripped,
            'The picker cards must NOT use wire:click="replaceMountedAction(...)" — it is dead under the teleport.'
        );
    }

    #[Test]
    public function listener_block_has_no_double_quote_that_would_truncate_the_x_init(): void
    {
        // The listener lives inside an x-init double-quoted attribute; a literal
        // double-quote anywhere in it truncates the attribute and leaks the rest
        // of the script as visible page text. Slice the listener body and assert
        // it carries no double-quote character.
        $start = strpos($this->iframePage, "addEventListener('liveEditOpenCreateContent'");
        $this->assertNotFalse($start);
        $slice = substr($this->iframePage, $start, 240);
        $this->assertStringNotContainsString('"', $slice,
            'The liveEditOpenCreateContent listener must use single quotes only (no double-quote inside the x-init attribute).');
    }
}
