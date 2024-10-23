<?php

namespace Modules\Btn\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Btn\Filament\BtnModuleSettings;

class BtnModule extends BaseModule
{
    public static string $name = 'Button';
    public static string $icon = 'heroicon-o-rectangle-stack';
    public static string $categories = 'other';
    public static int $position = 2;
    public static string $settingsComponent = BtnModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();

        return view('modules.btn::btn-layout', $viewData);


    }


}
