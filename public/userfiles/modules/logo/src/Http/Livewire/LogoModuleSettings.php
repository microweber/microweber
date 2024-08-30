<?php

namespace MicroweberPackages\Modules\Logo\Http\Livewire;

use App\Filament\Admin\Resources\ContentResource;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class LogoModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'logo';

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema());
    }


    public function getFormSchema(): array
    {
        return self::formArray();

    }

    public static function formArray()
    {
        return [
            Tabs::make('Options')
                ->tabs([
                    Tabs\Tab::make('Image')
                        ->schema([
                            MwFileUpload::make('options.logoimage')
                                ->live(),
                            TextInput::make('options.size')
                                ->label('Logo Size')
                                ->numeric()
                                ->live()
                        ]),
                    Tabs\Tab::make('Text')
                        ->schema([
                            TextInput::make('options.text')
                                ->label('Logo Text')
                                ->helperText('This logo text will appear when image not applied')
                                ->live(),
                            ColorPicker::make('options.text_color')
                                ->live()
                                ->rgba()
                        ]),
                ]),
        ];
    }





}
