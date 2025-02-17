<?php

namespace MicroweberPackages\User\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use MicroweberPackages\User\Filament\Resources\UsersResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\User\Models\User;

class UsersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'mw-users';

    protected static ?string $navigationGroup = 'Users';

    protected static ?int $navigationSort = 98;

    public static function form(Form $form): Form
    {
        $rows = [

            TextInput::make('first_name'),
            TextInput::make('last_name'),


            TextInput::make('username')->required()->unique(
                ignoreRecord: true,
            ),
            TextInput::make('email')->email()->required()->unique(
                ignoreRecord: true,
            ),
            TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(static function ($state) use ($form) {
                    if (!empty($state)) {
                        return Hash::make($state);
                    }

                    $user = User::find($form->getColumns());
                    if ($user) {
                        return $user->password;
                    }
                }),
            TextInput::make('password_confirmation')
                ->password()
                ->required(),

            Select::make('is_admin')
                ->label('Is Admin')
                ->options([
                    '0' => 'No',
                    '1' => 'Yes',
                ]),

            Select::make('is_active')
                ->label('Is Active')
                ->options([
                    '0' => 'No',
                    '1' => 'Yes',
                ]),



        ];

        $form->schema($rows);

        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('username'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('created_at'),

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
            'index' => \MicroweberPackages\User\Filament\Resources\UsersResource\Pages\ListUsers::route('/'),
//            'create' => \MicroweberPackages\User\Filament\Resources\UsersResource\Pages\CreateUsers::route('/create'),
//            'edit' => \MicroweberPackages\User\Filament\Resources\UsersResource\Pages\EditUsers::route('/{record}/edit'),
        ];
    }
}
