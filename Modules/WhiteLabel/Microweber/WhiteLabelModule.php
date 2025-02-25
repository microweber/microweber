<?php

namespace Modules\WhiteLabel\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\WhiteLabel\Filament\Pages\WhiteLabelSettingsAdminSettingsPage;

class WhiteLabelModule extends BaseModule
{
    public static string $name = 'White Label';
    public static string $module = 'white_label';
    public static string $icon = 'modules.white_label-icon';
    public static string $categories = 'advanced';
    public static int $position = 500;
    public static string $settingsComponent = WhiteLabelSettingsAdminSettingsPage::class;
    public static string $templatesNamespace = 'modules.white_label::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        // Add your module-specific data
        $viewData['customData'] = [
            'title' => 'White Label',
            'content' => 'White Label Module'
        ];

        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
