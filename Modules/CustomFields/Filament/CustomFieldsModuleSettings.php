<?php

namespace Modules\CustomFields\Filament;

use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CustomFieldsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'custom_fields';

    public function form(Schema $schema): Schema
    {
        $relId = $this->params['id'] ?? null;

        return $schema
            ->schema([
                Tabs::make('Custom Fields')
                    ->schema([
                        Tabs\Tab::make('Content')
                            ->schema([
                                Section::make('Fields')
                                    ->icon('heroicon-o-rectangle-stack')
                                    ->collapsible()
                                    ->schema(function () use ($relId) {
                                        $customFieldParams = [
                                            'relId' => $relId,
                                            'relType' => 'module',
                                        ];
                                        if ($relId == 0) {
                                            $customFieldParams['createdBy'] = user_id();
                                        }

                                        return [
                                            Livewire::make('admin-list-custom-fields', $customFieldParams)
                                                ->columnSpanFull(),
                                        ];
                                    }),
                            ]),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
