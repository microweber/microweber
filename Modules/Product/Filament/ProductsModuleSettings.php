<?php

namespace Modules\Product\Filament;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Content\Concerns\HasContentFilterModuleSettings;

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
                        Tabs\Tab::make('Main settings')
                            ->schema($this->getContentFilterModuleSettingsSchema()),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
