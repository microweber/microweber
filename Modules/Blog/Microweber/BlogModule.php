<?php

namespace Modules\Blog\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Blog\Filament\BlogSettings;

class BlogModule extends BaseModule
{
    public static string $name = 'Blog';
    public static string $module = 'blog';
    public static string $icon = 'heroicon-o-newspaper';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = BlogSettings::class;
    public static string $templatesNamespace = 'modules.blog::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        
        // Add blog-specific data
        $viewData['posts_per_page'] = $viewData['options']['posts_per_page'] ?? 10;
        $viewData['show_categories'] = $viewData['options']['show_categories'] ?? true;
        $viewData['show_tags'] = $viewData['options']['show_tags'] ?? true;
        $viewData['layout'] = $viewData['options']['layout'] ?? 'grid';
        
        // Get template from options or use default
        $template = $viewData['template'] ?? 'default';

        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
