<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-addcontent-save — save the create form from a teleported modal.
 *
 * Live-edit action modals are teleported to .fi-layout to escape a stacking
 * trap. That leaves the modal's form with NO wire:id ancestor, so its
 * wire:submit="callMountedAction" has no Livewire component to bind to: the
 * footer Save button (type=submit) and the SAVE pill (form.requestSubmit) fire a
 * native submit event Livewire never handles, and the create form could not be
 * saved (verified in-browser: a trusted Save click left the post un-created;
 * $wire.callMountedAction() at the page root created it).
 *
 * Fix: a page-root capture-phase submit interceptor (iframe-page.blade.php
 * x-init) runs $wire.callMountedAction() for an orphaned callMountedAction form
 * (wire:submit === 'callMountedAction' AND not inside .fi-main-ctn). Forms still
 * inside .fi-main-ctn are left to Livewire (no regression). Verified in-browser:
 * the +ADD Create Post Save button now creates the post, closes the modal, and
 * navigates the canvas to the new content.
 */
class AddContentSaveTeleportedFormContractTest extends TestCase
{
    private string $src;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php'
        ));
    }

    #[Test]
    public function page_root_submit_interceptor_runs_the_mounted_action(): void
    {
        $this->assertMatchesRegularExpression(
            "/document\.addEventListener\(\s*'submit'[\s\S]{0,400}\\\$wire\.callMountedAction\(\)/",
            $this->src,
            'A page-root submit listener must call $wire.callMountedAction() for the teleported form.'
        );
    }

    #[Test]
    public function only_orphaned_callmountedaction_forms_are_intercepted(): void
    {
        // Gate: wire:submit must equal callMountedAction...
        $this->assertMatchesRegularExpression(
            "/s\s*!==\s*'callMountedAction'/",
            $this->src,
            'The interceptor must only act on callMountedAction forms.'
        );
        // ...and the form must NOT be inside .fi-main-ctn (i.e. it is teleported);
        // non-teleported forms stay with Livewire.
        $this->assertMatchesRegularExpression(
            "/f\.closest\(\s*'\.fi-main-ctn'\s*\)/",
            $this->src,
            'The interceptor must skip forms still inside .fi-main-ctn (no regression for normal Livewire submit).'
        );
    }

    #[Test]
    public function interceptor_uses_capture_phase_and_prevents_default(): void
    {
        $start = strpos($this->src, "document.addEventListener('submit'");
        $this->assertNotFalse($start);
        $slice = substr($this->src, $start, 700);
        $this->assertStringContainsString('e.preventDefault()', $slice,
            'The interceptor must preventDefault so the native (handler-less) submit does not reload the page.');
        $this->assertStringContainsString('}, true)', $slice,
            'The interceptor must run on the capture phase.');
        $this->assertStringNotContainsString('"', $slice,
            'The interceptor must use single quotes only (it lives inside an x-init double-quoted attribute).');
    }
}
