<?php

namespace MicroweberPackages\Module\Filament\Resources;


use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Module\Module;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageUrlColumn::make('icon')->imageUrl(function ($record) {
                    return $record->icon();
                }),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                //Tables\Columns\TextColumn::make('module'),

                Tables\Columns\IconColumn::make('installed')
                    ->boolean()
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('categories'),

            ])
            ->filters([
                //
            ])
            ->actions([
               // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \MicroweberPackages\Module\Filament\Resources\ModuleResource\Pages\ListModules::route('/'),
          //  'create' => Pages\CreateModule::route('/create'),
            //'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }
}
