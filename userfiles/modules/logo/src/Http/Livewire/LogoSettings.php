<?php

namespace MicroweberPackages\Modules\Logo\Http\Livewire;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LogoSettings extends Page
{
    protected static bool $showTopBar = false;
    protected static bool $shouldRegisterNavigation = false;

    public static function showTopBar(): bool
    {
        return self::$showTopBar;
    }

    public function getLayout(): string
    {
        return static::$layout ?? 'filament-panels::components.layout.live-edit';
    }

    protected static string $view = 'microweber-module-logo::livewire.settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([
                                FileUpload::make('options.logo.attachment'),
                                TextInput::make('options.logo.title')
                            ]),
                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make('options.logo.text')
                                    ->label('Logo Text')
                                    ->helperText('This logo text will appear when image not applied'),
                                ColorPicker::make('options.logo.text_color')
                                    ->rgba()
                            ]),
                    ]),

            ]);
    }

}
