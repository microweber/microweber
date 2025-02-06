<?php

namespace Modules\Faq\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettingsTable;
use Modules\Faq\Models\Faq;

class FaqTableList extends LiveEditModuleSettingsTable
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Faq::query()
                    ->where('rel_type', $this->rel_type)
                    ->where('rel_id', $this->rel_id)
            )
            ->columns([
                TextColumn::make('question')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('answer')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('position')
                    ->sortable(),
            ])
            ->defaultSort('position', 'asc')
            ->reorderable('position')
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('question')
                            ->required(),
                        Textarea::make('answer')
                            ->required()
                            ->rows(4),
                        TextInput::make('position')
                            ->numeric()
                            ->default(0),
                    ]),
                DeleteAction::make(),
            ]);
    }


}
