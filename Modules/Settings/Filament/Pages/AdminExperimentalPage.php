<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminExperimentalPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-settings';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $title = 'Experimental';
    protected static string $description = 'Configure experimental features and settings';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Cache Settings')
                    ->description('Configure caching options for better performance')
                    ->schema([
                        Toggle::make('options.experimental.static_files_delivery')
                            ->label('Static files delivery')
                            ->helperText(new HtmlString('Enable static files delivery for better performance.<br>This is an experimental feature.'))
                            ->live(),

                        Toggle::make('options.experimental.enable_full_page_cache')
                            ->label('Full page cache')
                            ->helperText(new HtmlString('Enable full page caching for better performance.<br>This is an experimental feature.'))
                            ->live(),

                        Toggle::make('options.experimental.css_framework')
                            ->label('CSS Framework')
                            ->helperText(new HtmlString('Enable experimental CSS framework.<br>This is an experimental feature.'))
                            ->live(),

                        Toggle::make('options.experimental.legacy_loader')
                            ->label('Legacy Loader')
                            ->helperText(new HtmlString('Enable legacy loader support.<br>This is an experimental feature.'))
                            ->live(),
                    ]),

                Section::make('Development Settings')
                    ->description('Configure development-related settings')
                    ->schema([
                        Toggle::make('options.experimental.debug_mode')
                            ->label('Debug Mode')
                            ->helperText(new HtmlString('Enable debug mode for development.<br>This is an experimental feature.'))
                            ->live(),

                        Toggle::make('options.experimental.developer_tools')
                            ->label('Developer Tools')
                            ->helperText(new HtmlString('Enable developer tools for debugging.<br>This is an experimental feature.'))
                            ->live(),
                    ]),

                Section::make('UI Settings')
                    ->description('Configure experimental UI features')
                    ->schema([
                        Toggle::make('options.experimental.new_template_picker')
                            ->label('New Template Picker')
                            ->helperText(new HtmlString('Enable new template picker interface.<br>This is an experimental feature.'))
                            ->live(),

                        Toggle::make('options.experimental.live_edit_new_toolbar')
                            ->label('New Live Edit Toolbar')
                            ->helperText(new HtmlString('Enable new live edit toolbar.<br>This is an experimental feature.'))
                            ->live(),
                    ]),
            ]);
    }
}
