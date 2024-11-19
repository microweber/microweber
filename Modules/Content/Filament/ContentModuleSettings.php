<?php

namespace Modules\Content\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ContentModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'content';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Content')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(ContentTableList::class, [
                                    'params' => $this->params ?? [],
                                    'moduleId' => $this->params['id'] ?? null,
                                ])
                            ]),
                        Tabs\Tab::make('Content list settings')
                            ->schema([
                                TextInput::make('options.parent_id')
                                    ->label('Parent ID')
                                    ->numeric()
                                    ->live(),

                                Select::make('options.content_type')
                                    ->label('Content Type')
                                    ->options([
                                        'page' => 'Page',
                                        'post' => 'Post',
                                        'product' => 'Product',
                                    ])
                                    ->live(),


                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
