<?php

namespace Modules\Category\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Filament\Admin\Resources\CategoryResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\GlobalSearch\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\Filament\Forms\Components\MwTree;
use MicroweberPackages\Multilanguage\Filament\Resources\Concerns\TranslatableResource;
use Modules\Category\Models\Category;
use Modules\Content\Models\Content;

class CategoryResource extends Resource
{
    use TranslatableResource;

    protected static ?string $model = Category::class;

    protected static ?string $recordTitleAttribute = 'title';

    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Website';
    protected static ?int $navigationSort = 3;

    public static function formArray($params = [])
    {
        $selectedPage = 0;
        $selectedCategories = [];
        $id = null;

        if (isset($params['record'])) {
            $record = $params['record'];
            if ($record->parent_id) {
                $selectedCategories[] = $record->parent_id;
            } elseif ($record->rel_id) {
                $selectedPage = $record->rel_id;
            }
            $id = $record->id;
        }

        return [
            Forms\Components\Tabs::make('Category Details')
                ->columnSpanFull()
                ->tabs([
                    // General Tab
                    Forms\Components\Tabs\Tab::make('Category Details')
                        ->schema([

                            Forms\Components\Hidden::make('id')->default($id),
                            Forms\Components\Hidden::make('parent_id')->default(0),
                            Forms\Components\Hidden::make('rel_type'),
                            Forms\Components\Hidden::make('rel_id'),

                            Forms\Components\TextInput::make('title')
                                ->label('Title')
                                ->required(),

                            Forms\Components\Textarea::make('description')
                                ->label('Description'),

                            MwTree::make('mw_parent_page_and_category_state')
                                ->live()
                                ->extraFieldWrapperAttributes([
                                    'class' => 'mw-tree-wrapper',
                                ])->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'xl' => 1,
                                    '2xl' => 1,
                                ])
                                ->required(function (Forms\Get $get) {
                                    $required = true;

                                    if ($get('parent_id')) {
                                        $required = false;
                                    }
                                    if ($get('rel_id')) {
                                        $required = false;
                                    }

                                    return $required;
                                })
                                ->label('Choose Parent Page or Category')
                                ->viewData([
                                    'singleSelect' => true,
                                    'selectedPage' => $selectedPage,
                                    'selectedCategories' => $selectedCategories,
                                ])
                                ->default([])
                                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?array $old, ?array $state) {
                                    if (!$state) {
                                        $set('parent_id', '');
                                        $set('rel_type', '');
                                        $set('rel_id', '');
                                    }
                                    if ($state) {
                                        foreach ($state as $item) {
                                            if (isset($item['type']) && $item['type'] === 'page') {
                                                $set('rel_type', morph_name(Content::class));
                                                $set('rel_id', $item['id']);
                                                $set('parent_id', '');
                                            }
                                            if (isset($item['type']) && $item['type'] === 'category') {
                                                $set('parent_id', $item['id']);
                                                $set('rel_type', '');
                                                $set('rel_id', '');
                                            }
                                        }
                                    }
                                }),

                        ]),

                    // Advanced Tab
                    Forms\Components\Tabs\Tab::make('Advanced')
                        ->schema([
                            Forms\Components\TextInput::make('url')
                                ->label('Url'),
                            MwMediaBrowser::make('mediaIds')
                                ->label('Category Images'),
                            Forms\Components\TextInput::make('category_meta_title')
                                ->label('Meta Title'),
                            Forms\Components\Textarea::make('category_meta_description')
                                ->label('Meta Description'),
                        ]),
                ]),
        ];
    }

    public static function form(Form $form): Form
    {
        $params = [];
        $record = $form->getRecord();

        if ($record) {
            $params['record'] = $record;
        }

        return $form->schema(static::formArray($params));
    }


    public static function table(Table $table): Table
    {
        //list in handled in ListCategories.php

        $table
            ->columns([

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('category_meta_title')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('category_meta_description')
                    ->searchable()
                    ->hidden(),
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


        return $table;
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
            'index' => \Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\ListCategories::route('/'),
            'create' => \Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\CreateCategory::route('/create'),
            'edit' => \Modules\Category\Filament\Admin\Resources\CategoryResource\Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description', 'url', 'category_meta_title', 'category_meta_description'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->parent_id) {
            $parentCategory = Category::find($record->parent_id);
            if ($parentCategory) {
                $details['Parent Category'] = $parentCategory->title;
            }
        }

        if ($record->description) {
            $details['Description'] = Str::limit($record->description, 50);
        }

        return $details;
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
                ->url(static::getUrl('edit', ['record' => $record])),
            Action::make('view')
                ->url(fn () => $record->url ? url($record->url) : null)
                ->visible(fn () => $record->url),
        ];
    }
}
