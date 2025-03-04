<?php

namespace Modules\Newsletter\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Newsletter\Filament\NewsletterModuleSettings;

class NewsletterModule extends BaseModule
{
    public static string $name = 'Newsletter';
    public static string $module = 'newsletter';
    public static string $icon = 'modules.newsletter-icon';
    public static string $categories = 'marketing';
    public static int $position = 55;
    public static string $templatesNamespace = 'modules.newsletter::templates';
    public static string $settingsComponent = NewsletterModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();

        // Provide default values if not set
        $viewData['title'] = $viewData['options']['title'] ?? $this->params['title'] ?? 'Newsletter';
        $viewData['description'] = $viewData['options']['description'] ?? $this->params['description'] ?? '';
        $viewData['require_terms'] = $viewData['options']['require_terms'] ?? $this->params['require_terms'] ?? false;
        $viewData['list_id'] = $viewData['options']['list_id'] ?? 0;

         $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
