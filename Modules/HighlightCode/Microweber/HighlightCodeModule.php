<?php

namespace Modules\HighlightCode\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use MicroweberPackages\Modules\HighlightCode\Filament\HighlightCodeModuleSettings;

class HighlightCodeModule extends BaseModule
{
    public static string $name = 'HighlightCode Module';
    public static string $icon = 'heroicon-o-map';
    public static string $categories = 'other';
    public static int $position = 1;
    public static string $settingsComponent = HighlightCodeModuleSettings::class;
    public static string $templatesNamespace = 'modules.highlight_code::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        return view('modules.highlight_code::templates.default', $viewData);
     }
}
