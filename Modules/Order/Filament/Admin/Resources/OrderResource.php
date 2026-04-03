<?php

namespace Modules\Order\Filament\Admin\Resources;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use Modules\Content\Models\Content;
use Modules\CustomFields\Models\CustomField;
use Modules\Invoice\Services\InvoiceService;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Filament\Admin\Resources\OrderResource\RelationManagers\PaymentsRelationManager;
use Modules\Order\Filament\Admin\Resources\OrderResource\Widgets\OrderStats;
use Modules\Order\Models\Order;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Models\PaymentProvider;
use Modules\Product\Models\Product;
use Squire\Models\Country;
use Squire\Models\Currency;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;


    protected static string | \UnitEnum | null $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 12;

    protected static ?string $recordTitleAttribute = 'order_reference_id';

public static function getNavigationBadgeTooltip(): ?string
    {
        return 'New orders';
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('order_status', OrderStatus::New->value)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        \Filament\Schemas\Components\Tabs::make('OrderTabs')
                            ->contained()
                            ->tabs([
                                static::orderDetailsTab(),
                                static::shippingTab(),
                                static::paymentTab(),
                                static::advancedTab(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make([
                    static::orderStatusSection(),
                    static::orderTimestampsSection(),
                    static::statusTimelineSection(),
                    static::refundsSection(),
                ])->columnSpan(['lg' => 1])

            ])
            ->columns(3);
    }

    private static function getLatestPayment(?Order $record): ?object
    {
        return $record?->payments()->latest()->first();
    }

    protected static function orderDetailsTab(): \Filament\Schemas\Components\Tabs\Tab
    {
        return \Filament\Schemas\Components\Tabs\Tab::make('Order Details')
            ->icon('heroicon-o-shopping-cart')
            ->schema([
                Section::make()
                    ->heading('Customer & Reference')
                    ->schema(static::getDetailsFormSchema())
                    ->columnSpanFull(),

                Section::make('Order items')
                    ->schema([
                        static::getItemsRepeater(),
                    ]),
            ]);
    }

    protected static function shippingTab(): \Filament\Schemas\Components\Tabs\Tab
    {
        return \Filament\Schemas\Components\Tabs\Tab::make('Shipping')
            ->icon('heroicon-o-truck')
            ->schema([
                Section::make()
                    ->heading('Shipping details')
                    ->schema([
                        Forms\Components\Select::make('country')
                            ->searchable()
                            ->getSearchResultsUsing(fn(string $query) => Country::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                            ->formatStateUsing(fn($state): ?string => $state ? Country::firstWhere('id', $state)?->getAttribute('name') : null)
                            ->options(function () {
                                return Country::all()->pluck('name', 'id');
                            })
                            ->preload(),

                        Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('city'),
                                Forms\Components\TextInput::make('state')->label('State / Province'),
                                Forms\Components\TextInput::make('zip')->label('Zip / Postal code'),
                            ])->columns(3),

                        Forms\Components\Textarea::make('address'),
                        Forms\Components\Textarea::make('address2'),
                        Forms\Components\TextInput::make('phone'),
                    ])->columnSpanFull(),

                Section::make()
                    ->heading('Tracking')
                    ->schema([
                        Forms\Components\TextInput::make('shipping_tracking_number')
                            ->label('Tracking number')
                            ->placeholder('e.g. 1Z999AA10123456784')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('shipping_tracking_url')
                            ->label('Tracking URL')
                            ->placeholder('https://...')
                            ->url()
                            ->maxLength(2048),
                    ])->columnSpanFull(),
            ]);
    }

    protected static function paymentTab(): \Filament\Schemas\Components\Tabs\Tab
    {
        return \Filament\Schemas\Components\Tabs\Tab::make('Payment')
            ->icon('heroicon-o-credit-card')
            ->schema([
                Section::make()
                    ->heading('Payment Information')
                    ->schema([
                        Forms\Components\TextInput::make('payment_amount')
                            ->label('Amount')
                            ->numeric()
                            ->regex('/^\d{1,6}(\.\d{0,2})?$/')
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, ?Order $record) {
                                $payment = static::getLatestPayment($record);
                                $component->state($payment?->amount);
                            }),

                        Forms\Components\Select::make('payment_currency')
                            ->label('Currency')
                            ->searchable()
                            ->dehydrated(false)
                            ->options(fn () => Currency::all()->pluck('name', 'id'))
                            ->afterStateHydrated(function (Forms\Components\Select $component, ?Order $record) {
                                $payment = static::getLatestPayment($record);
                                $component->state($payment?->currency);
                            }),

                        Forms\Components\Select::make('payment_status')
                            ->label('Status')
                            ->dehydrated(false)
                            ->options(PaymentStatus::class)
                            ->default(PaymentStatus::Pending)
                            ->afterStateHydrated(function (Forms\Components\Select $component, ?Order $record) {
                                $payment = static::getLatestPayment($record);
                                $component->state($payment?->status);
                            }),

                        Forms\Components\Select::make('payment_provider_id')
                            ->label('Payment Provider')
                            ->dehydrated(false)
                            ->options(fn () => PaymentProvider::where('is_active', 1)->pluck('name', 'id'))
                            ->searchable()
                            ->afterStateHydrated(function (Forms\Components\Select $component, ?Order $record) {
                                $payment = static::getLatestPayment($record);
                                $component->state($payment?->payment_provider_id);
                            }),

                        Forms\Components\TextInput::make('payment_transaction_id')
                            ->label('Transaction ID')
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, ?Order $record) {
                                $payment = static::getLatestPayment($record);
                                $component->state($payment?->transaction_id);
                            }),
                    ])->columns(2),
            ]);
    }

    protected static function advancedTab(): \Filament\Schemas\Components\Tabs\Tab
    {
        return \Filament\Schemas\Components\Tabs\Tab::make('Advanced')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                Section::make('Import / Export')
                    ->collapsible()
                    ->collapsed()
                    ->headerActions([
                        \MicroweberPackages\Filament\Tables\Actions\ImportAction::make('importOrders')
                            ->icon('heroicon-m-cloud-arrow-up')
                            ->importer(\Modules\Order\Filament\Imports\OrderImporter::class)
                            ->chunkSize(50),
                        Tables\Actions\ExportAction::make()
                            ->exporter(\Modules\Order\Filament\Exports\OrderExporter::class)
                            ->icon('heroicon-m-cloud-arrow-down')
                            ->form(function (Tables\Actions\ExportAction $action): array {
                                $exportColumns = \Modules\Order\Filament\Exports\OrderExporter::getColumns();
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
                                $selectedColumns = array_keys(array_filter(\Illuminate\Support\Arr::except($data, 'export_multiple')));
                                $exportMultiple = $data['export_multiple'] ?? false;
                                $url = route('filament.admin.order.export', ['columns' => $selectedColumns, 'export_multiple' => $exportMultiple]);
                                return redirect()->to($url);
                            }),
                    ])
                    ->schema([
                        Forms\Components\Placeholder::make('import_export_info')
                            ->label('')
                            ->content('Use the buttons above to import or export orders.'),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Forms\Components\MarkdownEditor::make('other_info')
                            ->columnSpan('full'),
                    ]),
            ]);
    }

    protected static function orderStatusSection(): Section
    {
        return Section::make()
            ->schema([
                Forms\Components\Select::make('order_status')
                    ->columnSpanFull()
                    ->default(OrderStatus::New)
                    ->options(OrderStatus::class)
                    ->required(),
                Forms\Components\Toggle::make('order_completed')
                    ->default(1)
                    ->label('Order completed')
                    ->columnSpan('full')
                    ->required(),
                Forms\Components\Toggle::make('is_paid')
                    ->default(1)
                    ->label('Is paid')
                    ->columnSpan('full')
                    ->required(),
            ]);
    }

    protected static function orderTimestampsSection(): Section
    {
        return Section::make()
            ->schema([
                Forms\Components\Placeholder::make('created_at')
                    ->label('Created at')
                    ->content(fn(Order $record): ?string => $record->created_at?->diffForHumans()),
                Forms\Components\Placeholder::make('updated_at')
                    ->label('Last modified at')
                    ->content(fn(Order $record): ?string => $record->updated_at?->diffForHumans()),
            ])
            ->hidden(fn(?Order $record) => $record === null);
    }

    protected static function statusTimelineSection(): Section
    {
        return Section::make('Status Timeline')
            ->schema([
                Forms\Components\ViewField::make('status_timeline')
                    ->view('modules.order::filament.admin.status-timeline')
                    ->dehydrated(false)
                    ->columnSpanFull(),
            ])
            ->collapsible()
            ->hidden(fn(?Order $record) => $record === null);
    }

    protected static function refundsSection(): Section
    {
        return Section::make('Refunds')
            ->schema([
                Forms\Components\ViewField::make('refund_history')
                    ->view('modules.order::filament.admin.refund-history')
                    ->dehydrated(false)
                    ->columnSpanFull(),
            ])
            ->collapsible()
            ->hidden(fn(?Order $record) => $record === null || $record->refunds->isEmpty());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(150)
            ->paginationPageOptions([150, 200, 500, 1000, 'all'])
            ->emptyState(function (Table $table) {
                $modelName = static::$model;

                return view('modules.content::filament.admin.empty-state', ['modelName' => $modelName]);

            })
            ->modifyQueryUsing(fn ($query) => $query->with(['customer', 'cart.products']))
            ->columns([


                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->grow(false)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->grow(false)
                    ->dateTime('M d, Y'),

                ImageUrlColumn::make('firstProductThumbnail')
                    ->label('Product')
                    ->grow(false)
                    ->circular()
                    ->defaultImageUrl(function (Order $record) {
                        return $record->thumbnail();
                    }),

                Tables\Columns\TextColumn::make('order_reference_id')
                    ->label('Number')
                    ->grow(false)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_status')
                    ->label('Status')
                    ->grow(false)
                    ->badge(),


                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('amount')
                    ->grow(false)
                    ->sortable()
                    ->money(fn($record) => $record->currency),


                Tables\Columns\BooleanColumn::make('order_completed')
                    ->label('Completed')
                    ->grow(false)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BooleanColumn::make('is_paid')
                    ->label('Paid')
                    ->grow(false)
                    ->sortable()
                    ->toggleable(),


            ])
            ->filters([

            ])
            ->paginationPageOptions([
                50,
                100,
                200,
                'all'
            ])
->actions([
            Tables\Actions\EditAction::make()
                ->iconButton(),
            Tables\Actions\DeleteAction::make()
                ->iconButton(),
            Tables\Actions\Action::make('generate_invoice')
                ->label('Generate Invoice')
                ->iconButton()
                ->tooltip('Generate Invoice')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->modalHeading('Generate Invoice from Order')
                ->modalDescription('This will create a new invoice based on this order data.')
                ->requiresConfirmation()
                ->modalButton('Generate Invoice')
                ->visible(fn(Order $record): bool => !$record->invoice_id)
                ->action(function (Order $record) {
                    try {
                        $invoiceService = app(InvoiceService::class);
                        $result = $invoiceService->generateFromOrder($record->id, [
                            'status' => \Modules\Invoice\Models\Invoice::STATUS_DRAFT,
                            'due_date' => now()->addDays(14),
                        ]);

                        if ($result['success']) {
                            // Update order with invoice_id
                            $record->invoice_id = $result['invoice_id'];
                            $record->save();

                            Log::info('Invoice generated from order', [
                                'order_id' => $record->id,
                                'invoice_id' => $result['invoice_id'],
                            ]);

                            return redirect()->back()->with('success', $result['message']);
                        } else {
                            return redirect()->back()->with('error', $result['message']);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to generate invoice from order', [
                            'order_id' => $record->id,
                            'error' => $e->getMessage(),
                        ]);

                        return redirect()->back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
                    }
                }),
            Tables\Actions\Action::make('view_invoice')
                ->label('View Invoice')
                ->iconButton()
                ->tooltip('View Invoice')
                ->icon('heroicon-o-document-text')
                ->url(fn(Order $record): ?string => $record->invoice_id ? \Modules\Invoice\Filament\Resources\InvoiceResource::getUrl('edit', ['record' => $record->invoice_id]) : null)
                ->visible(fn(Order $record): bool => (bool) $record->invoice_id)
                ->openUrlInNewTab(),
        ])
