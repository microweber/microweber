<?php

namespace MicroweberPackages\User\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BooleanColumn;
use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\User\Filament\Resources\UserResource\Pages\CreateUser;
use MicroweberPackages\User\Filament\Resources\UserResource\Pages\EditUser;
use MicroweberPackages\User\Filament\Resources\UserResource\Pages\ListUsers;
use MicroweberPackages\User\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationGroup = 'Users';

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';


    public static function form(Form $form): Form
    {
        $rows = [
            Forms\Components\Card::make([
                TextInput::make('first_name')->required(),
                TextInput::make('last_name')->required(),
            ])->columnSpanFull()->columns(2),

            Forms\Components\Card::make([
            TextInput::make('username')->required(),
            TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(static function ($state) use ($form){
                    if(!empty($state)){
                        return Hash::make($state);
                    }

                    $user = User::find($form->getColumns());
                    if($user){
                        return $user->password;
                    }
                }),
            ])->columnSpanFull()->columns(2),
        ];

        $form->schema($rows);

        return $form;
    }

    public static function table(Table $table): Table
    {
        $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')->url(fn ($record) => $record->avatarUrl()),
                TextColumn::make('id')->sortable(),
                TextColumn::make('username')->sortable()->searchable(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                BooleanColumn::make('is_active')->sortable(),
                TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M j, Y')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('M j, Y')->sortable(),

            ]);

//            ->filters([
//
//                Tables\Filters\Filter::make('verified')
//                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
//                Tables\Filters\Filter::make('unverified')
//                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),
//            ]);


        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
