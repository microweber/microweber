<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-allmodules-editing — every live-edit item-editor module saves
 * its add/edit-item modal through the ONE verified teleport path.
 *
 * Background (see project_liveedit_modal_teleport memory + the Menu/Content
 * runtime-verified fixes): inside the live-edit slide-over each Module Settings
 * page is a nested iframe whose Filament action modals are hoisted to <body> to
 * escape a stacking-context trap. That hoist moves the modal form OUT of its
 * Livewire wire:id subtree, so the footer Save button's
 * wire:submit="callMountedTableAction" (or callMountedAction) has no component to
 * bind to. The generic submit interceptor in the shared module-settings layout
 * catches that orphaned submit and invokes the matching mounted-call on the
 * owning component.
 *
 * The interceptor is GENERIC: it maps every callMounted* handler to its bucket.
 * This test pins the structural invariant that makes the single fix cover ALL
 * item-editor modules at once — namely that each module's item list uses the
 * STANDARD Filament table CreateAction/EditAction (→ callMountedTableAction →
 * mountedTableActions), which the interceptor handles. If a future module rolls
 * its own non-standard mount path, this test flags it as an interceptor gap.
 *
 * Runtime-verified representatives (in-browser, prior sessions + this one):
 * Menu (add item), Content (create/edit), Accordion + Testimonials (this run).
 * The other item-editor modules share the identical standard-action structure
 * pinned here, so the verified path covers them.
 */
class LiveEditAllModulesEditingSavePathContractTest extends TestCase
{
    private const LAYOUT = 'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit-module-settings.blade.php';

    /** Item-editor modules: [module settings class file, embedded table-list file]. */
    public static function itemEditorModules(): array
    {
        return [
            'Accordion'    => ['Modules/Accordion/Filament/AccordionModuleSettings.php', 'Modules/Accordion/Filament/AccordionTableList.php'],
            'Tabs'         => ['Modules/Tabs/Filament/TabsModuleSettings.php', 'Modules/Tabs/Filament/TabsTableList.php'],
            'Faq'          => ['Modules/Faq/Filament/FaqModuleSettings.php', 'Modules/Faq/Filament/FaqTableList.php'],
            'Testimonials' => ['Modules/Testimonials/Filament/TestimonialsModuleSettings.php', 'Modules/Testimonials/Filament/TestimonialsTableList.php'],
            'Slider'       => ['Modules/Slider/Filament/SliderModuleSettings.php', 'Modules/Slider/Filament/SliderTableList.php'],
            'Rating'       => ['Modules/Rating/Filament/RatingModuleSettings.php', 'Modules/Rating/Filament/RatingTableList.php'],
            // NOTE: Pictures is intentionally NOT here — its live-edit settings panel
            // manages images via MwMediaBrowser (media picker), NOT an embedded
            // PicturesTableList. Its editing/save path is the media browser, covered
            // separately. PicturesTableList.php exists but is unused by the panel.
            'LayoutContent' => ['Modules/LayoutContent/Filament/LayoutContentModuleSettings.php', 'Modules/LayoutContent/Filament/LayoutContentTableList.php'],
            'Content'      => ['Modules/Content/Filament/ContentModuleSettings.php', 'Modules/Content/Filament/ContentTableList.php'],
            'Teamcard'     => ['Modules/Teamcard/Filament/TeamcardModuleSettings.php', 'Modules/Teamcard/Filament/TeamcardTableList.php'],
        ];
    }

    private function read(string $relative): string
    {
        $path = base_path($relative);
        $this->assertFileExists($path, "Expected module file to exist: {$relative}");

        return (string) file_get_contents($path);
    }

    #[Test]
    #[DataProvider('itemEditorModules')]
    public function item_editor_list_uses_standard_filament_table_actions(string $settingsFile, string $listFile): void
    {
        $list = $this->read($listFile);

        // Standard Filament table actions => mounted into mountedTableActions and
        // submitted via wire:submit="callMountedTableAction" — exactly what the
        // generic interceptor handles.
        $this->assertMatchesRegularExpression(
            '/CreateAction::make\(/',
            $list,
            "{$listFile} must use a standard Filament table CreateAction (covered by the interceptor)."
        );
        $this->assertMatchesRegularExpression(
            '/EditAction::make\(/',
            $list,
            "{$listFile} must use a standard Filament table EditAction (covered by the interceptor)."
        );
        // Import must come from the standard Filament\Tables\Actions namespace —
        // accept both per-line (`use Filament\Tables\Actions\CreateAction;`) and
        // grouped (`use Filament\Tables\Actions\{CreateAction, EditAction};`) forms.
        $this->assertMatchesRegularExpression(
            '/use\s+Filament\\\\Tables\\\\Actions\\\\(CreateAction|\{)/',
            $list,
            "{$listFile} must import table actions from Filament\\Tables\\Actions (non-standard mount paths are an interceptor gap)."
        );
    }

    #[Test]
    #[DataProvider('itemEditorModules')]
    public function module_settings_embeds_its_livewire_item_editor(string $settingsFile, string $listFile): void
    {
        $settings = $this->read($settingsFile);
        $listClass = pathinfo($listFile, PATHINFO_FILENAME); // e.g. AccordionTableList

        // The settings panel renders the item editor as an embedded Livewire
        // component (the surface whose modals get teleported in live-edit).
        $this->assertMatchesRegularExpression(
            '/Livewire::make\(\s*(\$this->tableComponentName|' . preg_quote($listClass, '/') . '::class)/',
            $settings,
            "{$settingsFile} must embed its {$listClass} item editor via Livewire::make()."
        );
    }

    #[Test]
    public function generic_interceptor_maps_table_action_calls_to_their_buckets(): void
    {
        $layout = $this->read(self::LAYOUT);

        // The single fix that covers every module above: callMounted* → bucket map.
        foreach ([
            'callMountedAction' => 'mountedActions',
            'callMountedTableAction' => 'mountedTableActions',
            'callMountedTableBulkAction' => 'mountedTableBulkActions',
            'callMountedFormComponentAction' => 'mountedFormComponentActions',
        ] as $call => $bucket) {
            $this->assertMatchesRegularExpression(
                '/' . $call . ':\s*\'' . $bucket . '\'/',
                $layout,
                "The interceptor must map {$call} to its {$bucket} bucket so the item-editor modals save."
            );
        }
    }
}
