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
        $skills = @json_decode($this->getOption('skills'), true) ?? [];

        if (!$skills) {
            $skills = $this->getDefaultSkills();
        }
        $viewData = array_merge($viewData, ['skills' => $skills]);
        return view('modules.skills::templates.default', $viewData);
    }

    public function getDefaultSkills()
    {

        return [
            [
                'skill' => 'HTML',
                'percent' => 90,
                'style' => 'primary',
            ],

        ];
    }
}
