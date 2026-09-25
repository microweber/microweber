<?php

namespace Modules\Pdf\Filament;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class PdfModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'pdf';

    // Filament derives the page title from the class name (PdfModuleSettings →
    // "Pdf Module Settings"); override so the PDF acronym is correctly cased.
    protected static ?string $title = 'PDF Module Settings';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('PDF')
                    ->schema([
                        Tabs\Tab::make('Content')
                            ->schema([
                                Section::make('PDF settings')->schema([
                                    ToggleButtons::make('options.data-pdf-source')
                                        ->label('PDF source')
                                        ->live()
                                        ->inline()
                                        ->default(fn () => $this->getOption('data-pdf-source', 'file'))
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
                                        ->acceptExtensions(['pdf'])
                                        ->helperText('Only PDF files are allowed.')
                                        ->live()
                                        ->default(fn () => $this->getOption('data-pdf-upload', '')),

                                    TextInput::make('options.data-pdf-url')
                                        ->hidden(function ($get) {
                                            return $get('options.data-pdf-source') === 'file';
                                        })
                                        ->label('PDF file URL')
                                        ->url()
                                        ->live()
                                        ->default(fn () => $this->getOption('data-pdf-url', ''))
                                        ->placeholder('https://www.example.com/document.pdf'),
                                ]),
                            ]),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
