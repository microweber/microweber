<?php

namespace Modules\ContactForm\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
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



                            ]),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }

}
