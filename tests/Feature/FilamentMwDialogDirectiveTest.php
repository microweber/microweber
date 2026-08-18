<?php

declare(strict_types=1);

namespace Tests\Feature;

use Filament\Actions\Action;
use MicroweberPackages\Filament\Support\MwDialogOptions;
use MicroweberPackages\Filament\Support\MwDialogRegistry;
use MicroweberPackages\Filament\Support\RegistersMwDialogMacro;
use Modules\Btn\Filament\BtnModuleSettings;
use Modules\Content\Filament\ContentModuleSettings;
use Modules\Pictures\Filament\PicturesModuleSettings;
use Modules\Post\Filament\PostModuleSettings;
use Modules\Product\Filament\ProductsModuleSettings;
use Modules\Slider\Filament\SliderModuleSettings;
use Modules\Video\Filament\VideoModuleSettings;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FilamentMwDialogDirectiveTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        RegistersMwDialogMacro::register();
    }

    #[Test]
    public function action_macro_is_registered(): void
    {
        $this->assertTrue(Action::hasMacro('mwDialog'));
    }

    #[Test]
    public function mw_dialog_sets_data_attributes_and_disables_slide_over(): void
    {
        $action = Action::make('demo')->mwDialog([
            'width' => 640,
            'overlayClose' => true,
        ]);

        $this->assertFalse($action->isModalSlideOver());

        $html = $action->getExtraModalWindowAttributeBag()->toHtml();
        $this->assertStringContainsString('data-mw-dialog="1"', $html);
        $this->assertStringContainsString('mw-filament-mw-dialog', $html);
        $this->assertStringContainsString('data-mw-dialog-options', $html);
        $this->assertStringContainsString('640', $html);
    }

    #[Test]
    public function mw_dialog_false_is_a_no_op(): void
    {
        $action = Action::make('demo')->mwDialog(false);
        $html = $action->getExtraModalWindowAttributeBag()->toHtml();
        $this->assertStringNotContainsString('data-mw-dialog="1"', $html);
    }

    #[Test]
    public function default_options_include_mw_dialog_controls(): void
    {
        $opts = MwDialogOptions::defaults();

        $this->assertTrue($opts['closeButton']);
        $this->assertTrue($opts['overlay']);
        $this->assertFalse($opts['overlayClose']);
        $this->assertTrue($opts['autoHeight']);
        $this->assertTrue($opts['autosize']);
        $this->assertTrue($opts['autoScroll']);
        $this->assertTrue($opts['draggable']);
        $this->assertTrue($opts['closeOnEscape']);
        $this->assertSame(800, $opts['width']);
        $this->assertSame('mw-dialog', $opts['skin']);
    }

    #[Test]
    public function opted_in_modules_use_mw_dialog(): void
    {
        $this->assertTrue(VideoModuleSettings::usesMwDialog());
        $this->assertTrue(SliderModuleSettings::usesMwDialog());
        $this->assertTrue(BtnModuleSettings::usesMwDialog());
        $this->assertTrue(PicturesModuleSettings::usesMwDialog());
        $this->assertTrue(ContentModuleSettings::usesMwDialog());
        $this->assertTrue(PostModuleSettings::usesMwDialog());
        $this->assertTrue(ProductsModuleSettings::usesMwDialog());
    }

    #[Test]
    public function registry_lists_opted_in_module_settings(): void
    {
        $classes = MwDialogRegistry::moduleSettingsClasses();

        $this->assertContains(VideoModuleSettings::class, $classes);
        $this->assertContains(SliderModuleSettings::class, $classes);
        $this->assertContains(BtnModuleSettings::class, $classes);
        $this->assertContains(PicturesModuleSettings::class, $classes);
        $this->assertContains(ContentModuleSettings::class, $classes);
        $this->assertContains(PostModuleSettings::class, $classes);
        $this->assertContains(ProductsModuleSettings::class, $classes);
        $this->assertTrue(MwDialogRegistry::supports(VideoModuleSettings::class));
    }

    #[Test]
    public function slide_right_and_slide_left_macros_are_registered(): void
    {
        $this->assertTrue(Action::hasMacro('slideRight'));
        $this->assertTrue(Action::hasMacro('slideLeft'));
    }

    #[Test]
    public function slide_right_and_slide_left_enable_slide_over(): void
    {
        $right = Action::make('demo-right')->slideRight();
        $this->assertTrue($right->isModalSlideOver());

        $left = Action::make('demo-left')->slideLeft();
        $this->assertTrue($left->isModalSlideOver());

        // A falsy condition must not force slide-over on.
        $off = Action::make('demo-off')->slideRight(false);
        $this->assertFalse($off->isModalSlideOver());
    }

    #[Test]
    public function registry_discovers_runtime_registered_opt_in_modules(): void
    {
        // A module registered at runtime that opts in must be discovered
        // without being hard-coded in MwDialogRegistry::candidates().
        \MicroweberPackages\Module\Facades\ModuleAdmin::registerSettingsComponent(
            'mwdialog-harvest-optin',
            MwDialogOptInStubSettings::class
        );

        $this->assertContains(MwDialogOptInStubSettings::class, MwDialogRegistry::moduleSettingsClasses());
        $this->assertTrue(MwDialogRegistry::supports(MwDialogOptInStubSettings::class));
    }

    #[Test]
    public function registry_ignores_runtime_registered_modules_that_do_not_opt_in(): void
    {
        \MicroweberPackages\Module\Facades\ModuleAdmin::registerSettingsComponent(
            'mwdialog-harvest-optout',
            MwDialogOptOutStubSettings::class
        );

        $this->assertNotContains(MwDialogOptOutStubSettings::class, MwDialogRegistry::moduleSettingsClasses());
    }
}

class MwDialogOptInStubSettings
{
    public static function usesMwDialog(): bool
    {
        return true;
    }
}

class MwDialogOptOutStubSettings
{
    public static function usesMwDialog(): bool
    {
        return false;
    }
}
