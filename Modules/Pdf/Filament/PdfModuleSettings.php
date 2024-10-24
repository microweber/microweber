<?php

namespace Modules\Pdf\Filament;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class PdfModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'pdf';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('PDF settings')->schema([
                    ToggleButtons::make('options.data-pdf-source')
                        ->live()
                        ->inline()
                        ->default('file')
                        ->columnSpanFull()
                        ->options([
                            'file' => 'File',
                            'url' => 'URL',
                        ]),

                    MwFileUpload::make('options.data-pdf-upload')
                        ->hidden(function ($get) {
                            return $get('options.data-pdf-source') === 'url';
                        })
                        ->label('Upload PDF file')
                        ->fileTypes(['pdf'])
                        ->live(),

                    TextInput::make('options.data-pdf-url')
                        ->hidden(function ($get) {
                            return $get('options.data-pdf-source') === 'file';
                        })
                        ->label('PDF file URL')
                        ->url()
                        ->live()
                        ->placeholder('https://www.example.com/document.pdf'),
                ])
            ]);
    }
}
