<?php

namespace Modules\ContactForm\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ContactFormModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'contact_form';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Contact Form')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([


                                \LaraZeus\Accordion\Forms\Accordions::make('Options')
                                    ->slideOverRight()
                                    ->activeAccordion(0)
                                    ->accordions([
                                        \LaraZeus\Accordion\Forms\Accordion::make('main-data')
                                            ->columns()
                                            ->label('User Details')
                                            ->icon('heroicon-o-user')
                                            ->badge('New Badge')
                                            ->badgeColor('info')
                                            ->schema([
                                                TextInput::make('name')->required(),
                                                TextInput::make('email')->required(),
                                            ]),
                                        \LaraZeus\Accordion\Forms\Accordion::make('settings')
                                            ->columns()
                                            ->label('Settings')
                                            ->schema([
                                                TextInput::make('name')->required(),
                                                TextInput::make('email')->required(),
                                            ]),
                                        \LaraZeus\Accordion\Forms\Accordion::make('next')
                                            ->columns()
                                            ->label('Whats next?')
                                            ->schema([
                                                TextInput::make('name')->required(),
                                                TextInput::make('email')->required(),
                                            ]),
                                    ]),


                            ]),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }

}
