<?php

namespace Modules\FileManager\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class FileManagerPageAdmin extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'mw-files';

    protected string $view = 'modules.settings::filament.admin.pages.settings-filebrowser';

    protected static ?string $title = 'Files';

    protected static string $description = 'Configure your file settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Other';


}
