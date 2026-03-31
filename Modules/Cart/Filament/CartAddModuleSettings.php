<?php

namespace Modules\Cart\Filament;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CartAddModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'shop/cart_add';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Tabs::make('Cart Add Settings')
                ->schema([
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
