<?php

namespace Modules\Content\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Content\Filament\ContentModuleSettings;
use Modules\Content\Models\Content;

class ContentModule extends BaseModule
{
    public static string $name = 'Content Module';
    public static string $module = 'content';
    public static string $icon = 'modules.content-icon';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = ContentModuleSettings::class;
    public static string $templatesNamespace = 'modules.content::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $rel_type = $this->params['rel_type'] ?? 'module';
        $rel_id = $this->params['rel_id'] ?? $this->params['id'];
       // $viewData['contents'] = Content::get()
        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
