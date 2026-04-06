<?php

namespace Modules\Category\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Filament\Admin\Resources\CategoryResource\RelationManagers;
use Filament\Forms;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\Filament\Forms\Components\MwTree;
use MicroweberPackages\Multilanguage\Filament\Resources\Concerns\TranslatableResource;
use Filament\Schemas\Components\Group;
use Modules\Category\Models\Category;
use Modules\Content\Models\Content;

class CategoryResource extends Resource
{
    use TranslatableResource;

    protected static ?string $model = Category::class;

    protected static ?string $recordTitleAttribute = 'title';

    //protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Website';
    protected static ?int $navigationSort = 2;

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
            Group::make()
                ->schema([
                    Tabs::make('Category Details')
                        ->contained()
                        ->columnSpanFull()
                        ->tabs([
                            // General Tab
                            Tabs\Tab::make('Category Details')
                                ->icon('heroicon-o-folder')
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
                                ]),

                            // SEO Tab
                            Tabs\Tab::make('SEO')
                                ->icon('heroicon-o-magnifying-glass')
                                ->schema([
                                    Forms\Components\TextInput::make('url')
                                        ->label('Url'),
                                    Forms\Components\TextInput::make('category_meta_title')
                                        ->label('Meta Title'),
                                    Forms\Components\Textarea::make('category_meta_description')
                                        ->label('Meta Description'),
                                ]),

                            // Advanced Tab
                            Tabs\Tab::make('Advanced')
                                ->icon('heroicon-o-cog-6-tooth')
                                ->schema([
                                    MwMediaBrowser::make('mediaIds')
                                        ->label('Category Images'),
                                ]),
                        ]),
                ])
                ->columnSpan(['lg' => 2]),

            Group::make()
                ->schema([
                    Forms\Components\Section::make('Parent Page or Category')
                        ->icon('heroicon-m-folder-open')
                        ->schema([
                            MwTree::make('mw_parent_page_and_category_state')
                                ->live()
                                ->extraFieldWrapperAttributes([
                                    'class' => 'mw-tree-wrapper',
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
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        $params = [];
        $record = $schema->getRecord();

        if ($record) {
            $params['record'] = $record;
        }

        return $schema->schema(static::formArray($params))->columns(3);
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
