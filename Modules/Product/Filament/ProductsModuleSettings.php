<?php

namespace Modules\Product\Filament;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Content\Concerns\HasContentFilterModuleSettings;
use Modules\Content\Filament\ContentTableList;
use Modules\Product\Models\Product;

class ProductsModuleSettings extends LiveEditModuleSettings
{

    use HasContentFilterModuleSettings;

    public string $module = 'shop/products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Product Settings')
                    ->tabs([
                        Tabs\Tab::make('Items list')
                            ->schema(
                                [
                                    Livewire::make(ContentTableList::class, [
                                        'params' => $this->params ?? [],
                                        'contentModel' => Product::class,
                                        'moduleId' => $this->params['id'] ?? null,
                                    ])
                                ]
                            ),

                        Tabs\Tab::make('Settings')
                            ->schema($this->getContentFilterModuleSettingsSchema()),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
