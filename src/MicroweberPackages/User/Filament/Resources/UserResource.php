<?php

namespace MicroweberPackages\User\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;
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
                    ->dehydrateStateUsing(static function ($state) use ($form) {
                        if (!empty($state)) {
                            return Hash::make($state);
                        }

                        $user = User::find($form->getColumns());
                        if ($user) {
                            return $user->password;
                        }
                    }),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->required(),

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

                Tables\Columns\Layout\Split::make([

                    Tables\Columns\ImageColumn::make('avatar')
                        ->circular()
                        ->size('60px')
                        ->url(fn($record) => $record->avatarUrl())
                        ->grow(false),

                    TextColumn::make('id')
                        ->sortable()->grow(false),

                    Tables\Columns\Layout\Stack::make([

                        TextColumn::make('full_name')
                            ->weight('bold')
                            ->label('Full Name')
                            ->limit(20)
                            ->searchable(['first_name', 'last_name']),

                        TextColumn::make('roleName')->weight('bold'),
                    ]),


                    TextColumn::make('username')
                        ->description('Username', 'above')
                        ->limit(30)
                        ->sortable()
                        ->searchable(),

                    TextColumn::make('email')
                        ->description('Email', 'above')
                        ->limit(30)
                        ->sortable()
                        ->searchable(),

                    IconColumn::make('is_active')
                        ->boolean()->sortable()->grow(false),

                ]),

                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('last_login')
                            ->description('Last Login', 'above')
                            ->sortable(),
                        Tables\Columns\TextColumn::make('created_at')
                            ->description('Created At', 'above')
                            ->dateTime('M j, Y')->sortable(),
                        Tables\Columns\TextColumn::make('updated_at')
                            ->description('Updated At', 'above')
                            ->dateTime('M j, Y')->sortable(),

                    ])
                ])->collapsible(),


            ])
            ->actions([
                Tables\Actions\Action::make('Edit')
                    ->icon('heroicon-o-pencil')
                    ->button()
                    ->action(function ($record) {
                        return redirect(admin_url('module/view?type=users/edit-user:' . $record->id));
                    }),
            ])
            ->bulkActions([

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_admin')
                    ->label('Users Type')
                    ->options(
                        [
                            '0' => 'Users',
                            '1' => 'Admins',
                        ]
                    ),

                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Users Status')
                    ->options(
                        [
                            '1' => 'Active',
                            '0' => 'Inactive',
                        ]
                    ),

            ]);


        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            //'create' => CreateUser::route('/create'),
            //'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
