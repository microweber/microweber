<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminUpdatesPage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'mw-updates';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Updates';

    protected static string $description = 'Check for the latest updates';
}
