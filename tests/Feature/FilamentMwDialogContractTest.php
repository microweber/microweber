<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Source-level contract for the Filament mw.dialog integration.
 * Reads files without booting Laravel.
 */
class FilamentMwDialogContractTest extends TestCase
{
    private string $root;

    protected function setUp(): void
    {
        parent::setUp();
        $this->root = dirname(__DIR__, 2);
    }

    #[Test]
    public function livewire_modal_package_ships_mw_dialog_and_bare_skins(): void
    {
        $this->assertFileExists($this->root . '/packages/microweber-livewire-modal/resources/views/skins/mw-dialog.blade.php');
        $this->assertFileExists($this->root . '/packages/microweber-livewire-modal/resources/views/skins/bare.blade.php');

        $skin = (string) file_get_contents($this->root . '/packages/microweber-livewire-modal/resources/views/skins/mw-dialog.blade.php');
        $this->assertStringContainsString('data-mw-dialog-skin="1"', $skin);
        $this->assertStringContainsString('data-mw-dialog-options', $skin);
    }

    #[Test]
    public function filament_action_macro_file_exists(): void
    {
        $path = $this->root . '/src/MicroweberPackages/Filament/Support/RegistersMwDialogMacro.php';
        $this->assertFileExists($path);
        $src = (string) file_get_contents($path);
        $this->assertStringContainsString("macro('mwDialog'", $src);
        $this->assertStringContainsString('data-mw-dialog', $src);
    }

    #[Test]
    public function opted_in_modules_set_use_mw_dialog_flag(): void
    {
        $files = [
            $this->root . '/Modules/Video/Filament/VideoModuleSettings.php',
            $this->root . '/Modules/Slider/Filament/SliderModuleSettings.php',
            $this->root . '/Modules/Btn/Filament/BtnModuleSettings.php',
            $this->root . '/Modules/Pictures/Filament/PicturesModuleSettings.php',
            $this->root . '/Modules/Content/Filament/ContentModuleSettings.php',
        ];

        foreach ($files as $file) {
            $src = (string) file_get_contents($file);
            $this->assertStringContainsString(
                'protected static bool $useMwDialog = true',
                $src,
                basename($file) . ' must opt in to mw.dialog'
            );
        }
    }

    #[Test]
    public function content_and_slider_table_actions_call_mw_dialog(): void
    {
        $content = (string) file_get_contents($this->root . '/Modules/Content/Filament/ContentTableList.php');
        $slider = (string) file_get_contents($this->root . '/Modules/Slider/Filament/SliderTableList.php');

        $this->assertStringContainsString('->mwDialog(', $content);
        $this->assertStringContainsString('->mwDialog(', $slider);
    }
}
