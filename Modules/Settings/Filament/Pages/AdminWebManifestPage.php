<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;

class AdminWebManifestPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-settings';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $title = 'Web Manifest';
    protected static string $description = 'Configure your web app manifest settings';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Web App Icons')
                    ->description('Configure icons for your Progressive Web App (PWA)')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                MwFileUpload::make('options.website.manifest_app_icon')
                                    ->label('App Icon (144x144)')
                                    ->helperText(new HtmlString('Select a PNG image for your website app icon.<br>Must be exactly 144x144 pixels.'))
                                    ->live()
                                    ->required(),

                                MwFileUpload::make('options.website.maskable_icon')
                                    ->label('Maskable Icon (512x512)')
                                    ->helperText(new HtmlString('Select a PNG image for your website maskable icon.<br>Must be exactly 512x512 pixels.'))
                                    ->live()
                                    ->required(),
                            ])
                    ])
            ]);
    }
}
