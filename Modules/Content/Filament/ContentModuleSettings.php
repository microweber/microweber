<?php

namespace Modules\Content\Filament;

use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Content\Concerns\HasContentFilterModuleSettings;
use Modules\Content\Models\Content;

class ContentModuleSettings extends LiveEditModuleSettings
{
    use HasContentFilterModuleSettings;


    public string $module = 'content';
    public string $contentModelClass = Content::class;
    protected static bool $useMwDialog = true;
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Content Settings')
                    ->schema([
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
