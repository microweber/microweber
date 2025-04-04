<?php

namespace Modules\Tax\Filament\Admin\Resources;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Modules\Tax\Models\TaxType;

class TaxResource extends Resource
{
    protected static ?string $model = TaxType::class;

    protected static ?string $navigationGroup = 'Shop Settings';
    protected static ?string $modelLabel = 'Tax';
    protected static ?int $navigationSort = 7;


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
                    ->live()
                    ->numeric()
                    ->columnSpan('full'),

                TextInput::make('description')
                    ->label('Description')
                    ->placeholder('Description')
                    ->columnSpan('full'),

                Placeholder::make('example_display')
                    ->label('Tax Display')
                    ->columnSpan('full')
                    ->live()
                    ->content(function (Set $set, Get $get) {
                        $exampleTaxFor100Dollars = 0;

                        $taxType = $get('type');
                        if ($taxType == 'percentage') {
                            $exampleTaxFor100Dollars = $get('rate') / 100 * 100;
                        } elseif ($taxType == 'fixed') {
                            $exampleTaxFor100Dollars = $get('rate') ;
                        }


                        return new HtmlString("
            <div class='bg-gray-100 p-4 rounded-lg'>
                <div class='mt-2'>
                    <div class='text-sm'>For \$100, the tax will be: <span class='font-semibold'>\$$exampleTaxFor100Dollars</span></div>
                </div>
            </div>
");
                    }),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyState(function (Table $table) {
                $modelName = static::$model;
                return view('modules.content::filament.admin.empty-state', ['modelName' => $modelName]);

            })
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

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => \Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\ListTaxes::route('/'),
            'create' => \Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\CreateTax::route('/create'),
            'edit' => \Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\EditTax::route('/{record}/edit'),
        ];
    }
}
