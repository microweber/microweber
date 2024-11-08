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
        dump($this->params);
        return $form
//            ->model()
            ->schema([

                Tabs::make('Contact Form')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([

                                \LaraZeus\Accordion\Forms\Accordions::make('Options')
                                    ->slideOverRight()
                                    ->activeAccordion(0)
                                    ->accordions([
                                        \LaraZeus\Accordion\Forms\Accordion::make('contact_settings')
                                            ->columns()
                                            ->label('Contact Settings')
                                            ->schema([
                                                TextInput::make('name')->required(),
                                            ]),

                                        \LaraZeus\Accordion\Forms\Accordion::make('from_fields')
                                            ->columns()
                                            ->label('From Fields')
                                            ->schema(function() {

                                                $relId = 0;
                                                $customFieldParams = [
                                                    'relId'   => $relId,
                                                    'relType' => 'contact_form'//morph_name(),
                                                ];

                                                if ($relId == 0) {
                                                    $customFieldParams['createdBy'] = user_id();
                                                }

                                                $components = [];
                                                $components[] = Livewire::make('admin-list-custom-fields', $customFieldParams)->columnSpanFull();

                                                return $components;
                                            }),
                                        \LaraZeus\Accordion\Forms\Accordion::make('auto_respond_settings')
                                            ->columns()
                                            ->label('Auto Respond Settings')
                                            ->schema([
                                                TextInput::make('name')->required(),
                                                TextInput::make('email')->required(),
                                            ]),
                                        \LaraZeus\Accordion\Forms\Accordion::make('receivers')
                                            ->columns()
                                            ->label('Receivers')
                                            ->schema([
                                                TextInput::make('name')->required(),
                                                TextInput::make('email')->required(),
                                            ]),
                                        \LaraZeus\Accordion\Forms\Accordion::make('advanced')
                                            ->columns()
                                            ->label('Advanced')
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
