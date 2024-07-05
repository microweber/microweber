<?php

namespace MicroweberPackages\Modules\Menu\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;

class AdminMenusPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-seo';

    protected static ?string $navigationGroup = 'Website Settings';

    protected static string $view = 'filament.admin.pages.settings-seo';

    protected static ?string $title = 'Menu';

    protected static string $description = 'Configure your menus';

    protected static ?string $slug = 'settings/menus';


}
