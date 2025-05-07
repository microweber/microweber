<?php

namespace Modules\Accordion\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Accordion\Filament\AccordionModuleSettings;
use Modules\Accordion\Models\Accordion;

class AccordionModule extends BaseModule
{
    public static string $name = 'Accordion Module';
    public static string $module = 'accordion';
    public static string $icon = 'modules.accordion-icon';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = AccordionModuleSettings::class;
    public static string $templatesNamespace = 'modules.accordion::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $rel_type = $this->params['rel_type'] ?? 'module';
        $rel_id = $this->params['rel_id'] ?? $this->params['id'];
        $viewData['accordion'] = Accordion::where('rel_type', $rel_type)->where('rel_id', $rel_id)->orderBy('position', 'asc')->get();

        $viewData['accordionColor'] = $viewData['options']['accordionColor'] ?? 'var(--mw-primary-color);';
        $viewData['accordionHoverColor'] = $viewData['options']['accordionHoverColor'] ?? 'var(--mw-primary-color);';
        $viewData['accordionBorderColor'] = $viewData['options']['accordionBorderColor'] ?? 'var(--mw-primary-color);';
        $viewData['accordionContentColor'] = $viewData['options']['accordionContentColor'] ?? '#ffffff;';
        $viewData['accordionTextColor'] = $viewData['options']['accordionTextColor'] ?? '#000;';

        $viewData['defaults'] = [
            [
                'title' => 'Open settings and type your title',
                'content' => 'Open settings and type your content'
            ]
        ];
        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

}