//            ->headerActions([
//                Tables\Actions\CreateAction::make()->label('Create order')
//            ])
            ->defaultSort('id', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update status')
                        ->icon('heroicon-m-arrow-path')
                        ->form([
                            Forms\Components\Select::make('order_status')
                                ->label('New status')
                                ->options(OrderStatus::class)
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            $records->each(function (Order $order) use ($data) {
                                $order->order_status = $data['order_status'];
                                $order->save();
                            });
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Order statuses updated'),
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(\Modules\Order\Filament\Exports\OrderExporter::class)
                        ->form(function (Tables\Actions\BulkAction $action): array {
                            $exportColumns = \Modules\Order\Filament\Exports\OrderExporter::getColumns();
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
                        ->action(function (array $data, Tables\Actions\BulkAction $action) {
                            $selectedColumns = array_keys(array_filter(\Illuminate\Support\Arr::except($data, 'export_multiple')));
                            $selectedRecordIds = implode(',', $action->getRecords()->pluck('id')->toArray());
                            $exportMultiple = $data['export_multiple'] ?? false;
                            $route = route('filament.admin.order.export', ['columns' => $selectedColumns, 'selected_ids' => $selectedRecordIds, 'export_multiple' => $exportMultiple]);
                            return redirect()->to($route);
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public static function getRelations(): array
    {
        $relations = [];
        //   $countPaymentProviders = PaymentProvider::count();
        //if ($countPaymentProviders > 0) {
        $relations[] = PaymentsRelationManager::class;
        // }

        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders::route('/'),
            'create' => \Modules\Order\Filament\Admin\Resources\OrderResource\Pages\CreateOrder::route('/create'),
            'edit' => \Modules\Order\Filament\Admin\Resources\OrderResource\Pages\EditOrder::route('/{record}/edit'),
        ];
    }


    /** @return Forms\Components\Component[] */
    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('order_reference_id')
                ->default('ORDER-' . now()->format('YmdHis'))
                //   ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(Order::class, 'order_reference_id', ignoreRecord: true),


            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'email')
                ->searchable()
                ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->getFullName()}  {$record->getEmail()}")
                ->label('Customer')
                //    ->preload()
                ->lazy()
                ->native(false)
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email address')
                        ->required()
                        ->email()
                        ->maxLength(255)
                        ->unique(),

                    Forms\Components\TextInput::make('phone')
                        ->maxLength(255),


                ])
                ->createOptionAction(function ($action) {
                    return $action
                        ->modalHeading('Create customer')
                        ->modalSubmitActionLabel('Create customer')
                        ->modalWidth('lg');
                }),


//            Forms\Components\Select::make('currency')
//                ->searchable()
////                ->getSearchResultsUsing(fn (string $query) => Currency::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
////                ->getOptionLabelUsing(fn ($value): ?string => Currency::firstWhere('id', $value)?->getAttribute('name'))
//                ->required(),

//            AddressForm::make('address')
//                ->columnSpan('full'),

        ];
    }

    public static function getCleanOptionString(Model $model): string
    {
        return $model->title . ' (' . $model->price . ')';
    }

    public static function getItemsRepeater(): Repeater
    {


        return Repeater::make('cart')
            ->relationship('cart')
            ->label('Items')
            ->schema([

                Forms\Components\Hidden::make('rel_type')
                    ->default(morph_name(Product::class)),
                Forms\Components\Hidden::make('order_completed')
                    ->default(1),


                Forms\Components\Select::make('rel_id')
                    ->label('Product')
                    ->allowHtml(true)
                    ->preload(true)
                    ->options(function () {
                        $options = [];
                        $products = Product::all();
                        if ($products) {
                            foreach ($products as $product) {
                                $html = '';
                                $html .= '<div class="flex gap-2 items-center">';
                                $html .= '<img src="' . $product->thumbnail() . '" width="16px" />';
                                $html .= $product->title;
                                if (!$product->getInStockAttribute()) {
                                    $html .= '<span class="bg-gray-200 text-black text-[0.6rem]]">oos</span>';
                                }
                                $html .= ' (' . $product->getPriceDisplayAttribute() . ')';
                                $html .= '</div>';
                                $options[$product->id] = $html;
                            }
                        }

                        return $options;
                    })
                    // ->options(Product::query()->whereNotNull('title')->pluck('title', 'id'))
                    ->required()
                    ->afterStateUpdated(fn($state, Forms\Set $set) => $set('price', Product::find($state)?->price ?? 0))
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 5,
                    ])
                    ->searchable(),

                Forms\Components\TextInput::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->default(1)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    //    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required()
                    ->columnSpan([
                        'md' => 3,
                    ]),

                Repeater::make('custom_fields_data')
                    ->label('Custom fields')
                    ->hidden(function (Forms\Get $get) {
                        $relId = $get('rel_id');
                        $findCustomFields = CustomField::where('rel_id', $relId)
                            ->where('rel_type', morph_name(Content::class))
                            ->count();
                        if ($findCustomFields > 0) {
                            return false;
                        }
                        return true;
                    })
                    ->schema(function (Forms\Get $get) {

                        $relId = $get('rel_id');
                        $findCustomFields = CustomField::where('rel_id', $relId)
                            ->where('rel_type', morph_name(Content::class))
                            ->get();
                        $customFieldsOptions = [];
                        $customFieldsOptionsDetailed = [];
                        $customFieldsOptionsValues = [];
                        $customFieldsOptionsValuesDetailed = [];
                        if ($findCustomFields) {
                            foreach ($findCustomFields as $customField) {
                                $customFieldsOptions[$customField->id] = $customField->name;
                                $customFieldsOptionsDetailed[$customField->id] = $customField->toArray();
                                $customFieldValues = $customField->fieldValue()->get();
                                if (!$customFieldValues) {
                                    continue;
                                }
                                foreach ($customFieldValues as $customFieldValue) {
                                    $customFieldsOptionsValues[$customField->id][] = $customFieldValue->value;
                                    $customFieldsOptionsValuesDetailed[$customField->id][] = $customFieldValue->toArray();
                                }
                            }
                        }

                        return [
                            Forms\Components\TextInput::make('field_name')
                                ->hidden(),
                            Forms\Components\TextInput::make('field_name_key')
                                ->hidden(),
                            Forms\Components\TextInput::make('field_type')
                                ->hidden(),
                            Forms\Components\TextInput::make('field_value')
                                ->hidden(),
                            Forms\Components\Select::make('field_id')
                                ->label('Field')
                                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) use ($customFieldsOptionsDetailed) {
                                    $set('field_name', $customFieldsOptionsDetailed[$state]['name']);
                                    $set('field_name_key', $customFieldsOptionsDetailed[$state]['name_key']);
                                    $set('field_type', $customFieldsOptionsDetailed[$state]['type']);
                                })
                                ->options($customFieldsOptions)
                                ->live(),
                            Forms\Components\Select::make('field_value_id')
                                ->label('Field Value')
                                ->hidden(function (Forms\Get $get) use ($customFieldsOptions) {
                                    if (array_key_exists($get('field_id'), $customFieldsOptions)) {
                                        return false;
                                    }
                                    return true;
                                })
                                ->live()
                                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) use ($customFieldsOptionsValuesDetailed) {
                                    $set('field_value', $customFieldsOptionsValuesDetailed[$get('field_id')][$state]['value']);
                                })
                                ->options(function (Forms\Get $get) use ($customFieldsOptionsValues) {
                                    if (isset($customFieldsOptionsValues[$get('field_id')])) {
                                        return $customFieldsOptionsValues[$get('field_id')];
                                    }
                                    return [
                                        'no_results' => 'No results'
                                    ];
                                }),
                        ];
                    })
                    ->columns(2)
                    ->columnSpanFull(),

            ])
            ->extraItemActions([
                Action::make('openProduct')
                    ->tooltip('Open product')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $product = Product::find($itemData['rel_id']);

                        if (!$product) {
                            return null;
                        }

                        return \Modules\Product\Filament\Admin\Resources\ProductResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['rel_id'])),
            ])
            ->defaultItems(0)
            ->hiddenLabel()
            ->columns([
                'md' => 10,
            ]);
    }

