<?php

namespace Modules\Post\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Post\Filament\PostModuleSettings;
use Modules\Post\Models\Post;

class PostModule extends BaseModule
{
    public static string $name = 'Posts Module';
    public static string $module = 'posts';
    public static string $icon = 'modules.post-icon';
    public static string $categories = 'posts';
    public static int $position = 30;
    public static string $settingsComponent = PostModuleSettings::class;
    public static string $templatesNamespace = 'modules.post::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        $viewData['data'] = [];
        $data = static::getQueryBuilderFromOptions($viewData['options'])->get();
        if ($data and !$data->empty()) {
            $viewData['data'] = $data;
        }

        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }

    public static function getQueryBuilderFromOptions($optionsArray = []): \Illuminate\Database\Eloquent\Builder
    {
        return Post::query()->where('is_active', 1);
    }
}
