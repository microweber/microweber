<?php

namespace Modules\Newsletter\Filament\Admin\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Modules\Newsletter\Models\NewsletterList;

class Lists extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'newsletter/lists';

    protected string $view = 'microweber-module-newsletter::livewire.filament.admin.sender-accounts';

    public static function table(Table $table): Table
    {

        $editForm = [
            TextInput::make('name')
                ->label('Name')
                ->placeholder('Enter name'),
        ];

        return $table
            ->heading('List')
            ->query(NewsletterList::query())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('subscribers'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add List')
                    ->form($editForm),
            ])
            ->actions([
                EditAction::make()->form($editForm),
                DeleteAction::make(),
            ])
            ->bulkActions([
//                \Filament\Actions\BulkActionGroup::make([
//                    \Filament\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

}
