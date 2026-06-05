<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-modsettings-save — save the create/edit form from a teleported
 * module-settings modal.
 *
 * Inside the live-edit slide-over each Module Settings page is a nested iframe.
 * Its create/edit modal (Menu add item, Accordion/Tabs/Content table actions, …)
 * is hoisted to <body> to escape the stacking trap, OUTSIDE the embedded Livewire
 * component's wire:id subtree — so the modal form's
 * wire:submit="callMountedAction" (or callMountedTableAction, …) has no component
 * to bind to: the footer Save button fired a native submit event Livewire never
 * handled and the row could not be saved (verified in-browser: the Save button
 * left the menu item un-created; the owning admin-menus-list component's
 * $wire.callMountedAction() created it).
 *
 * Fix: a submit interceptor in the shared module-settings layout catches the
 * orphaned submit (callMounted* wire:submit AND no wire:id ancestor), finds the
 * Livewire component whose matching mounted-action bucket is non-empty, and
 * invokes that call on it. Forms still inside a wire:id ancestor are untouched.
 * Verified in-browser: the Menu "Add menu item" Save button now persists the row
 * (count 3 → 4) and closes the modal.
 */
class ModuleSettingsSaveTeleportedFormContractTest extends TestCase
{
    private string $layout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->layout = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit-module-settings.blade.php'
        ));
    }

    #[Test]
    public function submit_interceptor_invokes_the_owning_components_mounted_call(): void
    {
        $this->assertMatchesRegularExpression(
            "/document\.addEventListener\(\s*'submit'[\s\S]{0,1500}w\[s\]\(\)/",
            $this->layout,
            'The module-settings layout must invoke the owning component call (w[s]()) on an orphaned form submit.'
        );
    }

    #[Test]
    public function interceptor_maps_every_mounted_call_to_its_bucket(): void
    {
        foreach ([
            'callMountedAction' => 'mountedActions',
            'callMountedTableAction' => 'mountedTableActions',
            'callMountedTableBulkAction' => 'mountedTableBulkActions',
            'callMountedFormComponentAction' => 'mountedFormComponentActions',
        ] as $call => $bucket) {
            $this->assertMatchesRegularExpression(
                '/' . $call . ':\s*\'' . $bucket . '\'/',
                $this->layout,
                "The interceptor must map {$call} to its {$bucket} bucket."
            );
        }
    }

    #[Test]
    public function interceptor_only_acts_on_orphaned_teleported_forms(): void
    {
        // gate on callMounted* wire:submit...
        $this->assertStringContainsString("s.indexOf('callMounted') !== 0", $this->layout,
            'The interceptor must only act on callMounted* submit handlers.');
        // ...and skip forms that still have a wire:id ancestor (not teleported).
        $this->assertMatchesRegularExpression(
            "/f\.closest\(\s*'\[wire\\\\\\\\:id\]'\s*\)/",
            $this->layout,
            'The interceptor must skip forms that still have a wire:id ancestor.'
        );
        // capture phase + preventDefault
        $start = strpos($this->layout, "document.addEventListener('submit'");
        $this->assertNotFalse($start);
        $slice = substr($this->layout, $start, 1500);
        $this->assertStringContainsString('e.preventDefault()', $slice);
        $this->assertStringContainsString('}, true)', $slice);
    }
}
