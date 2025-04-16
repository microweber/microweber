<?php

namespace MicroweberPackages\Filament\Support;

use Illuminate\Support\Str;

class FilamentHelpers
{
    public static function getNavigationItemIcon(\Filament\Navigation\NavigationItem $item): string
    {

        $icon = '';

        if (method_exists($item, 'getIcon')) {
            $icon = $item->getIcon() ?? '';

        }

        if(!$icon and function_exists('module_info')) {
            $reflectionClass = new \ReflectionClass($item);

            $refLabel = $reflectionClass->getProperty('label');

            if ($refLabel) {
                $refLabelVal = $refLabel->getValue($item);
                $singularName = Str::singular($refLabelVal);

                if($refLabelVal){


                   $checkForModuleIcon = module_info($refLabelVal);

                    if(!empty($checkForModuleIcon) and isset($checkForModuleIcon['icon']) and $checkForModuleIcon['icon'] != '') {
                        $icon = $checkForModuleIcon['icon'];
                    } else {
                        $checkForModuleIcon = module_info($singularName);
                        if(!empty($checkForModuleIcon) and isset($checkForModuleIcon['icon']) and $checkForModuleIcon['icon'] != '') {
                            $icon = $checkForModuleIcon['icon'];
                        }
                    }
                 }

            }

        }


        return $icon;
    }

    public static function getNavigationItemDescription(\Filament\Navigation\NavigationItem $item): string
    {
        // a reflection class for the item to get the description
        $description = '';
        $reflectionClass = new \ReflectionClass($item);
        $refIsActive = $reflectionClass->getProperty('isActive');

        if ($refIsActive) {
            $isActiveVal = $refIsActive->getValue($item);

            if (is_closure($isActiveVal)) {
                $reflector = new \ReflectionFunction($isActiveVal);
                $reflectionClassClosure = $reflector->getClosureCalledClass();

                if ($reflectionClassClosure && method_exists($reflectionClassClosure->getName(), 'getDescription')) {
                    try {
                        $descriptionMethod = $reflectionClassClosure->getMethod('getDescription');
                        if ($descriptionMethod->isStatic()) {
                            $description = $reflectionClassClosure->getName()::getDescription();
                        } else {
                            $description = app()->make($reflectionClassClosure->getName())->getDescription();
                        }
                    } catch (\Exception $e) {
                        // Method not accessible or other reflection error
                    }
                }
            }
        }

        return $description;
    }
}
