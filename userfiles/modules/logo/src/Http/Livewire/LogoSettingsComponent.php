<?php

namespace MicroweberPackages\Modules\Logo\Http\Livewire;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LogoSettingsComponent extends Page
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
                TextInput::make('logo'),
                Textarea::make('description'),
            ]);
    }

}
