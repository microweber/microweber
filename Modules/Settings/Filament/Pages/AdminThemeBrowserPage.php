<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use MicroweberPackages\Template\Adapters\RenderHelpers\TemplateThemeBrowser;

class AdminThemeBrowserPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-theme';
    protected static string $view = 'modules.settings::filament.admin.pages.theme-browser';
    protected static ?string $title = 'Theme Browser';
    protected static string $description = 'Browse and manage website themes';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Available Themes')
                    ->description('Browse and select from available website themes')
                    ->schema([
                        Grid::make()
                            ->schema([
                                // Theme browser will be rendered in the view
                            ])
                    ])
            ]);
    }

    public function getViewData(): array
    {
        $themeBrowser = new TemplateThemeBrowser();
        return [
            'themes' => $themeBrowser->getThemes(),
        ];
    }
}
