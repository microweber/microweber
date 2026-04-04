<?php

namespace Modules\Currency\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Currency\Models\Currency;
use Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $navigationIcon = null;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Currency Information')
                    ->description('Basic currency details and formatting')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('US Dollar')
                            ->hint('Full name of the currency'),

                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(3)
                            ->minLength(3)
                            ->placeholder('USD')
                            ->hint('ISO 4217 currency code (3 letters)')
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state)),

                        Forms\Components\TextInput::make('symbol')
                            ->required()
                            ->maxLength(10)
                            ->placeholder('$')
                            ->hint('Currency symbol'),

                        Forms\Components\TextInput::make('position')
                            ->numeric()
                            ->default(0)
                            ->hint('Display order (lower = first)'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Formatting Options')
                    ->description('Configure how amounts are displayed')
                    ->schema([
                        Forms\Components\TextInput::make('precision')
                            ->required()
                            ->numeric()
                            ->default(2)
                            ->minValue(0)
                            ->maxValue(8)
                            ->hint('Number of decimal places'),

                        Forms\Components\TextInput::make('thousand_separator')
                            ->required()
                            ->maxLength(1)
                            ->default(',')
                            ->hint('Character used to separate thousands'),

                        Forms\Components\TextInput::make('decimal_separator')
                            ->required()
                            ->maxLength(1)
                            ->default('.')
                            ->hint('Character used for decimal point'),

                        Forms\Components\Toggle::make('swap_currency_symbol')
                            ->label('Symbol After Amount')
                            ->hint('Place symbol after the amount (e.g., 100 $ instead of $ 100)'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->hint('Inactive currencies won\'t be available for selection'),

                        Forms\Components\Toggle::make('is_default')
                            ->label('Default Currency')
                            ->hint('Only one currency can be default. Setting this will unset the current default.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('symbol')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('precision')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->label('Default')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('position')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Default')
                    ->trueLabel('Default Currency')
                    ->falseLabel('Non-Default')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Currency $record) {
                        // Prevent deleting the default currency
                        if ($record->is_default) {
                            \Filament\Notifications\Notification::make()
                                ->title('Cannot Delete Default Currency')
                                ->body('Please set another currency as default before deleting this one.')
                                ->danger()
                                ->send();
                            return false;
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Check if any default currencies are being deleted
                            $defaultCount = $records->where('is_default', true)->count();
                            if ($defaultCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Cannot Delete Default Currency')
                                    ->body('Some selected currencies are set as default. Please change the default before deleting.')
                                    ->danger()
                                    ->send();
                                return false;
                            }
                        }),
                ]),
            ])
            ->defaultSort('position', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCurrencies::class,
            'create' => Pages\CreateCurrency::class,
            'edit' => Pages\EditCurrency::class,
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Currencies';
    }

    public static function getModelLabel(): string
    {
        return 'Currency';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Currencies';
    }
}
