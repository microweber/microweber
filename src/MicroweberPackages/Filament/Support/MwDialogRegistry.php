<?php

declare(strict_types=1);

namespace MicroweberPackages\Filament\Support;

use Modules\Btn\Filament\BtnModuleSettings;
use Modules\Content\Filament\ContentModuleSettings;
use Modules\Pictures\Filament\PicturesModuleSettings;
use Modules\Post\Filament\PostModuleSettings;
use Modules\Product\Filament\ProductsModuleSettings;
use Modules\Slider\Filament\SliderModuleSettings;
use Modules\Video\Filament\VideoModuleSettings;

/**
 * Opt-in list of Filament module-settings pages that open inside mw.dialog
 * (via the livewire-modal host) instead of the Filament slide-over.
 */
final class MwDialogRegistry
{
    /**
     * Candidate module-settings classes. A class is exposed only when it
     * exists and reports usesMwDialog() = true.
     *
     * @return list<class-string>
     */
    public static function candidates(): array
    {
        return [
            VideoModuleSettings::class,
            SliderModuleSettings::class,
            BtnModuleSettings::class,
            PicturesModuleSettings::class,
            ContentModuleSettings::class,
            PostModuleSettings::class,
            ProductsModuleSettings::class,
        ];
    }

    /**
     * @return list<class-string>
     */
    public static function moduleSettingsClasses(): array
    {
        $enabled = [];

        // Seed: the hard-coded candidates keep a stable order and guarantee
        // the first-party modules are always present even before any module
        // registration has run.
        foreach (self::candidates() as $class) {
            if (self::optsIn($class)) {
                $enabled[$class] = true;
            }
        }

        // Dynamic discovery: any module-settings component registered at
        // runtime that opts in via usesMwDialog(). This lets third-party
        // modules participate without editing this class.
        foreach (self::discoveredCandidates() as $class) {
            if (self::optsIn($class)) {
                $enabled[$class] = true;
            }
        }

        return array_keys($enabled);
    }

    private static function optsIn(string $class): bool
    {
        return class_exists($class)
            && method_exists($class, 'usesMwDialog')
            && $class::usesMwDialog();
    }

    /**
     * Module-settings component classes registered at runtime, discovered
     * from ModuleAdmin (which itself merges the ModuleRegistry map). The map
     * is type => class; we only need the class strings here. Any failure is
     * swallowed so discovery can never break the hard-coded seed list.
     *
     * @return list<class-string>
     */
    private static function discoveredCandidates(): array
    {
        try {
            $components = \MicroweberPackages\Module\Facades\ModuleAdmin::getSettingsComponents();
        } catch (\Throwable) {
            return [];
        }

        if (! is_array($components)) {
            return [];
        }

        $classes = [];
        foreach ($components as $class) {
            if (is_string($class) && $class !== '') {
                $classes[] = $class;
            }
        }

        return $classes;
    }

    public static function supports(string $moduleSettingsClass): bool
    {
        return in_array($moduleSettingsClass, self::moduleSettingsClasses(), true);
    }
}
