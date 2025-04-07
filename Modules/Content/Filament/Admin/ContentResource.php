<?php

namespace Modules\Content\Filament\Admin;

use BobiMicroweber\FilamentDropdownColumn\Columns\DropdownColumn;
use Filament\Forms;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\GlobalSearch\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MicroweberPackages\Filament\Forms\Components\MwInputSlider;
use MicroweberPackages\Filament\Forms\Components\MwInputSliderGroup;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\Filament\Forms\Components\MwSelectTemplateForPage;
use MicroweberPackages\Filament\Forms\Components\MwTitleWithSlugInput;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use Modules\Page\Models\Page;
use Modules\Post\Models\Post;

class ContentResource extends Resource
{
    use Translatable;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $model = \Modules\Content\Models\Content::class;

    protected static ?string $navigationGroup = 'Website';

    protected static bool $shouldRegisterNavigation = false;


    public static function formArray($params = [])
    {
        $id = null;
        if (isset($params['id'])) {
            $id = $params['id'];
        }
        $contentType = 'page';
        $contentSubtype = 'static';
        if (isset($params['contentType'])) {
            $contentType = $params['contentType'];
        }
        if (isset($params['contentModel'])) {
            if ($params['contentModel'] == \Modules\Product\Models\Product::class) {
                $contentType = 'product';
            } elseif ($params['contentModel'] == \Modules\Content\Models\Content::class) {
                $contentType = 'page';
            } elseif ($params['contentModel'] == Post::class) {
                $contentType = 'post';
            } elseif ($params['contentModel'] == Page::class) {
                $contentType = 'page';
            }
        }
        if (isset($params['contentSubtype'])) {
            $contentSubtype = $params['contentSubtype'];
        }
        if (isset(static::$contentType)) {
            $contentType = static::$contentType;
        }
        if (isset(static::$subType)) {
            $contentSubtype = static::$subType;
        }

        $site_url = site_url();
        $sessionId = session()->getId();


        $mainForm = [

            Forms\Components\Group::make([

                Forms\Components\Group::make()
                    ->schema([

                        Forms\Components\Hidden::make('id')
                            ->default($id),
                        Forms\Components\Hidden::make('session_id')
                            ->default($sessionId),

                        Forms\Components\Hidden::make('content_type')
                            ->default($contentType),

                        Forms\Components\Hidden::make('subtype')
                            ->default($contentSubtype),


                        Forms\Components\Hidden::make('active_site_template')->visible(function (Forms\Get $get) {
                            return $get('content_type') == 'page';
                        }),
                        Forms\Components\Hidden::make('layout_file')->visible(function (Forms\Get $get) {
                            return $get('content_type') == 'page';
                        }),
                        Forms\Components\Hidden::make('tags')
                            ->default(function (?Model $record) {
                                if ($record) {

                                    return $record->getTagNamesAttribute();
                                }
                                return [];
                            })->afterStateHydrated(function (?Model $record, Forms\Get $get, Forms\Set $set, ?array $state) {

                                if ($record) {
                                    $categoryIds = $record->getTagNamesAttribute();
                                    if (!is_array($categoryIds)) {
                                        $categoryIds = explode(',', $categoryIds);
                                    }
                                    $set('tags', $categoryIds);
                                } else {
                                    $set('tags', []);
                                }
                            }),
                        Forms\Components\Hidden::make('categoryIds')
                            ->default(function (?Model $record) {
                                if ($record) {
                                    return $record->getCategoryIdsAttribute();
                                }
                                return [];
                            })
                            ->afterStateHydrated(function (?Model $record, Forms\Get $get, Forms\Set $set, ?array $state) {

                                if ($record) {
                                    $categoryIds = $record->getCategoryIdsAttribute();
                                    if (!is_array($categoryIds)) {
                                        $categoryIds = explode(',', $categoryIds);
                                    }
                                    $set('categoryIds', $categoryIds);
                                } else {
                                    $set('categoryIds', []);
                                }
                            })
                        ,

                        Forms\Components\Hidden::make('menuIds')
                            ->default(function (?Model $record) {
                                if ($record) {
                                    return $record->menuIds;
                                }
                                return [];
                            })
                            ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set, ?array $state, ?Model $record) {

                                if ($record) {
                                    $set('menuIds', $record->menuIds);
                                } else {
                                    $set('menuIds', []);
                                }
                            })
                        ,
                        Forms\Components\Hidden::make('parent'),

                        Forms\Components\Hidden::make('is_shop')
                            ->default(0)
                            ->visible(function (Forms\Get $get) {
                                return $get('content_type') === 'page';
                            }),
                        Forms\Components\Hidden::make('is_home')
                            ->default(0)
                            ->visible(function (Forms\Get $get) {
                                return $get('content_type') === 'page';
                            }),


                        Forms\Components\Section::make('General Information')
                            ->heading(function (Forms\Get $get) {
                                if ($get('content_type') === 'page') {
                                    if ($get('id')) {
                                        return 'Edit Page';
                                    } else {
                                        return 'Add New Page';
                                    }
                                }
                                if ($get('content_type') === 'product') {
                                    if ($get('id')) {
                                        return 'Edit Product';
                                    } else {
                                        return 'Add New Product';
                                    }
                                }
                                if ($get('content_type') === 'post') {
                                    if ($get('id')) {
                                        return 'Edit Post';
                                    } else {
                                        return 'Add New Post';
                                    }
                                }
                            })
                            ->schema([

                                MwTitleWithSlugInput::make(
                                    fieldTitle: 'title',
                                    fieldSlug: 'url',
                                    urlHost: $site_url,
                                    titleLabel: 'Title',
                                    slugLabel: 'Link:',

                                )
                                    ->columnSpanFull(),


                                Forms\Components\Textarea::make('description')
                                    ->columnSpan('full'),

//                    MwRichEditor::make('content_body')
//                        ->columnSpan('full')
//                        ->visible(function (Forms\Get $get) {
//                            return $get('content_type') !== 'page';
//                        }),
                            ])
                            ->columnSpanFull()
                            ->columns(2),

                        MwMediaBrowser::make('mediaIds')->label('Pictures'),


                        Forms\Components\Section::make('Pricing')
                            ->schema([

                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->columnSpan(['lg' => 2,'sm' => 2])
                                    ->required(),


                                Forms\Components\TextInput::make('special_price')
                                    ->afterStateHydrated(function (?Model $record, Forms\Get $get, Forms\Set $set, ?array $state) {

                                        if ($record) {
                                            $getSpecialPrice = $record->getSpecialPriceAttribute();

                                            $set('special_price', $getSpecialPrice);
                                        } else {
                                            $set('special_price','');
                                        }
                                    })
                                    ->numeric()
                                    ->columnSpan(['lg' => 2,'sm' => 2])
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->visible(function_exists('offers_get_price'))
                                ,


                            ])->columnSpanFull()->visible(function (Forms\Get $get) {
                                return $get('content_type') == 'product';
                            }),


                        Forms\Components\Section::make('Inventory')
                            ->schema([


                                Forms\Components\TextInput::make('content_data.sku')
                                    ->helperText('Stock Keeping Unit'),

                                Forms\Components\TextInput::make('content_data.barcode')
                                    ->helperText('ISBN, UPC, GTIN, etc.'),

                                Forms\Components\Toggle::make('content_data.track_quantity')
                                    ->label('Track Quantity')
                                    ->live()
                                    ->default(false),


                                Forms\Components\Group::make([
                                    Forms\Components\TextInput::make('content_data.quantity')
                                        ->numeric()
                                        ->rules(['regex:/^\d{1,6}$/'])
                                        ->default(0),

                                    Forms\Components\Checkbox::make('content_data.sell_oos')
                                        ->label('Continue selling when out of stock')
                                        ->default(false),

                                    Forms\Components\TextInput::make('content_data.max_qty_per_order')
                                        ->numeric()
                                        ->rules(['regex:/^\d{1,6}$/'])
                                        ->label('Max quantity per order')
                                        ->default(0),
                                ])->hidden(function (Forms\Get $get) {
                                    return !$get('content_data.track_quantity');
                                }),


                            ])->columnSpanFull()->visible(function (Forms\Get $get) {
                                return $get('content_type') == 'product';
                            }),
                        Forms\Components\Section::make('Select Template')
                            ->schema(
                                [
                                    MwSelectTemplateForPage::make(
                                        'active_site_template',
                                        'layout_file')
                                        ->columnSpanFull(),
                                ])
                            ->columnSpanFull()
                            ->visible(function (Forms\Get $get) {
                                return $get('content_type') == 'page';
                            }),


                        Forms\Components\Section::make('Shipping')
                            ->schema([

                                // This is a physical product
                                Forms\Components\Toggle::make('content_data.physical_product')
                                    ->label('This is a physical product')
                                    ->default(true)
                                    ->live(),

                                Forms\Components\Group::make([
                                    Forms\Components\TextInput::make('content_data.shipping_fixed_cost')
                                        ->numeric()
                                        ->helperText('Used to set your shipping price at checkout and label prices during fulfillment.')
                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                        ->suffix(currency_symbol())
                                        ->label('Fixed cost')
                                        ->columnSpanFull()
                                        ->default(0),


                                    Forms\Components\Toggle::make('content_data.free_shipping')
                                        ->columnSpanFull(),

                                    Forms\Components\Toggle::make('content_data.shipping_advanced_settings')
                                        ->label('Show advanced weight settings')
                                        ->live()
                                        ->columnSpanFull(),

                                ])->columns(2)->hidden(function (Forms\Get $get) {
                                    return !$get('content_data.physical_product');
                                }),


                                Forms\Components\Section::make('Shipping Advanced')
                                    ->heading('Advanced')
                                    ->description('Advanced product shipping settings.')
                                    ->schema([
                                        Forms\Components\TextInput::make('content_data.weight')
                                            ->numeric()
                                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                            ->label('Weight (kg)')
                                            ->default(0),


                                        Forms\Components\TextInput::make('content_data.width')
                                            ->numeric()
                                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                            ->label('Width (cm)')
                                            ->default(0),

                                        Forms\Components\TextInput::make('content_data.length')
                                            ->numeric()
                                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                            ->label('Length (cm)')
                                            ->default(0),

                                        Forms\Components\TextInput::make('content_data.depth')
                                            ->numeric()
                                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                            ->label('Depth (cm)')
                                            ->default(0),

                                        Forms\Components\Checkbox::make('content_data.params_in_checkout')
                                            ->label('Show parameters in checkout page')
                                            ->columnSpanFull()
                                            ->default(false),

                                    ])
                                    ->columns(4)
                                    ->visible(function (Forms\Get $get) {
                                        return $get('content_data.shipping_advanced_settings');
                                    }),

                            ])->columnSpanFull()->visible(function (Forms\Get $get) {
                                return $get('content_type') == 'product';
                            }),

                    ])->columnSpan(['lg' => 2]),


                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Published')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Published')
                                    ->default(function (Forms\Get $get) {
                                        return $get('id') ? 0 : 1;
                                    })

                            ]),


