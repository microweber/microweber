<?php

namespace Modules\Product\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ProductsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'product';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Product Settings')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                TextInput::make('options.product_name')
                                    ->label('Product Name')
                                    ->required(),
                                TextInput::make('options.product_price')
                                    ->label('Product Price')
                                    ->numeric()
                                    ->required(),
                                Select::make('options.product_category')
                                    ->label('Product Category')
                                    ->options([
                                        'electronics' => 'Electronics',
                                        'clothing' => 'Clothing',
                                        'accessories' => 'Accessories',
                                    ])
                                    ->required(),
                            ]),
                    ]),
            ]);
    }
}
