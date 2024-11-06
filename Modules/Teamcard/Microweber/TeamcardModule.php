<?php

namespace Modules\Teamcard\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Teamcard\Filament\TeamcardModuleSettings;
use Modules\Teamcard\Models\Teamcard;

class TeamcardModule extends BaseModule
{
    public static string $name = 'Team Card';
    public static string $module = 'teamcard';
    public static string $icon = 'modules.teamcard-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 57;
    public static string $settingsComponent = TeamcardModuleSettings::class;
    public static string $templatesNamespace = 'modules.teamcard::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $rel_type = $this->params['rel_type'] ?? 'module';
        $rel_id = $this->params['rel_id'] ?? $this->params['id'];
        $viewData['teamcard'] = Teamcard::where('rel_type', $rel_type)->where('rel_id', $rel_id)->get();

        return view('modules.teamcard::templates.default', $viewData);
    }

}
