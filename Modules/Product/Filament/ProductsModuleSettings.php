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
use Modules\Content\Filament\ContentModuleSettings;
use Modules\Content\Filament\ContentTableList;
use Modules\Product\Models\Product;

class ProductsModuleSettings extends ContentModuleSettings
{

    public string $module = 'shop/products';
    public string $contentModelClass = Product::class;

}
