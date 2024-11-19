<?php

namespace Modules\Page\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Content\Microweber\ContentModule;
use Modules\Page\Filament\PageModuleSettings;
use Modules\Page\Models\Page;

class PageModule extends ContentModule
{
    public static string $name = 'Pages Module';
    public static string $module = 'pages';
    public static string $icon = 'modules.page-icon';
    public static string $categories = 'pages';
    public static int $position = 30;
    public static string $settingsComponent = PageModuleSettings::class;
    public static string $templatesNamespace = 'modules.page::templates';

    public static function getQueryBuilderFromOptions($optionsArray = []): \Illuminate\Database\Eloquent\Builder
    {
        return Page::query()->where('is_active', 1);
    }
}
