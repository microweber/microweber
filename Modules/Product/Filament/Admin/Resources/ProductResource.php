<?php

namespace Modules\Product\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Product\Filament\Exports\ProductExporter;
use Modules\Product\Filament\Imports\ProductImporter;
use Modules\Product\Models\Product;

class ProductResource extends ContentResource
{

    protected static ?string $model = Product::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Shop';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 1;


    protected static string $contentType = 'product';
    protected static string $subType = 'product';

    public static function getPages(): array
    {
        return [
            'index' => ProductResource\Pages\ListProducts::route('/'),
            'create' => ProductResource\Pages\CreateProduct::route('/create'),
            'edit' => ProductResource\Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        $schema = parent::form($schema);

        // A "downloadable product" is a product with subtype 'downloadable_product'.
        // The toggle is mapped to/from the subtype column in the Create/Edit pages
        // (it is not a model attribute itself).
        return $schema->components(array_merge($schema->getComponents(), [
            Section::make(__('Product type'))
                ->schema([
                    Toggle::make('is_downloadable')
                        ->label(__('Downloadable product'))
                        ->helperText(__('Digital product — buyers get secure downloads and shipping is skipped at checkout.'))
                        ->default(false)
                        ->dehydrated(true),

                    // Per-product opt-in (default off): email the buyer the secure
                    // tokenized download link when this product is ordered. Stored
                    // in content_data; mapped in the Create/Edit pages. Only
                    // meaningful for downloadable products.
                    Toggle::make('email_download_on_order')
                        ->label(__('Email download link on order'))
                        ->helperText(__('When ordered, email the buyer a secure download link for this product.'))
                        ->default(false)
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => (bool) $get('is_downloadable'))
                        ->dehydrated(true),

                    // A "bookable product" (subtype 'booking_product'): customers
                    // reserve it for a date range (rooms) or a time slot (services),
                    // with per-product capacity + availability tracking. The config
                    // is persisted to booking_product_settings by the Create/Edit
                    // pages; this toggle maps to the subtype.
                    Toggle::make('is_bookable')
                        ->label(__('Bookable product'))
                        ->helperText(__('Customers reserve dates/times; availability is tracked and shipping is skipped.'))
                        ->default(false)
                        ->live()
                        ->dehydrated(true),
                ]),

            Section::make(__('Booking settings'))
                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => (bool) $get('is_bookable'))
                ->columns(2)
                ->schema([
                    \Filament\Forms\Components\Select::make('booking_type')
                        ->label(__('Booking type'))
                        ->options([
                            'date_range' => __('Date range (nightly / rooms)'),
                            'time_slot' => __('Time slot (hourly / services)'),
                        ])
                        ->default('date_range')
                        ->live()
                        ->dehydrated(true),
                    TextInput::make('booking_capacity')
                        ->label(__('Capacity (units)'))
                        ->helperText(__('How many can be booked at once, e.g. number of identical rooms.'))
                        ->numeric()->minValue(1)->default(1)->dehydrated(true),
                    \Filament\Forms\Components\Select::make('booking_price_unit')
                        ->label(__('Price per'))
                        ->options([
                            'per_night' => __('Night'),
                            'per_hour' => __('Hour'),
                            'per_slot' => __('Slot'),
                            'flat' => __('Flat (whole booking)'),
                        ])
                        ->default('per_night')->dehydrated(true),
                    TextInput::make('booking_unit_price')
                        ->label(__('Unit price'))
                        ->helperText(__('Leave empty to use the product price.'))
                        ->numeric()->minValue(0)->dehydrated(true),
                    TextInput::make('booking_min_duration')
                        ->label(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('booking_type') === 'time_slot' ? __('Min minutes') : __('Min nights'))
                        ->numeric()->minValue(1)->dehydrated(true),
                    TextInput::make('booking_max_duration')
                        ->label(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('booking_type') === 'time_slot' ? __('Max minutes') : __('Max nights'))
                        ->numeric()->minValue(1)->dehydrated(true),
                    TextInput::make('booking_buffer_minutes')
                        ->label(__('Turnaround buffer (minutes)'))
                        ->numeric()->minValue(0)->default(0)->dehydrated(true),
                    // Time-slot only
                    TextInput::make('booking_slot_minutes')
                        ->label(__('Slot length (minutes)'))
                        ->numeric()->minValue(1)
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('booking_type') === 'time_slot')
                        ->dehydrated(true),
                    \Filament\Forms\Components\TimePicker::make('booking_opens_at')
                        ->label(__('Opens at'))->seconds(false)
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('booking_type') === 'time_slot')
                        ->dehydrated(true),
                    \Filament\Forms\Components\TimePicker::make('booking_closes_at')
                        ->label(__('Closes at'))->seconds(false)
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('booking_type') === 'time_slot')
                        ->dehydrated(true),
                    // Date-range only
                    \Filament\Forms\Components\TimePicker::make('booking_check_in_time')
                        ->label(__('Check-in time'))->seconds(false)
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('booking_type') !== 'time_slot')
                        ->dehydrated(true),
                    \Filament\Forms\Components\TimePicker::make('booking_check_out_time')
                        ->label(__('Check-out time'))->seconds(false)
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('booking_type') !== 'time_slot')
                        ->dehydrated(true),
                ]),

            Section::make(__('Add to cart link'))
                ->description(__('Share this link to add the product straight to the cart.'))
                ->visible(fn (?Model $record) => $record !== null && $record->getKey())
                ->schema([
                    TextInput::make('add_to_cart_link')
                        ->hiddenLabel()
                        ->readOnly()
                        ->dehydrated(false)
                        ->helperText(__('Append ?qty=N to set a quantity.'))
                        ->afterStateHydrated(function (TextInput $component, ?Model $record) {
                            if ($record && $record->getKey()) {
                                $component->state(url('add-to-cart/' . $record->getKey()));
                            }
                        }),
                ]),
        ]));
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->headerActions([
                \Filament\Actions\ActionGroup::make([
                \MicroweberPackages\Filament\Actions\ImportAction::make('importProducts')
                    ->icon('heroicon-m-cloud-arrow-up')
                    ->importer(ProductImporter::class)
                    ->chunkSize(50),
                \Filament\Actions\ExportAction::make()
                    ->exporter(ProductExporter::class)
                    ->icon('heroicon-m-cloud-arrow-down')
                    ->form(function (\Filament\Actions\ExportAction $action): array {
                        $exportColumns = ProductExporter::getColumns();
                        $schemaSchema = [];
                        foreach ($exportColumns as $column) {
                            $schemaSchema[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                ->label($column->getLabel())
                                ->default(true);
                        }
                        $schemaSchema[] = \Filament\Forms\Components\Checkbox::make('export_multiple')
                            ->label('Export to multiple files (ZIP)');
                        return $schemaSchema;
                    })
                    ->action(function (array $data, Table $table) {
                        $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                        $exportMultiple = $data['export_multiple'] ?? false;
                        $url = route('filament.admin.product.export', ['columns' => $selectedColumns, 'export_multiple' => $exportMultiple]);
                        return redirect()->to($url);
                    }),
                ])->icon('heroicon-o-cog-6-tooth')->tooltip('Settings')->color('gray')->button()->label(''),
                \Filament\Actions\CreateAction::make()
                    ->extraAttributes(['class' => 'hidden md:inline-flex']),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\ExportBulkAction::make()
                        ->exporter(ProductExporter::class)
                        ->form(function (\Filament\Actions\BulkAction $action): array {
                            $exportColumns = ProductExporter::getColumns();
                            $schemaSchema = [];
                            foreach ($exportColumns as $column) {
                                $schemaSchema[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                    ->label($column->getLabel())
                                    ->default(true);
                            }
                            $schemaSchema[] = \Filament\Forms\Components\Checkbox::make('export_multiple')
                                ->label('Export to multiple files (ZIP)')
                                ->default(false);
                            return $schemaSchema;
                        })
                        ->action(function (array $data, \Filament\Actions\BulkAction $action) {
                            $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                            $selectedRecordIds = implode(',', $action->getRecords()->pluck('id')->toArray());
                            $exportMultiple = $data['export_multiple'] ?? false;
                            $route = route('filament.admin.product.export', ['columns' => $selectedColumns, 'selected_ids' => $selectedRecordIds, 'export_multiple' => $exportMultiple]);
                            return redirect()->to($route);
                        }),
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
