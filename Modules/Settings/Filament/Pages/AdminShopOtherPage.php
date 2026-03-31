<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Schemas\Schema;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopOtherPage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Other';

    protected static string $description = 'Configure your shop other settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }
}
