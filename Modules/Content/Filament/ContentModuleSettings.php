<?php

namespace Modules\Content\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Content\Concerns\HasContentFilterModuleSettings;
use Modules\Content\Models\Content;
use Modules\Product\Models\Product;

class ContentModuleSettings extends LiveEditModuleSettings
{
    use HasContentFilterModuleSettings;


    public string $module = 'content';
    public string $contentModelClass = Content::class;
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
                                        'contentModel' => $this->contentModelClass,
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
    }}
