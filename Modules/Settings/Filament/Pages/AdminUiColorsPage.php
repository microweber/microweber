<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminUiColorsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-settings';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $title = 'UI Colors';
    protected static string $description = 'Configure admin interface colors';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Admin Interface Colors')
                    ->description('Customize the colors of your admin interface')
                    ->schema([
                        ColorPicker::make('options.admin_theme.primary_color')
                            ->label('Primary Color')
                            ->helperText(new HtmlString('The main color used throughout the admin interface.'))
                            ->live(),

                        ColorPicker::make('options.admin_theme.secondary_color')
                            ->label('Secondary Color')
                            ->helperText(new HtmlString('The secondary color used for accents and highlights.'))
                            ->live(),

                        ColorPicker::make('options.admin_theme.background_color')
                            ->label('Background Color')
                            ->helperText(new HtmlString('The main background color of the admin interface.'))
                            ->live(),

                        ColorPicker::make('options.admin_theme.text_color')
                            ->label('Text Color')
                            ->helperText(new HtmlString('The main text color used in the admin interface.'))
                            ->live(),
                    ]),

                Section::make('Button Colors')
                    ->description('Customize the colors of buttons in the admin interface')
                    ->schema([
                        ColorPicker::make('options.admin_theme.button_primary_color')
                            ->label('Primary Button Color')
                            ->helperText(new HtmlString('The color used for primary action buttons.'))
                            ->live(),

                        ColorPicker::make('options.admin_theme.button_secondary_color')
                            ->label('Secondary Button Color')
                            ->helperText(new HtmlString('The color used for secondary action buttons.'))
                            ->live(),

                        ColorPicker::make('options.admin_theme.button_text_color')
                            ->label('Button Text Color')
                            ->helperText(new HtmlString('The text color used on buttons.'))
                            ->live(),
                    ]),

                Section::make('Navigation Colors')
                    ->description('Customize the colors of the navigation menu')
                    ->schema([
                        ColorPicker::make('options.admin_theme.nav_background_color')
                            ->label('Navigation Background Color')
                            ->helperText(new HtmlString('The background color of the navigation menu.'))
                            ->live(),

                        ColorPicker::make('options.admin_theme.nav_text_color')
                            ->label('Navigation Text Color')
                            ->helperText(new HtmlString('The text color used in the navigation menu.'))
                            ->live(),

                        ColorPicker::make('options.admin_theme.nav_active_color')
                            ->label('Navigation Active Color')
                            ->helperText(new HtmlString('The color used for active navigation items.'))
                            ->live(),
                    ])
            ]);
    }
}
