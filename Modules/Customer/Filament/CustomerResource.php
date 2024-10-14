<?php

namespace Modules\Customer\Filament;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Modules\Customer\Filament\CustomerResource\Pages\ManageCustomers;
use Modules\Customer\Models\Customer;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')

                    ->maxLength(255),
                Forms\Components\TextInput::make('email')

                    ->email()
                    ->maxLength(255),
                Forms\Components\Toggle::make('active')
                    ->required(),
                Forms\Components\TextInput::make('user_id')

                    ->numeric(),
                Forms\Components\TextInput::make('currency_id')

                    ->numeric(),
                Forms\Components\TextInput::make('company_id')

                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('first_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\BooleanColumn::make('active')->sortable(),
                Tables\Columns\TextColumn::make('user_id')->sortable(),
                Tables\Columns\TextColumn::make('currency_id')->sortable(),
                Tables\Columns\TextColumn::make('company_id')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn(Builder $query): Builder => $query->where('active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCustomers::route('/'),
        ];
    }
}
