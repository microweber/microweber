<?php

namespace Modules\Teamcard\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Teamcard\Filament\TeamcardModuleSettings;
use Modules\Teamcard\Models\Teamcard;

class TeamcardModule extends BaseModule
{
    public static string $name = 'Team Card';
    public static string $module = 'teamcard';
    public static string $icon = 'heroicon-o-user-group';
    public static string $categories = 'miscellaneous';
    public static int $position = 57;
    public static string $settingsComponent = TeamcardModuleSettings::class;
    public static string $templatesNamespace = 'modules.teamcard::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $viewData['teamcard'] = Teamcard::where('module_id', $this->params['id'])->get();

        return view('modules.teamcard::templates.default', $viewData);
    }

}
