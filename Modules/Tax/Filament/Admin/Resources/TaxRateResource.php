<?php

namespace Modules\Tax\Filament\Admin\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Modules\Country\Models\Country;
use Modules\Tax\Models\TaxRate;

use MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGloballySearchable;
class TaxRateResource extends Resource
{
    protected static ?string $model = TaxRate::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-receipt-percent';
    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';
    protected static string $description = 'Manage tax rates by region';
    protected static ?string $modelLabel = 'Tax Rate';

    public static function getDescription(): string
    {
        return static::$description;
    }
    protected static ?string $navigationLabel = 'Tax Rates';
    protected static ?int $navigationSort = 8;
    use MicroweberGloballySearchable;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description', 'country_code'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->rate) {
            $details['Rate'] = $record->rate . '%';
        }

        if ($record->country_code) {
            $details['Country'] = $record->country_code;
        }

        if ($record->type) {
            $details['Type'] = ucfirst($record->type);
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Basic Information')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tax Name')
                            ->placeholder('e.g., VAT, Sales Tax, GST')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['lg' => 2]),

                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Description of this tax rate')
                            ->rows(2)
                            ->columnSpan(['lg' => 2]),

                        Select::make('type')
                            ->label('Tax Type')
                            ->options([
                                'percentage' => 'Percentage (%)',
                                'fixed' => 'Fixed Amount',
                            ])
                            ->default('percentage')
                            ->required()
                            ->live(),

                        TextInput::make('rate')
                            ->label('Rate')
                            ->placeholder('e.g., 20 for 20%')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->suffix(function (Get $get) {
                                return $get('type') === 'percentage' ? '%' : null;
                            }),
                    ])
                    ->columns(['lg' => 2]),

                Section::make('Location Rules')
                    ->icon('heroicon-m-map-pin')
                    ->description('Define where this tax rate applies')
                    ->schema([
                        Select::make('country_code')
                            ->label('Country')
                            ->options(function () {
                                return Country::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'code')
                                    ->toArray();
                            })
                            ->searchable()
                            ->placeholder('All Countries')
                            ->nullable()
                            ->helperText('Leave empty to apply to all countries'),

                        TextInput::make('state_code')
                            ->label('State / Province')
                            ->placeholder('e.g., CA, TX, NY')
                            ->maxLength(10)
                            ->nullable()
                            ->helperText('Leave empty to apply to all states'),

                        TextInput::make('city')
                            ->label('City')
                            ->placeholder('e.g., New York')
                            ->maxLength(255)
                            ->nullable()
                            ->helperText('Leave empty to apply to all cities'),

                        TextInput::make('zip_code_pattern')
                            ->label('ZIP Code Pattern')
                            ->placeholder('e.g., 100* or 90210')
                            ->maxLength(20)
                            ->nullable()
                            ->helperText('Use * as wildcard. Leave empty for all ZIP codes'),
                    ])
                    ->columns(['lg' => 2]),

                Section::make('Configuration')
                    ->icon('heroicon-m-cog-6-tooth')
                    ->schema([
                        TextInput::make('priority')
                            ->label('Priority')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Higher priority tax rates are applied first'),

                        Toggle::make('compound_tax')
                            ->label('Compound Tax')
                            ->helperText('When enabled, this tax is calculated on the subtotal plus previous taxes'),

                        Toggle::make('is_default')
                            ->label('Default Tax Rate')
                            ->helperText('Use as fallback when no specific tax rate matches'),
                    ])
                    ->columns(['lg' => 3]),

                Section::make('Validity Period')
                    ->icon('heroicon-m-calendar-days')
                    ->schema([
                        DateTimePicker::make('valid_from')
                            ->label('Valid From')
                            ->nullable()
                            ->helperText('Leave empty for immediate effect'),

                        DateTimePicker::make('valid_until')
                            ->label('Valid Until')
                            ->nullable()
                            ->helperText('Leave empty for indefinite validity'),
                    ])
                    ->columns(['lg' => 2]),

                Section::make('Tax Preview')
                    ->icon('heroicon-m-eye')
                    ->schema([
                        Placeholder::make('tax_preview')
                            ->label('')
                            ->content(function (Get $get) {
                                $rate = $get('rate') ?? 0;
                                $type = $get('type');
                                $country = $get('country_code') ?? 'All Countries';
                                $state = $get('state_code') ?? 'All States';

                                if ($type === 'percentage' && $rate) {
                                    $example100 = number_format($rate, 2) . '%';
                                    $exampleTax = number_format($rate / 100 * 100, 2);
                                } elseif ($type === 'fixed' && $rate) {
                                    $example100 = currency_format($rate);
                                    $exampleTax = currency_format($rate);
                                } else {
                                    $example100 = '0%';
                                    $exampleTax = '$0.00';
                                }

                                return new HtmlString('<div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border"><h4 class="font-semibold mb-2 text-gray-900 dark:text-gray-100">Tax Rate Preview</h4><div class="space-y-1 text-sm text-gray-600 dark:text-gray-400"><p><strong>Rate:</strong> ' . $example100 . '</p><p><strong>Example Tax on $100:</strong> ' . $exampleTax . '</p><p><strong>Location:</strong> ' . htmlspecialchars($country) . ', ' . htmlspecialchars($state) . '</p></div></div>');
                            }),
                    ]),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
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

                Tables\Columns\TextColumn::make('formatted_rate')
                    ->label('Rate')
                    ->sortable('rate'),

                Tables\Columns\TextColumn::make('location_description')
                    ->label('Location')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->location_description;
                    }),

                Tables\Columns\IconColumn::make('compound_tax')
                    ->label('Compound')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Valid Until')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),

                Tables\Filters\SelectFilter::make('country_code')
                    ->label('Country')
                    ->options(function () {
                        return Country::query()
                            ->orderBy('name')
                            ->pluck('name', 'code')
                            ->toArray();
                    })
                    ->searchable(),

                TernaryFilter::make('compound_tax')
                    ->label('Compound Tax')
                    ->placeholder('All')
                    ->trueLabel('Compound Only')
                    ->falseLabel('Non-Compound Only'),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('priority', 'desc')
            ->emptyStateHeading('No tax rates yet')
            ->emptyStateDescription('Create your first tax rate to start collecting taxes on orders.')
            ->emptyStateIcon('heroicon-o-receipt-percent');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\ListTaxRates::route('/'),
            'create' => \Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\CreateTaxRate::route('/create'),
            'edit' => \Modules\Tax\Filament\Admin\Resources\TaxRateResource\Pages\EditTaxRate::route('/{record}/edit'),
        ];
    }
}
