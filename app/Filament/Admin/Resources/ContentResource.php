<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContentResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Livewire\Component;
use MicroweberPackages\Filament\Forms\Components\MwSelectTemplateForPage;
use MicroweberPackages\Filament\Forms\Components\MwTitleWithSlugInput;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\User\Models\User;
use Livewire\Attributes\On;

class ContentResource extends Resource
{
    use Translatable;

    protected static ?string $model = \MicroweberPackages\Content\Models\Content::class;


    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {

        $livewire = $form->getLivewire();

        $contentType = 'page';
        $contentSubtype = 'static';

        $isShop = false;

        $categoryIds = '';
        $id = 0;

        if (
            $livewire instanceof \App\Filament\Admin\Resources\PageResource\Pages\CreatePage ||
            $livewire instanceof \App\Filament\Admin\Resources\PageResource\Pages\EditPage
        ) {
            $contentType = 'page';
            $contentSubtype = 'static';
        }

        if (
            $livewire instanceof \App\Filament\Admin\Resources\PostResource\Pages\CreatePost ||
            $livewire instanceof \App\Filament\Admin\Resources\PostResource\Pages\EditPost
        ) {
            $contentType = 'post';
            $contentSubtype = 'post';

        }

        if ($livewire instanceof \App\Filament\Admin\Resources\ProductResource\Pages\CreateProduct ||
            $livewire instanceof \App\Filament\Admin\Resources\ProductResource\Pages\EditProduct) {
            $contentType = 'product';
            $contentSubtype = 'product';
        }

        $record = $form->getRecord();
        $modelName = (morph_name($livewire->getModel()));
        $id = 0;
        if ($record) {
            $id = $record->id;
        }

        $menuIds = [];
        if ($record) {
            $menuIds = $record->menuIds;

        }


        $menus = get_menus();
        $menusCheckboxes = [];
        $mediaUrls = [];
        // $selectedMenus = [];

        if ($menus) {
            foreach ($menus as $menu) {
                $menusCheckboxes[$menu['id']] = $menu['title'];

                if (is_in_menu($menu['id'], $id)) {
                    //      $selectedMenus[$menu['id']] = $menu['title'];
                }
            }
        }

        if (!empty($menuIds)) {
            //    $record->add_content_to_menu = $selectedMenus;

        }


        $templates = site_templates();
        $active_site_template = template_name();


        $mediaFiles = [];
        $parent = 0;
        if ($record) {
            $parent = $record->parent;
            $category_ids_array = $record->getCategoryIdsAttribute();
            if (!empty($category_ids_array)) {
                $categoryIds = implode(',', $category_ids_array);
            }
            //  $mediaFiles = $record->getMediaFilesAttribute();

        }
        //dd($mediaFiles);

$componentName = $livewire->getName();
        $sessionId = session()->getId();
        //dd($category_ids);
//        $livewire->selectedMenus = $selectedMenus;
//        $livewire->menus = $menus;
//        $livewire->existingMenus = $menusCheckboxes;
        // $livewire->menuIds = $menuIds;

        //  $menuIds = $record->menuIds;
        $site_url = site_url();
        return $form
            ->schema([

                Forms\Components\Section::make('Images')
                    ->schema([


//                        MwImagesForModel::make('mediaIds'),

                        Forms\Components\Livewire::make('admin-list-media-for-model', [
                            'relType' => $modelName,
                            'relId' => $id,
                            'mediaFiles' => $mediaFiles,
                            'mediaUrls' => $mediaUrls,

                            'parentComponentName' => $componentName,

                            'sessionId' => $sessionId

                        ])->afterStateUpdated(function (string $operation, $state, Forms\Set $set, Forms\Get $get, ?Model $record) {

                        }) ,
//                        Forms\Components\ViewField::make('mediaIds')
//                            ->view('media::admin.filament.forms.attach-media-to-model')
//
//                            ->viewData([
//
//                                'relType' => $modelName,
//                                'relId' => $id,
//                                'sessionId' => $sessionId,
//
//
//
////                                'mediaFiles' => $mediaFiles,
////                                'mediaUrls' => $mediaUrls,
////                                'sessionId' => $sessionId,
////                                'contentId' => $id,
//
//                            ])


                    ])
                    ->collapsible(),


//                Forms\Components\CheckboxList::make('belongsToMenus')
//                ->relationship(titleAttribute: 'title'),


//                Forms\Components\Tabs::make('Tabs')
//                    ->tabs([
//
//                        Forms\Components\Tabs\Tab::make('Details')
//                            ->schema([
//
                Forms\Components\Group::make([

                    Forms\Components\Group::make()
                        ->schema([

                            Forms\Components\TextInput::make('id')
                                ->default($id)
                                ->hidden(),
                            Forms\Components\TextInput::make('session_id')
                                ->default($sessionId)
                            ,
                            Forms\Components\TextInput::make('mediaIds')

                            ,

                            Forms\Components\TextInput::make('content_type')
                                ->default($contentType)
                                ->hidden(),
                            Forms\Components\TextInput::make('categoryIds')
                                ->default($categoryIds)
                                ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set, ?array $state) use ($categoryIds) {

                                    if ($categoryIds) {
                                        if (!is_array($categoryIds)) {
                                            $categoryIds = explode(',', $categoryIds);
                                        }
                                        $set('categoryIds', $categoryIds);
                                    } else {
                                        $set('categoryIds', []);
                                    }
                                })
                            ,

                            Forms\Components\TextInput::make('menuIds')
                                ->default($menuIds)
                                ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set, ?array $state) use ($menuIds) {

                                    if ($menuIds) {
                                        $set('menuIds', $menuIds);
                                    } else {
                                        $set('menuIds', []);
                                    }
                                })
                            ,
                            Forms\Components\TextInput::make('parent')
                                ->default($parent),

                            Forms\Components\TextInput::make('subtype')
                                ->default($contentSubtype)
                                ->hidden(),


                            Forms\Components\TextInput::make('is_shop')
                                ->default($contentSubtype)
                                ->hidden(),

//
//
//                            Forms\Components\FileUpload::make('image')
//                                ->image()
//                                ->imageEditor(),

                            Forms\Components\Section::make('General Information')
                                ->heading(false)
                                ->schema([

                                    MwTitleWithSlugInput::make(
                                        fieldTitle: 'title',
                                        fieldSlug: 'url',
                                        urlHost: $site_url,
                                        titleLabel: 'Title',
                                        slugLabel: 'Link:',

                                    )
                                        ->columnSpanFull(),


//                                    Forms\Components\TextInput::make('title')
//                                        ->required()
//                                        ->maxLength(255)
//                                        ->columnSpanFull()
//                                        ->live(onBlur: true)
//                                        ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
//                                            if ($operation !== 'create') {
//                                                return;
//                                            }
//
//                                            $set('url', Str::slug($state));
//                                        }),
//
//                                    Forms\Components\TextInput::make('url')
//                                        //     ->disabled()
//                                        ->dehydrated()
//                                        ->required()
//                                        ->maxLength(255)
//                                        ->columnSpanFull()
//                                        ->unique(Content::class, 'url', ignoreRecord: true),

                                    Forms\Components\MarkdownEditor::make('description')
                                        ->columnSpan('full'),
                                ])
                                ->columnSpanFull()
                                ->columns(2),


                            Forms\Components\Section::make('Pricing')
                                ->schema([

                                    Forms\Components\TextInput::make('price')
                                        ->numeric()
                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                        ->required(),

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
                                    ])->columnSpanFull()->visible(function (Forms\Get $get) {
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

//                                                    Forms\Components\TextInput::make('content_data.weight')
//                                                        ->numeric()
//                                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
//                                                        ->helperText('Used to calculate shipping rates at checkout and label prices during fulfillment.')
//                                                        ->label('Weight')
//                                                        ->default(0),

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
                            Forms\Components\Section::make('Visible')
                                ->schema([
                                    Forms\Components\ToggleButtons::make('is_active')
                                        ->label(false)
                                        ->options([
                                            1 => 'Published',
                                            0 => 'Unpublished',
                                        ])
                                        ->default(true),

                                ]),


                            Forms\Components\Section::make('Parent page')
                                ->schema([
                                    Forms\Components\View::make('filament-forms::admin.mw-tree')->viewData([
                                        'selectedPage' => $parent,
                                        'selectedCategories' => $categoryIds
                                    ]),
                                ]),


                            Forms\Components\Section::make('Category')
                                ->schema([

                                ]),

                            Forms\Components\Section::make('Tags')
                                ->schema([
                                    Forms\Components\TagsInput::make('tags')
                                        ->label(false)
                                        ->helperText('Separate using commas or Enter key.')
                                        ->placeholder('Add a tag'),
                                ]),

                            Forms\Components\Section::make('Menus')
                                ->schema([

                                    Forms\Components\CheckboxList::make('menuIds')
                                        //   ->hiddenLabel(true)
//                                        ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set, ?array $state) use ($selectedMenus) {
//
//                                            if ($selectedMenus) {
//                                                $set('menuIds', $selectedMenus);
//                                            } else {
//                                                $set('menuIds', []);
//                                            }
//                                        })
//                                        ->afterStateUpdated(function (string $operation, $state, Forms\Set $set, ?Model $record) {
//                                            $items = $state;
//
//                                            if (is_array($items) and !empty($items)) {
//                                                $items = array_filter($items);
//                                                $items = array_values($items);
//                                                $set('menuIds', $items);
//                                            } else {
//                                                $set('menuIds', []);
//                                            }
//
//                                            if ($items) {
//                                                //$record->setMenuIds($items);
//                                            } else {
//                                                //$record->setMenuIds([]);
//
//                                            }
//                                        })
                                        ->options($menusCheckboxes),
                                ])
                        ])->columnSpan(['lg' => 1]),


                ])->columns(3)->columnSpanFull(),

//                            ])->columns(3),


//                Forms\Components\Tabs\Tab::make('Custom Fields')
//                    ->schema([
//                        Forms\Components\Section::make('Custom Fields')
//                            ->heading(false)
//                        ->schema([
//                            Forms\Components\View::make('custom_field::livewire.filament.admin.show-list-custom-fields')
//                                ->columnSpanFull(),
//                        ]),
//                    ]),
//                Forms\Components\Tabs\Tab::make('SEO')
//                    ->schema([
//
//
//
//                        ]),
//
//                    ]),
//                Forms\Components\Tabs\Tab::make('Advanced')
//                    ->schema([
//
//                    ]),
//                    ])->columnSpanFull(),

            ]);
    }

    public static function seoForm(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }


    public static function advancedSettingsForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Advanced Settings')
                    ->description('You can configure advanced settings for this content')
                    ->schema([

                        Forms\Components\TextInput::make('original_link')
                            ->label('Redirect URL')
                            ->helperText('Redirect to another URL when this content is accessed')
                            ->columnSpanFull(),


                        Forms\Components\Toggle::make('require_login')
                            ->label('Require login')
                            ->helperText('Require user to be logged in to view this content')
                            ->columnSpanFull(),


                        Forms\Components\Select::make('created_by')
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


                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Created At')
//                            ->date(function ($record) {
//
//                                return isset($record->updated_at) ? $record->created_at->format('Y-m-d H:i:s') : null;
//                            })
                            //  ->format('Y-m-d H:i:s')
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('updated_at')
                            ->label('Updated At')
                            //                            ->date(function ($record) {
//                                return isset($record->updated_at) ? $record->updated_at : null;
//                            })
                            //     ->format('Y-m-d H:i:s')
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
            ]);
    }


    public static function getListTableColumns(): array
    {

        return [
            ImageUrlColumn::make('media_url')
                ->height(83)
                ->imageUrl(function (Product $product) {
                    return $product->mediaUrl();
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

                ImageUrlColumn::make('media_url')
                    ->height(83)
                    ->imageUrl(function (Content $product) {
                        return $product->mediaUrl();
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

                Tables\Columns\SelectColumn::make('is_active')
                    ->options([
                        1 => 'Published',
                        0 => 'Unpublished',
                    ]),


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
            ->deferLoading()
            ->reorderable('position')
            ->columns(
                $livewire->isGridLayout()
                    ? static::getGridTableColumns()
                    : static::getListTableColumns()
            )
            ->contentGrid(
                fn() => $livewire->isListLayout()
                    ? null
                    : [
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                    ]
            )
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
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'view' => Pages\ViewContent::route('/{record}'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
