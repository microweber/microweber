<?php

namespace Modules\Skills\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Skills\Filament\SkillsModuleSettings;

class SkillsModule extends BaseModule
{
    public static string $name = 'Skills';
    public static string $module = 'skills';
    public static string $icon = 'modules.skills-icon';
    public static string $categories = 'other';
    public static int $position = 41;
    public static string $settingsComponent = SkillsModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();

        // Retrieve settings from the SkillsModuleSettings
      // $settings = app(SkillsModuleSettings::class)->getSettings();

        // Prepare skills data for the view
//        $skills = [
//            [
//                'skill' => $settings['options']['skill_name'] ?? 'Default Skill',
//                'percent' => $settings['options']['percent'] ?? 0,
//                'style' => $settings['options']['style'] ?? 'primary',
//            ]
//        ];

        $skills=[
            [
                'skill' => 'HTML',
                'percent' => 90,
                'style' => 'primary',
            ],
            [
                'skill' => 'CSS',
                'percent' => 80,
                'style' => 'success',
            ],
            [
                'skill' => 'JavaScript',
                'percent' => 70,
                'style' => 'info',
            ],
            [
                'skill' => 'PHP',
                'percent' => 60,
                'style' => 'warning',
            ],
            [
                'skill' => 'Laravel',
                'percent' => 50,
                'style' => 'danger',
            ],
        ];
        // Merge skills data into view data
        $viewData = array_merge($viewData, ['skills' => $skills]);

        return view('modules.skills::templates.default', $viewData);
    }
}
