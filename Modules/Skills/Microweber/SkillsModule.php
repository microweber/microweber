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
    public static string $templatesNamespace = 'modules.skills::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $skills = @json_decode($this->getOption('skills'), true) ?? [];

        if (!$skills) {
            $skills = $this->getDefaultSkills();
        }
        $viewData = array_merge($viewData, ['skills' => $skills]);

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
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
