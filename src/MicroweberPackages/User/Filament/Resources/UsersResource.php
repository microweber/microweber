<?php

namespace MicroweberPackages\User\Filament\Resources;

use MicroweberPackages\User\Filament\Resources\UsersResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\User\Models\User;

class UsersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
//            'index' => \MicroweberPackages\User\Filament\Resources\UsersResource\Pages\ListUsers::route('/'),
//            'create' => \MicroweberPackages\User\Filament\Resources\UsersResource\Pages\CreateUsers::route('/create'),
//            'edit' => \MicroweberPackages\User\Filament\Resources\UsersResource\Pages\EditUsers::route('/{record}/edit'),
        ];
    }
}
