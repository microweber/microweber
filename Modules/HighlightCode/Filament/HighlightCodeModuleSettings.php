<?php

namespace Modules\HighlightCode\Filament;

use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class HighlightCodeModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'highlight_code';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Options')
                    ->schema([
                        Tabs\Tab::make('Highlight Code')
                            ->schema([

                                Textarea::make('options.text')
                                    ->label('Code')
                                    ->rows(10)
                                    ->placeholder('Paste your code here')
                                    ->default(fn () => $this->getOption('text', ''))
                                    ->live(),

                            ]),
                        Tabs\Tab::make('Design')
                            ->schema(
                                $this->getTemplatesFormSchema() ?? [],
                            ),
                    ]),
            ]);
    }
}
