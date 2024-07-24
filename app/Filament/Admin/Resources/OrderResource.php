<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\FormBuilder\Elements\Select;
use MicroweberPackages\Order\Models\Order;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;


use Illuminate\Support\Carbon;
use MicroweberPackages\Product\Models\Product;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

//    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getDetailsFormSchema())
                            ->columns(2),

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
                    ->columnSpan(['lg' => fn(?Order $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn(Order $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn(Order $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn(?Order $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_id')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                //order_completed
                Tables\Columns\BooleanColumn::make('order_completed')
                    ->label('Order completed')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BooleanColumn::make('is_paid')
                    ->label('Is paid')
                    ->sortable()
                    ->toggleable(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }


    /** @return Forms\Components\Component[] */
    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('order_id')
                ->default('ORDER-' . random_int(100000, 999999))
                //   ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(Order::class, 'order_id', ignoreRecord: true),


            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'email')
                ->searchable()
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

//            Forms\Components\ToggleButtons::make('status')
//                ->inline()
//              //  ->options(OrderStatus::class)
//                ->required(),

//            Forms\Components\Select::make('currency')
//                ->searchable()
////                ->getSearchResultsUsing(fn (string $query) => Currency::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
////                ->getOptionLabelUsing(fn ($value): ?string => Currency::firstWhere('id', $value)?->getAttribute('name'))
//                ->required(),

//            AddressForm::make('address')
//                ->columnSpan('full'),

            Forms\Components\MarkdownEditor::make('other_info')
                ->columnSpan('full'),

            //order_completed

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
                                $html .= '<img src="'.$product->thumbnail().'" width="16px" />';
                                $html .=  $product->title;
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

                Repeater::make('custom_fields_json')
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
                        $customFieldsOptionsValues = [];
                        if ($findCustomFields) {
                            foreach ($findCustomFields as $customField) {
                                $customFieldsOptions[$customField->name] = $customField->name;
                                $customFieldValues = $customField->fieldValue()->get();
                                if (!$customFieldValues) {
                                    continue;
                                }
                                foreach ($customFieldValues as $customFieldValue) {
                                    $customFieldsOptionsValues[$customField->name][] = $customFieldValue->value;
                                }
                            }
                        }
//                        $customFieldsJsonSaved = $get('custom_fields_json');
//                        if (!empty($customFieldsJsonSaved)) {
//                            foreach ($customFieldsJsonSaved as $customFieldJsonField) {
//                                if (isset($customFieldJsonField['field_name'])) {
//                                    unset($customFieldsOptions[$customFieldJsonField['field_name']]);
//                                }
//                            }
//                        }

                        return [
                            Forms\Components\Select::make('field_name')
                                ->label('Field')
                                ->options($customFieldsOptions)
                                ->live(),
                            Forms\Components\Select::make('field_value')
                                ->label('Field Value')
                                ->hidden(function (Forms\Get $get) use($customFieldsOptions) {
                                    if (in_array($get('field_name'), $customFieldsOptions)) {
                                        return false;
                                    }
                                    return true;
                                })
                                ->options(function (Forms\Get $get) use($customFieldsOptionsValues) {
                                    return $customFieldsOptionsValues[$get('field_name')];
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

                        return ProductResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['rel_id'])),
            ])
            ->orderColumn('sort')
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md' => 10,
            ])
            ->required();
    }

}
