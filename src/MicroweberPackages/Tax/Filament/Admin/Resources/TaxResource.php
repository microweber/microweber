<?php

namespace MicroweberPackages\Tax\Filament\Admin\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\Tax\Models\TaxType;

class TaxResource extends Resource
{
    protected static ?string $model = TaxType::class;

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Name')
                    ->required()
                    ->columnSpan('full'),

                Select::make('type')
                    ->label('Type')
                    ->live()
                    ->reactive()
                    ->placeholder('Select Type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed',
                    ])
                    ->required()
                    ->columnSpan('full'),

                TextInput::make('rate')
                    ->label('Rate')
                    ->placeholder('Rate')
                    ->required()
                    ->numeric()
                    ->columnSpan('full'),

                TextInput::make('description')
                    ->label('Description')
                    ->placeholder('Description')
                    ->columnSpan('full'),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rate')
                    ->label('Rate')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable(),
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
            'index' => \MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource\Pages\ListTaxes::route('/'),
            'create' => \MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource\Pages\CreateTax::route('/create'),
            'edit' => \MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource\Pages\EditTax::route('/{record}/edit'),
        ];
    }
}
