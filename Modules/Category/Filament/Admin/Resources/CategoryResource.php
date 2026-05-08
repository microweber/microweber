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

        // Allow preselecting parent via query string (?parent_page_id=X or ?parent_category_id=X)
        if (!$selectedPage && empty($selectedCategories)) {
            $parentPageId = request()->get('parent_page_id');
            $parentCategoryId = request()->get('parent_category_id');
            if ($parentPageId) {
                $selectedPage = (int) $parentPageId;
            } elseif ($parentCategoryId) {
                $selectedCategories[] = (int) $parentCategoryId;
            }
        }

        $parentTreeSection = Forms\Components\Section::make('Parent page')
            ->icon('heroicon-m-folder-open')
            ->columns(1)
            ->schema([
                MwTree::make('mw_parent_page_and_category_state')
                    ->columnSpanFull()
                    ->hiddenLabel()
                    ->inlineLabel(false)
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
            ]);

        $tabs = Tabs::make('Category Details')
            ->contained()
            ->columnSpanFull()
            ->tabs([
                Tabs\Tab::make('Category Details')
                    ->icon('heroicon-o-folder')
                    ->schema([
                        Forms\Components\Hidden::make('id')->default($id),
                        Forms\Components\Hidden::make('parent_id')
                            ->default($selectedCategories[0] ?? 0),
                        Forms\Components\Hidden::make('rel_type')
                            ->default($selectedPage ? morph_name(Content::class) : null),
                        Forms\Components\Hidden::make('rel_id')
                            ->default($selectedPage ?: null),

                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Description'),
                    ]),

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

                Tabs\Tab::make('Advanced')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        MwMediaBrowser::make('mediaIds')
                            ->label('Category Images'),
                    ]),
            ]);

        return [
            Group::make([
                Group::make()
                    ->schema([$tabs])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([$parentTreeSection])
                    ->columnSpan(['lg' => 1]),
            ])->columns(3)->columnSpanFull(),
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

    /**
     * Lean live-edit variant — no tabs, no SEO, no Advanced media
     * picker. Mirrors ContentResource::formArrayCompact() so the
     * +ADD toolbar's "New Category" modal feels identical to the
     * other content-type modals (lightweight, focused on rapid
     * inline create). task-2026-05-04-e0fe54.
     */
    public static function formArrayCompact($params = [])
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

        if (!$selectedPage && empty($selectedCategories)) {
            $parentPageId = request()->get('parent_page_id');
            $parentCategoryId = request()->get('parent_category_id');
            if ($parentPageId) {
                $selectedPage = (int) $parentPageId;
            } elseif ($parentCategoryId) {
                $selectedCategories[] = (int) $parentCategoryId;
            }
        }

        // task-2026-05-04-26c52a — replace mw.tree with a native
        // Filament Select for the live-edit category compact form.
        // mw.tree is heavy (100+ items dumped into the DOM, custom
        // Livewire view, search box, expand/collapse chevrons) and
        // doesn't fit the lean inline-create UX. A searchable
        // Filament Select with "Page: …" / "Category: …" labelled
        // options is faster and reads as a normal form field. The
        // full admin form (`form()` → `formArray()`) keeps the
        // mw.tree picker for power users who want the visual tree.
        $parentSelectInitial = '';
        if ($selectedPage) {
            $parentSelectInitial = 'page:' . $selectedPage;
        } elseif (!empty($selectedCategories)) {
            $parentSelectInitial = 'category:' . $selectedCategories[0];
        }

        $parentSelectOptions = static::buildCompactParentSelectOptions();

        // task-2026-05-04-fc971b — `->native()` makes Filament
        // use a plain HTML <select> instead of the searchable
        // Choices.js panel. The native browser dropdown renders
        // OUTSIDE the modal's stacking context entirely, so it
        // can't be clipped by the modal-content's `overflow:
        // auto` (which is required for the scrollable body fix
        // from task-b7eee8). Loses in-place search but the
        // option list is short enough (pages + categories,
        // capped at 500 each) that the native browser's
        // type-ahead works fine.
        // task-2026-05-04-1e4af3 (P1-5): Parent is now OPTIONAL.
        // Auto-default to the page being edited in live-edit
        // (same convention as posts/products: see
        // AdminLiveEditPage::generateAction's save handler that
        // links new content to $currentPageId). Sarah-the-baker
        // doesn't know what "parent of a category" means and
        // shouldn't be forced to pick from a 30-item dropdown
        // upfront. If she leaves it blank, we default to the
        // current canvas page so the category still appears
        // under that listing.
        if ($parentSelectInitial === '') {
            // task-2026-05-05-e78299 — accept an explicit currentPageId
            // from the caller (AdminLiveEditPage passes the live-edit
            // canvas page id resolved from `?url=`). The previous
            // request()->query / Referer fallbacks were always empty
            // when this form is built during the Livewire
            // `/livewire/update` POST that mounts the action — that
            // request's query string is empty and the Referer is
            // /admin/live-edit (no parent_page_id). Without an explicit
            // pass-through the dropdown defaulted to "Select an option"
            // and Sarah-the-baker had to scroll the entire site
            // hierarchy to find the page she was already on.
            $currentPageId = (int) ($params['currentPageId'] ?? 0);
            if (! $currentPageId) {
                $currentPageId = (int) request()->query('parent_page_id', 0);
            }
            if (! $currentPageId) {
                $referer = request()->headers->get('referer');
                if ($referer && str_contains($referer, 'parent_page_id=')) {
                    parse_str(parse_url($referer, PHP_URL_QUERY) ?: '', $q);
                    $currentPageId = (int) ($q['parent_page_id'] ?? 0);
                }
            }
            if ($currentPageId && isset($parentSelectOptions['page:' . $currentPageId])) {
                $parentSelectInitial = 'page:' . $currentPageId;
            }
        }

        $parentTreeSection = Forms\Components\Select::make('mw_parent_select')
            ->label('Parent page or category (optional)')
            ->options($parentSelectOptions)
            ->default($parentSelectInitial)
            ->native(true)
            ->live()
            ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                if (!$state || !str_contains($state, ':')) {
                    $set('parent_id', '');
                    $set('rel_type', '');
                    $set('rel_id', '');
                    return;
                }
                [$type, $id] = explode(':', $state, 2);
                if ($type === 'page') {
                    $set('rel_type', morph_name(Content::class));
                    $set('rel_id', $id);
                    $set('parent_id', '');
                } elseif ($type === 'category') {
                    $set('parent_id', $id);
                    $set('rel_type', '');
                    $set('rel_id', '');
                }
            })
            ->columnSpanFull();

        // Super-minimalistic schema (task-2026-05-04-2199df):
        //   UPFRONT: Title (required, autofocus) + Parent picker
        //   IN ACCORDION (collapsed "More options"): Description.
        // Categories don't have a media browser of their own, so
        // the upfront stack is just title + parent — Description
        // moves below the fold for the lean default flow.
        return [
            Group::make([
                Forms\Components\Hidden::make('id')->default($id),
                Forms\Components\Hidden::make('parent_id')
                    ->default($selectedCategories[0] ?? 0),
                Forms\Components\Hidden::make('rel_type')
                    ->default($selectedPage ? morph_name(Content::class) : null),
                Forms\Components\Hidden::make('rel_id')
                    ->default($selectedPage ?: null),

                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->hiddenLabel()
                    ->placeholder("What's the category name?")
                    ->extraInputAttributes(['class' => 'mw-fb-title-input'])
                    ->rules(['required'])
                    ->markAsRequired()
                    ->autofocus()
                    ->extraFieldWrapperAttributes(['class' => 'mw-fb-title-wrap'])
                    ->columnSpanFull(),

                // Description + Parent picker move into accordion
                // — task-2026-05-04-1e4af3 (P1-5): the parent is
                // optional and auto-defaults to the canvas page,
                // so it doesn't need to demand the user's
                // attention upfront. Title-only save is a
                // "very easy" path now.
                Forms\Components\Section::make('More options')
                    ->icon('heroicon-m-adjustments-horizontal')
                    ->collapsible()
                    ->collapsed()
                    ->extraAttributes(['class' => 'mw-fb-more-options'])
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                        $parentTreeSection,
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ])->columns(1)->columnSpanFull(),
        ];
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
                // TASK-020 / TICKET-K / AI-38 (cycle-60 2026-05-08):
                // record-contextual label + tooltip so screen readers
                // announce `Edit "Hardware"` and the hover tooltip
                // matches. Anchor on title; fall back to "#{id}" for
                // categories without a title set.
                Tables\Actions\EditAction::make()
                    ->label(fn ($record): string => 'Edit "' . trim((string) ($record->title ?? ('#' . ($record->id ?? '')))) . '"')
                    ->tooltip(fn ($record): string => 'Edit "' . trim((string) ($record->title ?? ('#' . ($record->id ?? '')))) . '"'),
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
        return $record->title ?? 'Category #' . $record->id;
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

    /**
     * Build the option list for the live-edit category compact
     * form's parent picker. Keys are encoded "page:{id}" /
     * "category:{id}" so the same Select can target both targets;
     * the afterStateUpdated callback splits the prefix and writes
     * to the right hidden field. Cached statically per request.
     * task-2026-05-04-26c52a.
     */
    protected static function buildCompactParentSelectOptions(): array
    {
        static $cached = null;
        if ($cached !== null) return $cached;

        $options = [];

        $pages = Content::query()
            ->whereIn('content_type', ['page'])
            ->where('is_deleted', '!=', 1)
            ->orderBy('title')
            ->limit(500)
            ->get(['id', 'title']);
        foreach ($pages as $p) {
            $options['page:' . $p->id] = 'Page: ' . ($p->title ?: ('#' . $p->id));
        }

        $categories = Category::query()
            ->orderBy('title')
            ->limit(500)
            ->get(['id', 'title']);
        foreach ($categories as $c) {
            $options['category:' . $c->id] = 'Category: ' . ($c->title ?: ('#' . $c->id));
        }

        return $cached = $options;
    }
}
