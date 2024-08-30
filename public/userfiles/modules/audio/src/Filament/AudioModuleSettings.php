<?php

namespace MicroweberPackages\Modules\Audio\Filament;

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

class AudioModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'audio';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Section::make('Audio settings')->schema([
                    MwFileUpload::make('options.data-audio-upload')
                        ->label('Upload audio file')
                        ->fileTypes('audio')
                        ->helperText('Select an logo for your website.')
                        ->live(),

                    TextInput::make('options.data-audio-url')
                        ->label('Audio file URL')
                        ->url()
                        ->live()
                        ->placeholder('https://www.example.com/audio.mp3'),
                ])
            ]);
    }


}
