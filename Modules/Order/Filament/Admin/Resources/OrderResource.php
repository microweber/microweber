<?php

namespace Modules\Order\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Order\Filament\Admin\Resources\OrderResource\Pages;
use Modules\Content\Models\Content;
use Modules\CustomFields\Models\CustomField;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Filament\Admin\Resources\OrderResource\RelationManagers\PaymentsRelationManager;
use Modules\Order\Filament\Admin\Resources\OrderResource\Widgets\OrderStats;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Squire\Models\Country;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;


    protected static ?string $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'New orders';
    }

/*    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('order_status', OrderStatus::New)->count();
    }*/

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([

                        Forms\Components\Group::make()
                            ->schema([

                                Forms\Components\Section::make()
                                    ->heading('Order Details')
                                    ->schema(static::getDetailsFormSchema())
                                    ->collapsible()
                                    ->columnSpanFull(),

                                Forms\Components\Section::make()
                                    ->heading('Shipping details')
                                    ->collapsible()
                                    ->collapsed()
                                    ->schema([

                                        Forms\Components\Select::make('country')
                                            ->searchable()
                                            ->getSearchResultsUsing(fn(string $query) => Country::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                                            ->getOptionLabelUsing(fn($value): ?string => Country::firstWhere('id', $value)?->getAttribute('name')),

                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('city'),
                                                Forms\Components\TextInput::make('state')->label('State / Province'),
                                                Forms\Components\TextInput::make('zip')->label('Zip / Postal code'),

                                            ])->columns(3),

                                        Forms\Components\Textarea::make('address'),
                                        Forms\Components\Textarea::make('address2'),
                                        Forms\Components\TextInput::make('phone'),


                                    ])->columnSpanFull(),

                            ])->columns(2),

                        Forms\Components\Section::make('Order items')
                            ->headerActions([
                                Action::make('reset')
                                    ->modalHeading('Are you sure?')
                                    ->modalDescription('All existing items will be removed from the order.')
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->action(fn(Forms\Set $set) => $set('cart', [])),
                            ])
                            ->schema([
                                static::getItemsRepeater(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),


                Forms\Components\Group::make([

                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\ToggleButtons::make('order_status')
                                ->columnSpanFull()
                                ->inline()
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
                        ]),

                    Forms\Components\Section::make()
                        ->schema([

                            Forms\Components\Placeholder::make('created_at')
                                ->label('Created at')
                                ->content(fn(Order $record): ?string => $record->created_at?->diffForHumans()),

                            Forms\Components\Placeholder::make('updated_at')
                                ->label('Last modified at')
                                ->content(fn(Order $record): ?string => $record->updated_at?->diffForHumans()),
                        ])
                        ->hidden(fn(?Order $record) => $record === null),
                ])->columnSpan(['lg' => 1])

            ])
            ->columns(3);
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
            ->columns([


                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at'),

                Tables\Columns\TextColumn::make('order_status')
                    ->label('Status')
                    ->badge(),
                ImageUrlColumn::make('firstProductThumbnail')
                    ->label('Product')
                    ->circular()
                    ->defaultImageUrl(function (Order $record) {
                        return $record->thumbnail();
                    }),

                Tables\Columns\TextColumn::make('order_reference_id')
                    ->label('Number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),


                Tables\Columns\TextColumn::make('amount')
                    ->sortable()
                    ->money(fn($record) => $record->currency),


                Tables\Columns\BooleanColumn::make('order_completed')
                    ->label('Completed')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BooleanColumn::make('is_paid')
                    ->label('Paid')
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
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('id', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
                ->createOptionAction(function (Action $action) {
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

            Forms\Components\MarkdownEditor::make('other_info')
                ->columnSpan('full'),


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
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md' => 10,
            ])
            ->required();
    }

//    /** @return Builder<Order> */
//    public static function getEloquentQuery(): Builder
//    {
//        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
//    }

}
