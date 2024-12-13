<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminLanguagePage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-language';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Language';

    protected static string $description = 'Configure your language settings';

    public static function getNavigation(): array
    {
        return [
            'label' => static::$title,
            'icon' => 'heroicon-o-language',
            'url' => static::getUrl(),
            'group' => 'Settings',
            'sort' => 1,
            'iconHtml' => '<i class="mw-language" style="font-size: 30px;"></i>', // Customize font size here
        ];
    }

}