//            Forms\Components\Section::make('Parent page')
//                ->schema(function(?Models $record) {
//                    $parent = null;
//                    $categoryIds = [];
//                    if ($record) {
//                        $parent = $record->parent;
//                        $categoryIds = $record->getCategoryIdsAttribute();
//                    }
//                    return [
//                        Forms\Components\View::make('filament-forms::admin.mw-tree')
//                            ->viewData([
//                                'selectedPage' => $parent,
//                                'selectedCategories' => $categoryIds
//                            ])
//                    ];
//                }),


                        Forms\Components\Section::make('Tags')
                            ->schema([
                                Forms\Components\TagsInput::make('tags')
                                    ->label(false)
                                    ->reorderable()
                                    ->helperText('Separate using commas or Enter key.')
                                    ->placeholder('Add a tag'),
                            ]),

                        Forms\Components\Section::make('Menus')
                            ->schema([

                                Forms\Components\CheckboxList::make('menuIds')
                                    ->label('Menus')
                                    ->helperText('Select menu where this content will appear')
                                    ->options(function (?Model $record) {
                                        $menus = get_menus();
                                        $menusCheckboxes = [];
                                        if ($menus) {
                                            foreach ($menus as $menu) {
                                                $menusCheckboxes[$menu['id']] = Str::headline($menu['title']);
                                            }
                                        }
                                        return $menusCheckboxes;
                                    }),
                            ])
                    ])->columnSpan(['lg' => 1]),


            ])->columns(3)->columnSpanFull(),


        ];


        return [
            Tabs::make('ContentTabs')
                ->tabs([
                    Tabs\Tab::make('Details')
                        ->schema(
                            $mainForm
                        ),
                    Tabs\Tab::make('Custom Fields')
                        ->schema(function (Content|null $record) {

                            $relId = 0;
                            if (isset($record->id)) {
                                $relId = $record->id;
                            }


                            $customFieldParams = [
                                'relId' => $relId,
                                'relType' => morph_name(Content::class),
                            ];

                            if ($relId == 0) {
                                $customFieldParams['createdBy'] = user_id();
//                            if (isset($this->data['session_id']) and $this->data['session_id']) {
//                                $customFieldParams['sessionId'] = $this->data['session_id'];
//                            }
                            }

                            $components = [];
                            $components[] = Livewire::make('admin-list-custom-fields', $customFieldParams);

                            return $components;
                        }),
                    Tabs\Tab::make('SEO')
                        ->schema(
                            self::seoFormArray()
                        ),
                    Tabs\Tab::make('Advanced')
                        ->schema(self::advancedSettingsFormArray()),
                ])->columnSpanFull()


        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(static::formArray());
    }

    public static function seoFormArray()
    {
        return [
            Forms\Components\Section::make('Search engine optimisation (SEO)')
                ->description('Add a title and description to see how this product might appear in a search engine listing')
                ->schema([

                    Forms\Components\TextInput::make('content_meta_title')
                        ->label('Meta Title')
                        ->helperText('Describe for what is this page about in short title')
                        ->columnSpanFull(),


                    Forms\Components\Textarea::make('description')
                        ->label('Meta Description')
                        ->helperText('Please provide a brief summary of this web page')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('content_meta_keywords')
                        ->label('Meta Keywords')
                        ->helperText('Separate keywords with a comma and space. Type keywords that describe your content - Example: Blog, Online News, Phones for sale')
                        ->columnSpanFull(),

                ])
        ];
    }

    public static function seoForm(Form $form): Form
    {
        return $form
            ->schema(static::seoFormArray());
    }


    public static function advancedSettingsFormArray()
    {
        return [
            Forms\Components\Section::make('Advanced Settings')
                ->description('You can configure advanced settings for this content')
                ->schema([

                    Forms\Components\TextInput::make('original_link')
                        ->label('Redirect URL')
                        ->helperText('Redirect to another URL when this content is accessed')
                        ->columnSpanFull(),


                    Forms\Components\Toggle::make('require_login')
                        ->label('Require login')
                        ->visible(function (Forms\Get $get) {
                            return $get('id');
                        })
                        ->helperText('Require user to be logged in to view this content')
                        ->columnSpanFull(),


                    Forms\Components\Select::make('created_by')
                        ->visible(function (Forms\Get $get) {
                            return $get('id');
                        })
                        ->label('Author')
                        ->placeholder('Select author')
                        // ->options(User::all()->pluck('email', 'id'))
                        ->getSearchResultsUsing(fn(string $search) => User::where('email', 'like', "%{$search}%")->limit(50)->pluck('email', 'id'))
                        ->getOptionLabelUsing(fn($value): ?string => User::find($value)?->email)
                        ->getSelectedRecordUsing(fn($value) => User::find($value))
                        ->searchable(),


                    //change conten type select
                    Forms\Components\Select::make('content_type')
                        ->label('Content Type')
                        ->options([
                            'page' => 'Page',
                            'post' => 'Post',
                            'product' => 'Product',
                        ]),


                    Forms\Components\Select::make('subtype')
                        ->label('Content Subtype')
                        ->options([
                            'static' => 'Static',
                            'post' => 'Post',
                            'product' => 'Product',
                            'dynamic' => 'Dynamic',
                        ]),

                    Forms\Components\Toggle::make('is_shop')
                        ->label('Is Shop')
                        ->default(0)
                        ->helperText('This page will accept products to be added to it.')
                        ->visible(function (Forms\Get $get) {
                            return $get('content_type') === 'page';
                        })
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_home')
                        ->label('Is Homepage')
                        ->default(0)
                        ->helperText('This will be the first page of your website.')
                        ->visible(function (Forms\Get $get) {
                            return $get('content_type') === 'page';
                        })
                        ->columnSpanFull(),


                    Forms\Components\DateTimePicker::make('created_at')
                        ->label('Created At')
                        ->format('Y-m-d H:i:s')
                        ->native(false)
                        ->displayFormat('Y-m-d H:i:s')
                        ->visible(function (Forms\Get $get) {
                            return $get('id');
                        })
                        ->columnSpanFull(),

                    Forms\Components\DateTimePicker::make('updated_at')
                        ->label('Updated At')
                        ->format('Y-m-d H:i:s')
                        ->native(false)
                        ->displayFormat('Y-m-d H:i:s')
                        ->visible(function (Forms\Get $get) {
                            return $get('id');
                        })
                        ->columnSpanFull(),

                    Forms\Components\Placeholder::make('id')
                        ->label('ID')
                        ->inlineLabel(true)
                        ->content(function ($record) {
                            return $record->id;
                        })->visible(function (Forms\Get $get) {
                            return $get('id');
                        }),


                ])
        ];
    }

    public static function advancedSettingsForm(Form $form): Form
    {
        return $form
            ->schema(static::advancedSettingsFormArray());
    }


    public static function getListTableColumns(): array
    {

        return [
            ImageUrlColumn::make('media_url')
                ->height(83)
                ->imageUrl(function (Model $record) {
                    return $record->mediaUrl();
                }),


            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->columnSpanFull()
                ->weight(FontWeight::Bold),

            Tables\Columns\TextColumn::make('price_display')
                ->searchable()
                ->columnSpanFull(),

            Tables\Columns\SelectColumn::make('is_active')
                ->options([
                    1 => 'Published',
                    0 => 'Unpublished',
                ]),

        ];
    }

    public static function getGridTableColumns(): array
    {

        return [

            Tables\Columns\Layout\Split::make([

                Tables\Columns\ViewColumn::make('content')
                    ->columnSpanFull()
                    ->searchable()
                    ->view('modules.content::filament.admin.content-view-column'),

                DropdownColumn::make('is_active')
                    ->searchable()
                    ->grow(false)
                    ->size('sm')
                    ->options([
                        1 => 'Published',
                        0 => 'Unpublished',
                    ])
                    ->icon(fn(string $state): string => match ($state) {
                        '0' => 'heroicon-o-clock',
                        '1' => 'heroicon-o-check',
                        default => 'heroicon-o-clock',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'warning',
                        '1' => 'success',
                        default => 'gray',
                    }),


            ])->columnSpanFull(),

        ];
    }

    public static function ___getGridTableColumns(): array
    {
        return [
            Tables\Columns\Layout\Split::make([

                ImageUrlColumn::make('media_url')
                    ->height(83)
                    ->imageUrl(function (Model $record) {


                        return $record->mediaUrl();
                    }),


                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('id')
                        ->width(50)
                        ->columnSpan('sm')->searchable(),


                    Tables\Columns\TextColumn::make('title')
                        ->searchable()
                        ->columnSpanFull()
                        ->weight(FontWeight::Bold),

                    Tables\Columns\TextColumn::make('title')
                        ->searchable()
                        ->columnSpanFull(),

                    Tables\Columns\TextColumn::make('created_at')
                        ->searchable()
                        ->columnSpanFull(),

                ]),

                Tables\Columns\TextColumn::make('price_display')
                    ->searchable()
                    ->columnSpanFull(),


                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->columnSpanFull(),

            ])
        ];
    }


    public static function table(Table $table): Table
    {

        $livewire = $table->getLivewire();

        return $table
            ->recordAction(null)
            ->recordUrl(null)
            ->paginated([10, 25, 50, 100, 250, 'all'])
            ->defaultPaginationPageOption(250)
            ->deferLoading()
            ->reorderable('position')
            ->defaultSort('position', 'asc')
            ->columns(
                $livewire->isGridLayout()
                    ? static::getGridTableColumns()
                    : static::getListTableColumns()
            )
            ->emptyState(function (Table $table) {
                $modelName = static::$model;
                return view('modules.content::filament.admin.empty-state', ['modelName' => $modelName]);

            })


//            ->contentGrid(
//                fn() => $livewire->isListLayout()
//                    ? null
//                    : [
//                        'md' => 1,
//                        'lg' => 1,
//                        'xl' => 1,
//                    ]
//            )
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('qty')
                            ->label('Quantity')
                            ->relationship('metaData', 'sku'),

                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('sku')
                            ->relationship('metaData', 'sku'),

                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('barcode')
                            ->relationship('metaData', 'barcode'),
                    ]),
            ])
            ->filtersFormWidth(MaxWidth::Medium)
            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\EditAction::make('edit')
                        ->icon('heroicon-o-pencil'),

                    Tables\Actions\Action::make('live_edit')
                        ->url(function (Content $record) {
                            return $record->link() . '?editmode=y';
                        })
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\DeleteAction::make('delete')
                        ->icon('heroicon-o-trash'),

                ])->icon('mw-dots-menu')
                    ->color(Color::Gray)
                    ->iconSize('lg')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => \Modules\Content\Filament\Admin\ContentResource\Pages\ListContents::route('/'),
            'create' => \Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent::route('/create'),
            'view' => \Modules\Content\Filament\Admin\ContentResource\Pages\ViewContent::route('/{record}'),
            'edit' => \Modules\Content\Filament\Admin\ContentResource\Pages\EditContent::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description', 'content_body', 'url'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Type' => ucfirst($record->content_type),
            'Status' => $record->is_active ? 'Published' : 'Unpublished',
        ];
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
                ->url(static::getUrl('edit', ['record' => $record])),
            Action::make('view')
                ->url($record->link()),
        ];
    }
}
