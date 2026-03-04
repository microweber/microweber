<?php

namespace Modules\Tax\Filament\Admin\Resources;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;
use Modules\Tax\Models\TaxType;

class TaxResource extends Resource
{
    protected static ?string $model = TaxType::class;
    protected static string | \BackedEnum | null $navigationIcon = 'mw-taxes';

    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';
    protected static ?string $modelLabel = 'Tax';
    protected static ?int $navigationSort = 7;


    protected static string $description = 'Configure your shop taxes settings';


    public function getDescription(): string
    {

        return static::$description;
    }



    public static function form(Schema $schema): Schema
    {
        return $schema
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
            ->headerActions([
                Action::make('toggle_taxes')
                    ->label(function () {
                        return get_option('enable_taxes', 'shop') == 1 ? 'Disable Taxes' : 'Enable Taxes';
                    })
                    ->icon(function () {
                        return get_option('enable_taxes', 'shop') == 1 ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle';
                    })
                    ->color(function () {
                        return get_option('enable_taxes', 'shop') == 1 ? 'danger' : 'success';
                    })
                    ->requiresConfirmation()
                    ->modalHeading(function () {
                        return get_option('enable_taxes', 'shop') == 1 ? 'Disable Taxes Globally?' : 'Enable Taxes Globally?';
                    })
                    ->modalDescription(function () {
                        return get_option('enable_taxes', 'shop') == 1
                            ? 'This will disable tax calculations for the entire shop during checkout.'
                            : 'This will enable tax calculations for the entire shop during checkout.';
                    })
                    ->action(function () {
                        $currentValue = get_option('enable_taxes', 'shop');
                        $newValue = $currentValue == 1 ? '0' : '1';
                        save_option('enable_taxes', $newValue, 'shop');
                    })
                    ->after(function () {


                    }),
            ])
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

                Tables\Columns\TextColumn::make('global_status')
                    ->label('Global Status')
                    ->getStateUsing(function () {
                        return get_option('enable_taxes', 'shop') == 1 ? 'Enabled' : 'Disabled';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Enabled' => 'success',
                        'Disabled' => 'warning',
                    }),
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