/** @return Builder<Order> */
public static function getEloquentQuery(): Builder
{
return parent::getEloquentQuery()
->with(['customer', 'cart', 'payments']);
}

    /**
     * Get the attributes that should be searchable globally.
     *
     * @return array
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['order_reference_id', 'id', 'amount', 'customer.email', 'customer.name'];
    }

    /**
     * Get the title for the global search result.
     *
     * @param Model $record
     * @return string
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return 'Order #' . $record->order_reference_id;
    }

    /**
     * Get the details for the global search result.
     *
     * @param Model $record
     * @return array
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Amount' => $record->amount . ' ' . ($record->currency ?? ''),
            'Customer' => $record->customer?->name ?? $record->customer?->email ?? 'N/A',
            'Status' => $record->order_status,
            'Completed' => $record->order_completed ? 'Yes' : 'No',
            'Paid' => $record->is_paid ? 'Yes' : 'No',
            'Date' => $record->created_at?->format('Y-m-d H:i'),
        ];
    }

    /**
     * Get the actions for the global search result.
     *
     * @param Model $record
     * @return array
     */
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            \Filament\Actions\Action::make('edit')
                ->url(static::getUrl('edit', ['record' => $record])),
            \Filament\Actions\Action::make('view')
                ->url(static::getUrl('edit', ['record' => $record])),
        ];
    }


}
