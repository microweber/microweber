<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminMaintenanceModePage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $title = 'System Settings';
    protected static string $description = 'Configure website maintenance mode settings';
    protected static string | \UnitEnum | null $navigationGroup = 'System Settings';
    protected static ?int $navigationSort = 500;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Maintenance Mode Settings')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->description('Configure maintenance mode for your website')
                    ->schema([
                        Toggle::make('options.website.maintenance_mode')
                            ->label('Enable Maintenance Mode')
                            ->helperText(new HtmlString('When enabled, visitors will see a maintenance message instead of your website.'))
                            ->live(),

                        Textarea::make('options.website.maintenance_mode_text')
                            ->label('Maintenance Mode Message')
                            ->helperText(new HtmlString('This message will be displayed to visitors when maintenance mode is enabled.'))
                            ->placeholder('We are currently performing maintenance. Please check back soon.')
                            ->rows(4)
                            ->live(),

                        Toggle::make('options.website.maintenance_mode_admin_access')
                            ->label('Allow Admin Access')
                            ->helperText(new HtmlString('When enabled, administrators can still access the website while in maintenance mode.'))
                            ->live(),

                        Toggle::make('options.website.maintenance_mode_ip_whitelist_enable')
                            ->label('Enable IP Whitelist')
                            ->helperText(new HtmlString('Allow specific IP addresses to access the website during maintenance.'))
                            ->live(),

                        Textarea::make('options.website.maintenance_mode_ip_whitelist')
                            ->label('IP Whitelist')
                            ->helperText(new HtmlString('Enter IP addresses, one per line, that should have access during maintenance mode.'))
                            ->placeholder('127.0.0.1')
                            ->rows(4)
                            ->live()
                    ])
            ]);
    }
}
