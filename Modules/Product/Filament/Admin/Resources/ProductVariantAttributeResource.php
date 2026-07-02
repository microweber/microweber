<?php

namespace Modules\Product\Filament\Admin\Resources;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Product\Models\ProductVariantAttribute;

class ProductVariantAttributeResource extends Resource
{
    protected static ?string $model = ProductVariantAttribute::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-swatch';

    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';

    protected static string $description = 'Configure product variant attributes';

    protected static ?int $navigationSort = 5;

    public static function getDescription(): string
    {
        return static::$description;
    }

    protected static ?string $label = 'Variant Attribute';

    protected static ?string $pluralLabel = 'Variant Attributes';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'key', 'description'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->type) {
            $details['Type'] = ucfirst($record->type);
        }

        if ($record->key) {
            $details['Key'] = $record->key;
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(static::formSchema());
    }

    public static function formSchema(): array
    {
        return [
            Section::make('Attribute Information')
                ->icon('heroicon-m-information-circle')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Set $set) {
                            $set('key', Str::slug($state));
                        }),

                    TextInput::make('key')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('Unique identifier used in code'),

                    Textarea::make('description')
                        ->maxLength(500)
                        ->rows(2),

                    Select::make('type')
                        ->required()
                        ->options([
                            'select' => 'Dropdown Select',
                            'radio' => 'Radio Buttons',
                            'color' => 'Color Swatches',
                            'button' => 'Button Group',
                        ])
                        ->default('select')
                        ->helperText('How this attribute will be displayed on the product page'),

                    TextInput::make('position')
                        ->numeric()
                        ->default(0)
                        ->helperText('Display order (lower numbers appear first)'),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columnSpan(['lg' => 2]),

            Section::make('Attribute Values')
                ->icon('heroicon-m-queue-list')
                ->schema([
                    Repeater::make('values')
                        ->relationship('values')
                        ->label('')
                        ->schema([
                            TextInput::make('value')
                                ->required()
                                ->placeholder('e.g., Small, Red, Cotton')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $set('key', Str::slug($state));
                                }),

                            TextInput::make('key')
                                ->required()
                                ->placeholder('e.g., small, red, cotton'),

                            TextInput::make('color_code')
                                ->label('Color Code')
                                ->placeholder('#FF0000')
                                ->visible(function (Get $get) {
                                    return $get('../../type') === 'color';
                                }),

                            TextInput::make('position')
                                ->numeric()
                                ->default(0),

                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                        ])
                        ->columns(2)
                        ->collapsible()
                        ->itemLabel(fn(array $state): ?string => $state['value'] ?? null)
                        ->addActionLabel('Add Value')
                        ->reorderable()
                        ->defaultItems(0),
                ])
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'select' => 'Dropdown',
                        'radio' => 'Radio',
                        'color' => 'Color',
                        'button' => 'Button',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'select' => 'info',
                        'radio' => 'success',
                        'color' => 'warning',
                        'button' => 'primary',
                        default => 'gray',
                    }),

                // task-2026-05-28-c7f4d2 / AI-1063 — VALUES column showed count
                // "(1)" instead of actual value names (S, M, L, XL).
                // ->counts('values') emits only the integer count.
                // Replaced with ->getStateUsing() that plucks the 'value' field
                // from the eager-loaded values collection and joins with ', '.
                TextColumn::make('values_list')
                    ->label('Values')
                    ->getStateUsing(fn ($record) =>
                        $record->values->pluck('value')->filter()->implode(', ') ?: '—'
                    )
                    ->wrap()
                    ->searchable(false),

                TextColumn::make('position')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'select' => 'Dropdown',
                        'radio' => 'Radio',
                        'color' => 'Color',
                        'button' => 'Button',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('position', 'asc');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // task-2026-05-28-c7f4d2 / AI-1063 — eager-load values relation so the
        // VALUES column can render comma-separated labels without N+1 queries.
        return parent::getEloquentQuery()->with(['values']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ProductVariantAttributeResource\Pages\ListProductVariantAttributes::route('/'),
            'create' => ProductVariantAttributeResource\Pages\CreateProductVariantAttribute::route('/create'),
            'edit' => ProductVariantAttributeResource\Pages\EditProductVariantAttribute::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }
}
