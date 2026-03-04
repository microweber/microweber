<?php

namespace Modules\Cart\Filament;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Tabs;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CartAddModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'shop/cart_add';

    public function form(Schema $schema): Schema
    {
        return $form->schema([
            Tabs::make('Cart Add Settings')
                ->tabs([
                    Tabs\Tab::make('Settings')
                        ->schema([
                            TextInput::make('options.button_text')
                                ->label('Button Text')
                                ->live()
                                ->placeholder('Add to cart')
                                ->helperText('Custom text for the add to cart button'),


                        ]),





                    // Design Tab
                    Tabs\Tab::make('Design')
                        ->schema([
                            // Add template settings
                            Section::make('Design Settings')->schema(
                                $this->getTemplatesFormSchema()
                            ),

                        ])


                ]),
        ]);
    }
}
