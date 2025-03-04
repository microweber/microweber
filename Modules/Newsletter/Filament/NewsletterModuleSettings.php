<?php

namespace Modules\Newsletter\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class NewsletterModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'newsletter';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Newsletter settings')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                TextInput::make('options.title')
                                    ->live()
                                    ->label('Title'),

                                TextInput::make('options.description')
                                    ->live()
                                    ->label('Description'),

                                Toggle::make('options.require_terms')
                                    ->live()
                                    ->label('Require terms')
                                    ->default(false),

                                Select::make('options.list_id')
                                    ->live()
                                    ->label('Newsletter List')
                                    ->options(function () {
                                        return \Modules\Newsletter\Models\NewsletterList::whereNotNull('name')->pluck('name', 'id')->toArray();
                                    })->visible(function () {
                                        return \Modules\Newsletter\Models\NewsletterList::count() > 0;
                                    }),

                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
