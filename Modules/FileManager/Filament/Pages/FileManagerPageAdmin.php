<?php

namespace Modules\FileManager\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class FileManagerPageAdmin extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-folder';

    protected string $view = 'modules.settings::filament.admin.pages.settings-filebrowser';

    protected static ?string $title = 'Files';

    protected static string $description = 'Configure your file settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Website Settings';


}
