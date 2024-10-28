<?php

namespace Modules\HighlightCode\Filament;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class HighlightCodeModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'highlight_code';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Highlight Code')
                            ->schema([

                                Textarea::make('options.text')
                                    ->label('Code')
                                    ->rows(10)
                                    ->placeholder('Paste your code here')
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
