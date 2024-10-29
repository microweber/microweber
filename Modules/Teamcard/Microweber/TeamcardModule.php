<?php

namespace Modules\Teamcard\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Teamcard\Filament\TeamcardModuleSettings;

class TeamcardModule extends BaseModule
{
    public static string $name = 'Teamcard Module';
    public static string $module = 'teamcard';
    public static string $icon = 'heroicon-o-user-group';
    public static string $categories = 'team, card';
    public static int $position = 1;
    public static string $settingsComponent = TeamcardModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();

        return view('modules.teamcard::templates.default', $viewData);
    }

}
