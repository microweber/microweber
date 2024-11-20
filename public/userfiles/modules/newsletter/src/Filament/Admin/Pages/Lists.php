<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;

class Lists extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'newsletter/lists';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.sender-accounts';

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
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

}
