<?php

namespace MicroweberPackages\Modules\HighlightCode\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class HighlightCodeModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'highlight_code';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Section::make('Highlight Code')->schema([

                    Textarea::make('options.text')
                        ->label('Code')
                        ->rows(10)
                        ->placeholder('Paste your code here')
                        ->live(),
                ])
            ]);
    }


}
