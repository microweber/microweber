<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminMaintenanceModePage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-settings';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $title = 'Maintenance Mode';
    protected static string $description = 'Configure website maintenance mode settings';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Maintenance Mode Settings')
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
