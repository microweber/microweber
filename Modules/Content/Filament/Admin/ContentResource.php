<?php

namespace Modules\Content\Filament\Admin;

use BobiMicroweber\FilamentDropdownColumn\Columns\DropdownColumn;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width as MaxWidth;
use Filament\Tables;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\Filament\Forms\Components\MwSelectTemplateForPage;
use MicroweberPackages\Filament\Forms\Components\MwTitleWithSlugInput;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Multilanguage\Filament\Resources\Concerns\TranslatableResource;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use Modules\Media\Models\Media;
use Modules\Page\Models\Page;
use Modules\Post\Models\Post;
use SolutionForest\FilamentTranslateField\Facades\FilamentTranslateField;
use MicroweberPackages\Multilanguage\Forms\Actions\TranslateFieldAction;

class ContentResource extends Resource
{
    use TranslatableResource;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $model = \Modules\Content\Models\Content::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Website';

    protected static bool $shouldRegisterNavigation = false;

    /*
     * AI-133 / SEC-02 (cycle-115 2026-05-09): Filament resource-level
     * access control. Pre-fix, ContentResource (and the
     * ProductResource + PostResource subclasses) had NO canAccess /
     * canEdit / canDelete / canCreate overrides — Filament v5
     * defaults to "any user with admin-panel access can do
     * everything", which combined with role-based menu hiding
     * (CSS-only) creates an IDOR vector: a non-admin user with
     * admin-panel access can navigate directly to
     * /admin/pages|posts|products/<id>/edit and modify content even
     * if the menu item is hidden in their role.
     *
     * Each method below gates on `user_can_access(...)` (same gate
     * used by the topbar Live-Edit chip per cycle-97 / AI-86), so
     * server-side access control matches the menu-hiding state.
     * IDOR is closed because the route handler short-circuits on
     * canAccess() = false, regardless of menu visibility.
     *
     * Subclasses (ProductResource / PostResource) inherit these
     * defaults automatically. They CAN override per-resource (e.g.
     * to require a finer-grained `module.product.edit` permission)
     * but the safe default is the broad `module.content.edit` gate.
     */

    public static function canAccess(): bool
    {
        return function_exists('user_can_access')
            && user_can_access('module.' . static::getContentTypeForAccess() . '.view');
    }

    public static function canCreate(): bool
    {
        return function_exists('user_can_access')
            && user_can_access('module.' . static::getContentTypeForAccess() . '.create');
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return function_exists('user_can_access')
            && user_can_access('module.' . static::getContentTypeForAccess() . '.edit');
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return function_exists('user_can_access')
            && user_can_access('module.' . static::getContentTypeForAccess() . '.delete');
    }

    public static function canDeleteAny(): bool
    {
        return function_exists('user_can_access')
            && user_can_access('module.' . static::getContentTypeForAccess() . '.delete');
    }

    /**
     * AI-133 / SEC-02: derive the permission key from the resource's
     * static $contentType so subclasses (ProductResource / PostResource)
     * automatically gate on `module.product.*` / `module.post.*`
     * without needing to override every can*() method.
     */
    protected static function getContentTypeForAccess(): string
    {
        // Subclasses set $contentType (e.g. 'product', 'post', 'page').
        // Fall back to 'content' for the bare ContentResource.
        if (property_exists(static::class, 'contentType')) {
            return (string) (static::$contentType ?? 'content');
        }
        return 'content';
    }


    public static function formArray($params = [])
    {
        $id = $params['id'] ?? null;
        $isMultilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();
        $relType = \Modules\Content\Models\Content::class;
        $relId = $id;

        static $mediaIdsCache = [];
        $cacheKey = $relId ?? '__null__';
        if (!isset($mediaIdsCache[$cacheKey])) {
            $mediaIdsCache[$cacheKey] = Media::query()
                ->where('rel_type', $relType)
                ->where('rel_id', $relId)
                ->orderBy('position', 'asc')
                ->pluck('id')->toArray();
        }
        $mediaIds = $mediaIdsCache[$cacheKey];

        $contentType = static::resolveContentType($params);
        $contentSubtype = $params['contentSubtype'] ?? (isset(static::$subType) ? static::$subType : 'static');
        $sessionId = session()->getId();
        $active_site_template_default = static::resolveDefaultTemplate();
        [$firstBlogId, $firstShopId] = static::resolveParentPages($contentType);

        $mainForm = [
            Schemas\Components\Group::make([
                Schemas\Components\Group::make()
                    ->schema([
                        ...static::hiddenFieldsSchema($id, $sessionId, $contentType, $contentSubtype, $isMultilanguageEnabled, $active_site_template_default),
                        static::generalInformationSection(),
                        static::mediaSection($relType, $relId, $mediaIds),
                        static::pricingSection(),
                    ])->columnSpan(['lg' => 2]),

                Schemas\Components\Group::make()
                    ->schema([
                        static::publishedSection(),
                        static::parentPageSection($firstBlogId, $firstShopId),
                        static::tagsSection(),
                        static::menusSection(),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3)->columnSpanFull(),
        ];

        return static::contentTabsSchema($mainForm);
    }

    /**
     * Lean form schema used by the live-edit Add Content modal
     * (toolbar +ADD and per-module Items-list New/Edit). The
     * full `formArray()` ships ~15 tabs + 4 sidebar sections —
     * useful at /admin/content/{id}/edit, but a wall-of-fields
     * inside a 1024px modal where the user is in flow editing
     * the canvas. This variant keeps only the essentials for
     * "create now, refine later":
     *   - Title (required, autofocus)
     *   - Content body / Excerpt (post only)
     *   - Permalink (collapsed, optional override)
     *   - Published toggle
     *   - Parent page picker
     *   - Pricing (product only)
     *   - All hidden state fields the save handler needs
     *
     * Excluded vs full form: Media browser, Tags, Menus, the
     * Template / Product Details / Variants / Custom Fields /
     * SEO / Advanced tabs. Power users can still open the full
     * admin form via Edit content from the table.
     *
     * task-2026-05-04-1d68c7.
     */
    public static function formArrayCompact($params = [])
    {
        $id = $params['id'] ?? null;
        $isMultilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();
        $relType = \Modules\Content\Models\Content::class;
        $relId = $id;

        static $compactMediaIdsCache = [];
        $cacheKey = $relId ?? '__null__';
        if (!isset($compactMediaIdsCache[$cacheKey])) {
            $compactMediaIdsCache[$cacheKey] = Media::query()
                ->where('rel_type', $relType)
                ->where('rel_id', $relId)
                ->orderBy('position', 'asc')
                ->pluck('id')->toArray();
        }
        $mediaIds = $compactMediaIdsCache[$cacheKey];

        $contentType = static::resolveContentType($params);
        $contentSubtype = $params['contentSubtype'] ?? (isset(static::$subType) ? static::$subType : 'static');
        $sessionId = session()->getId();
        $active_site_template_default = static::resolveDefaultTemplate();
        [$firstBlogId, $firstShopId] = static::resolveParentPages($contentType);

        // Super-minimalistic live-edit schema (task-2026-05-04-2199df):
        //   UPFRONT (visible immediately):
        //     - Title (required, autofocus)
        //     - Picture (Media browser)
        //     - Parent page (collapsed)
        //   IN ACCORDION (collapsed "More options" section):
        //     - Content body (post only) + Excerpt (post only)
        //     - Pricing (product only)
        //     - Published toggle + Publish date
        // The lean shape matches user's request: title + picture +
        // parent visible, everything else one click away. Power
        // users can hit "Open in admin" for the full form.
        return [
            Schemas\Components\Group::make([
                ...static::hiddenFieldsSchema($id, $sessionId, $contentType, $contentSubtype, $isMultilanguageEnabled, $active_site_template_default),

                static::compactTitleOnlySection(),
                // task-2026-05-22-1f0503 — "Cover image" collapsed accordion.
                // The MwMediaBrowser tile (~200px) was shown upfront, pushing
                // Template + Layout + iframe off-screen on the Create page modal.
                // Wrapped in a collapsible Section (collapsed by default, same
                // pattern as "More options") so the primary path (Title →
                // Template → Layout) is always above the fold.
                Schemas\Components\Section::make(__('Cover image'))
                    ->icon('heroicon-m-photo')
                    ->collapsible()
                    ->collapsed()
                    ->extraAttributes(['class' => 'mw-fb-cover-image-accordion'])
                    ->schema([
                        static::mediaSection($relType, $relId, $mediaIds)
                            ->heading(null)
                            ->icon(null)
                            ->extraAttributes(['class' => 'mw-fb-media-section']),
                    ])
                    ->columnSpanFull(),

                // Pricing kept VISIBLE upfront for products —
                // user explicitly asked: "on add new product in
                // live edit the price must be visible not hidden
                // in More settings" (task-2026-05-04-26c52a). The
                // pricingSection() helper carries its own
                // ->visible() rule (`content_type === 'product'`)
                // so it absents itself for posts/pages without
                // adding chrome.
                static::pricingSection()
                    ->columnSpanFull(),

                // Body + Excerpt VISIBLE upfront for posts and
                // products. Customer-persona testing showed that
                // saving a post with body collapsed inside More
                // options produced a publicly visible empty post
                // (just title + footer) — Sarah's "did it work?"
                // moment broke. Move the writing surface upfront
                // so a one-line save still produces a real-looking
                // post page. Pages have their own body editor
                // inside the Page setup section above, so this
                // group is hidden for pages to avoid duplicating.
                // task-2026-05-04-1e4af3.
                Schemas\Components\Section::make('Body')
                    ->heading(null)
                    ->extraAttributes(['class' => 'mw-fb-body-section'])
                    ->schema([
                        static::compactBodyAndExcerptGroup(),
                    ])
                    ->columnSpanFull()
                    ->visible(function (Schemas\Components\Utilities\Get $get) {
                        return $get('content_type') !== 'page';
                    }),

                // Page-specific upfront fields
                // (task-2026-05-04-4e425c) — user reported "the
                // page fields are not available" on the Create
                // page modal. Pages need a Template picker
                // upfront because choosing a template is the key
                // page-creation decision (Blog page vs Landing
                // page vs Pricing page etc), and a Content body
                // editor for the body text. Both visibility-
                // gated to content_type === 'page' so post and
                // product flows are unchanged.
                Schemas\Components\Section::make('Page setup')
                    ->heading(null)
                    ->extraAttributes(['class' => 'mw-fb-page-setup'])
                    ->schema([
                        MwSelectTemplateForPage::make('active_site_template', 'layout_file')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content_body')
                            ->label('Page content')
                            ->columnSpanFull()
                            ->hintAction(
                                TranslateFieldAction::make('content_body')->label('')
                            ),
                    ])
                    ->columnSpanFull()
                    ->visible(function (Schemas\Components\Utilities\Get $get) {
                        return $get('content_type') === 'page';
                    }),

                // Everything else collapsed into a single "More
                // options" accordion — INCLUDING the Parent page
                // section, which user-research showed is the
                // dominant height contributor (~575px on a
                // typical viewport) and isn't needed upfront for
                // 95% of inline-create flows where the parent is
                // auto-resolved to firstBlogId / firstShopId /
                // homepage. task-2026-05-04-2cd250.
                Schemas\Components\Section::make('More options')
                    ->icon('heroicon-m-adjustments-horizontal')
                    ->collapsible()
                    ->collapsed()
                    ->extraAttributes(['class' => 'mw-fb-more-options'])
                    ->schema([
                        // Body upfront, Short summary inside the
                        // accordion (task-2026-05-05-dac8a6) — user
                        // reported the upfront Body + Short summary
                        // stack was too tall. Short summary is
                        // optional and auto-derives from body text
                        // by default, so it belongs behind the
                        // disclosure.
                        static::compactShortSummaryGroup()
                            ->columnSpanFull(),
                        static::publishedSection()
                            ->columnSpanFull(),
                        static::parentPageSection($firstBlogId, $firstShopId)
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ])->columns(1)->columnSpanFull(),
        ];
    }

    /**
     * Title-only section for the super-minimalistic live-edit
     * compact form. Other content body / excerpt fields moved
     * into the "More options" accordion below.
     * task-2026-05-04-2199df.
     */
    protected static function compactTitleOnlySection(): Schemas\Components\Section
    {
        // Facebook-style writing surface (task-2026-05-04-bfe418):
        // no label, no border around the section, big-type
        // placeholder. The placeholder is dynamic per content
        // type so "Create page" shows "What's the page title?"
        // not "What's the post about?" — task-2026-05-04-4e425c.
        return Schemas\Components\Section::make('Title')
            ->heading(null)
            ->extraAttributes(['class' => 'mw-fb-title-section'])
            ->schema([
                Forms\Components\TextInput::make('title')
                    // task-2026-05-17-04f015 / AI-817 — WCAG 3.3.2
                    // Level A requires a programmatic label for every
                    // form input. The prior `->hiddenLabel()` removed
                    // the <label> from the DOM entirely; once the
                    // placeholder disappears on focus, AT users hear
                    // just "edit text, blank" with no field name.
                    // Fix: emit Filament's canonical `<label>Title</label>`
                    // (per Filament convention) + visually-hide via
                    // the .mw-fb-title-wrap CSS sr-only rule in
                    // live-edit-module-settings.blade.php so the
                    // Facebook-style writing surface visual stays
                    // intact. Defense-in-depth aria-label below
                    // covers the input even if a future CSS change
                    // mis-hides the rendered label.
                    ->label('Title')
                    ->extraFieldWrapperAttributes(['class' => 'mw-fb-title-wrap'])
                    ->maxLength(255)
                    ->rules(['required'])
                    ->markAsRequired()
                    ->autofocus()
                    ->placeholder(function (Schemas\Components\Utilities\Get $get) {
                        $type = $get('content_type');
                        return match ($type) {
                            'page' => "What's the page title?",
                            'product' => "What's the product name?",
                            default => "What's the post about?",
                        };
                    })
                    // task-2026-05-05-66e507 (QW2) — drunk-designer
                    // audit found Title was server-side `->required()`
                    // but not announced as required to screen readers.
                    // Add aria-required="true" so AT users hear the
                    // requirement before submit. AI-817 layered an
                    // aria-label here for defense-in-depth: even if
                    // the rendered <label>Title</label> were lost,
                    // the input still announces a field name.
                    ->extraInputAttributes(['class' => 'mw-fb-title-input', 'aria-required' => 'true', 'aria-label' => 'Title'])
                    ->hintAction(
                        TranslateFieldAction::make('title')->label('')
                    )->columnSpanFull(),
            ])
            ->columnSpanFull()
            ->columns(1);
    }

    /**
     * Body + Excerpt group used inside the "More options"
     * accordion. Empty Group component when content_type doesn't
     * support these fields (page) so it cleanly absents itself.
     * task-2026-05-04-2199df.
     */
    protected static function compactBodyAndExcerptGroup(): Schemas\Components\Group
    {
        // task-2026-05-04-4e425c — Content body is shown UPFRONT
        // for pages via `Page setup` section (above the Media
        // tile), so this More-options copy is hidden for pages
        // to avoid duplicating the editor. Posts and products
        // see body here in the accordion. Excerpt is post-only.
        // task-2026-05-05-dac8a6 — Short summary moved OUT of this
        // upfront group into the More options accordion via
        // `compactShortSummaryGroup()`. The user reported the
        // upfront Body + Short summary stack made the upfront
        // surface too tall; Short summary is optional and almost
        // always auto-derived from the first lines of the post,
        // so it belongs behind a disclosure.
        return Schemas\Components\Group::make([
            // task-2026-05-04-novice — "Content body" reads as
            // database-column jargon to a first-time blogger.
            // Renamed to "Write your post here" so the label
            // doubles as the call-to-action.
            Forms\Components\RichEditor::make('content_body')
                // task-2026-05-05-1db9bd (Audit-#1) — was a single
                // label "Write your post here" that showed on the
                // Create Product modal too, which the external audit
                // flagged as a "high-impact UX bug" since the user is
                // creating a product, not a post. Branch on
                // content_type so each surface gets the right voice.
                ->label(function (Schemas\Components\Utilities\Get $get) {
                    return match ($get('content_type')) {
                        'product' => 'Product description',
                        default => 'Write your post here',
                    };
                })
                ->columnSpan('full')
                ->hintAction(
                    TranslateFieldAction::make('content_body')->label('')
                )
                ->visible(function (Schemas\Components\Utilities\Get $get) {
                    return $get('content_type') !== 'page';
                }),
        ])->columns(1)->columnSpanFull();
    }

    /**
     * Short summary (Excerpt) — placed inside the "More options"
     * accordion per task-2026-05-05-dac8a6. Post-only — pages
     * don't have a list-page excerpt slot, products use the body
     * for product description.
     */
    protected static function compactShortSummaryGroup(): Schemas\Components\Group
    {
        return Schemas\Components\Group::make([
            // task-2026-05-04-novice — "Excerpt" is publisher-
            // jargon (newspapers, WordPress); a first-time blog
            // author has never heard the word. Renamed to "Short
            // summary" — same meaning, immediately readable.
            Forms\Components\Textarea::make('description')
                ->label('Short summary')
                ->helperText('Shown in your blog list and search results. Leave empty to use the first lines of your post.')
                ->rows(3)
                ->maxLength(500)
                ->columnSpanFull()
                ->hintAction(
                    TranslateFieldAction::make('description')->label('')
                )
                ->visible(function (Schemas\Components\Utilities\Get $get) {
                    return $get('content_type') === 'post';
                }),
        ])->columns(1)->columnSpanFull();
    }

    /**
     * Trimmed general-information block for the live-edit modal:
     * Title + Content body + Excerpt only. No Permalink (URL slug)
     * subsection — task-2026-05-04-e0fe54: customers almost never
     * set the slug manually during inline create, the auto-
     * generated value is fine, and the collapsed Permalink card
     * was visually heavy inside an already-compact modal.
     */
    protected static function compactGeneralInformationSection(): Schemas\Components\Section
    {
        return Schemas\Components\Section::make('General Information')
            ->heading(null)
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    // `->rules(['required'])` instead of
                    // `->required()` so Filament still validates
                    // server-side via Livewire BUT we don't emit
                    // the native `required` HTML attribute. The
                    // browser's native "Please fill out this
                    // field" tooltip used to anchor itself to the
                    // first hidden invalid sibling (the rich-text
                    // editor's hidden textarea), pointing the
                    // arrow at the wrong field. With Filament's
                    // own inline error rendering, the message
                    // appears below the Title field where it
                    // belongs. task-2026-05-04-f575c7.
                    ->rules(['required'])
                    ->markAsRequired()
                    ->autofocus()
                    // AI-781 (task-2026-05-17-378d85) — type-aware placeholder
                    // mirrors the bigType title field at line ~356. Before the
                    // fix, every content_type saw "e.g. My first post" — wrong
                    // context for Products + Pages. Match pattern aligns with
                    // contentTitleSection() for cross-section consistency.
                    ->placeholder(function (Schemas\Components\Utilities\Get $get) {
                        return match ($get('content_type')) {
                            'page' => 'e.g. About us',
                            'product' => 'e.g. Blue cotton t-shirt',
                            default => 'e.g. My first post',
                        };
                    })
                    // TICKET-C (audit-test reply 2026-05-06): server-side
                    // ->rules(['required']) + ->markAsRequired() applies
                    // the visual asterisk but does NOT emit aria-required
                    // (because we deliberately skipped ->required() so
                    // browser native validation doesn't anchor to the
                    // wrong field — see the long comment above). Add
                    // aria-required="true" explicitly so screen readers
                    // hear the requirement before submit.
                    ->extraInputAttributes(['aria-required' => 'true'])
                    ->hintAction(
                        TranslateFieldAction::make('title')->label('')
                    )->columnSpanFull(),

                // task-2026-05-04-novice — see compactBodyAndExcerptGroup;
                // "Content body" → "Write your post here".
                // task-2026-05-05-1db9bd (Audit-#1) — branch on
                // content_type so the Create Product modal shows
                // "Product description" instead of "Write your post
                // here".
                Forms\Components\RichEditor::make('content_body')
                    ->label(function (Schemas\Components\Utilities\Get $get) {
                        return match ($get('content_type')) {
                            'product' => 'Product description',
                            default => 'Write your post here',
                        };
                    })
                    ->columnSpan('full')
                    ->hintAction(
                        TranslateFieldAction::make('content_body')->label('')
                    )
                    ->visible(function (Schemas\Components\Utilities\Get $get) {
                        return $get('content_type') !== 'page';
                    }),

                // task-2026-05-04-novice — see compactBodyAndExcerptGroup;
                // "Excerpt" → "Short summary" for novice readability.
                Forms\Components\Textarea::make('description')
                    ->label('Short summary')
                    ->helperText('Shown in your blog list and search results. Leave empty to use the first lines of your post.')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->hintAction(
                        TranslateFieldAction::make('description')->label('')
                    )
                    ->visible(function (Schemas\Components\Utilities\Get $get) {
                        return $get('content_type') === 'post';
                    }),
            ])
            ->columnSpanFull()
            ->columns(2);
    }

    protected static function resolveContentType(array $params): string
    {
        $contentType = 'page';
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
        if (isset(static::$contentType)) {
            $contentType = static::$contentType;
        }
        return $contentType;
    }

    protected static function resolveDefaultTemplate(): string
    {
        $active_site_template_default = template_name();
        $availableTemplates = site_templates();
        if ($availableTemplates) {
            $templateDirNames = array_column($availableTemplates, 'dir_name');
            if (!in_array($active_site_template_default, $templateDirNames) && !empty($templateDirNames)) {
                $active_site_template_default = $templateDirNames[0];
            }
        }
        return $active_site_template_default;
    }

    protected static function resolveParentPages(string $contentType): array
    {
        static $cachedBlogs = null;
        static $cachedShops = null;

        if ($cachedBlogs === null) {
            $cachedBlogs = app()->content_repository->getAllBlogPages() ?: [];
        }
        if ($cachedShops === null) {
            $cachedShops = app()->content_repository->getAllShopPages() ?: [];
        }

        if (empty($cachedShops) && $contentType === 'product') {
            app()->content_repository->createDefaultShopPage();
            $cachedShops = app()->content_repository->getAllShopPages() ?: [];
        }
        if (empty($cachedBlogs) && $contentType === 'post') {
            app()->content_repository->createDefaultBlogPage();
            $cachedBlogs = app()->content_repository->getAllBlogPages() ?: [];
        }

        $firstBlogId = ($cachedBlogs && !empty($cachedBlogs) && isset($cachedBlogs[0]['id'])) ? $cachedBlogs[0]['id'] : false;
        $firstShopId = ($cachedShops && !empty($cachedShops) && isset($cachedShops[0]['id'])) ? $cachedShops[0]['id'] : false;

        return [$firstBlogId, $firstShopId];
    }

    protected static function hiddenFieldsSchema($id, $sessionId, $contentType, $contentSubtype, $isMultilanguageEnabled, $active_site_template_default): array
    {
        return [
            Forms\Components\Hidden::make('id')
                ->default($id),
            Forms\Components\Hidden::make('session_id')
                ->default($sessionId),
            Forms\Components\Hidden::make('content_type')
                ->default($contentType),
            Forms\Components\Hidden::make('subtype')
                ->default($contentSubtype),
            Forms\Components\Hidden::make('multilanguage')
                ->visible($isMultilanguageEnabled),
            Forms\Components\Hidden::make('active_site_template')
                ->default($active_site_template_default)
                ->visible(function (Schemas\Components\Utilities\Get $get) {
                    return $get('content_type') == 'page';
                }),
            Forms\Components\Hidden::make('layout_file')->visible(function (Schemas\Components\Utilities\Get $get) {
                return $get('content_type') == 'page';
            }),
            Forms\Components\Hidden::make('tags')
                ->default(function (?Model $record) {
                    if ($record) {
                        return $record->getTagNamesAttribute();
                    }
                    return [];
                })->afterStateHydrated(function (?Model $record, Schemas\Components\Utilities\Get $get, Schemas\Components\Utilities\Set $set) {
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
                ->afterStateHydrated(function (?Model $record, Schemas\Components\Utilities\Get $get, Schemas\Components\Utilities\Set $set, ?array $state) {
                    if ($record) {
                        $categoryIds = $record->getCategoryIdsAttribute();
                        if (!is_array($categoryIds)) {
                            $categoryIds = explode(',', $categoryIds);
                        }
                        $set('categoryIds', $categoryIds);
                    } else {
                        $set('categoryIds', []);
                    }
                }),
            Forms\Components\Hidden::make('menuIds')
                ->default(function (?Model $record) {
                    if ($record) {
                        return $record->menuIds;
                    }
                    return [];
                })
                ->afterStateHydrated(function (Schemas\Components\Utilities\Get $get, Schemas\Components\Utilities\Set $set, ?array $state, ?Model $record) {
                    if ($record) {
                        $set('menuIds', $record->menuIds);
                    } else {
                        $set('menuIds', []);
                    }
                }),
            Forms\Components\Hidden::make('parent'),
            Forms\Components\Hidden::make('is_shop')
                ->default(0)
                ->visible(function (Schemas\Components\Utilities\Get $get) {
                    return $get('content_type') === 'page';
                }),
            Forms\Components\Hidden::make('is_home')
                ->default(0)
                ->visible(function (Schemas\Components\Utilities\Get $get) {
                    return $get('content_type') === 'page';
                }),
        ];
    }

    protected static function generalInformationSection(): Schemas\Components\Section
    {
        return Schemas\Components\Section::make('General Information')
            // The modal already shows "Create post" / "Edit post" /
            // etc. as its primary heading (see AdminLiveEditPage::
            // generateAction's modalHeading). Returning null here drops
            // the redundant inner section heading ("Add New Post" /
            // "Edit Post") so the user sees ONE heading, not two —
            // task-2026-05-02-4c1606.
            ->heading(null)
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->required()
                    // Autofocus + start typing immediately. Customer
                    // shouldn't have to hunt for the first input —
                    // task-2026-05-02-4c244f.
                    ->autofocus()
                    // Short helper — the verbose "main heading
                    // displayed on the page (recommended: 50-60
                    // characters)" was pure noise; "Title" is enough
                    // signage and the maxLength does the rest.
                    // AI-781 (task-2026-05-17-378d85) — type-aware placeholder
                    // matches compactGeneralInformationSection so the full-form
                    // Title field shows the same context-sensitive hint.
                    ->placeholder(function (Schemas\Components\Utilities\Get $get) {
                        return match ($get('content_type')) {
                            'page' => 'e.g. About us',
                            'product' => 'e.g. Blue cotton t-shirt',
                            default => 'e.g. My first post',
                        };
                    })
                    // task-2026-05-05-5e9ffc (Audit-#8) — admin
                    // full-form Title was server-side `->required()`
                    // but not announced as required to screen
                    // readers. Add aria-required="true" so AT users
                    // hear the requirement before submit. Mirrors
                    // the live-edit modal Title (task-66e507).
                    ->extraInputAttributes(['aria-required' => 'true'])
                    ->hintAction(
                        TranslateFieldAction::make('title')->label('')
                    )->columnSpanFull(),

                Forms\Components\RichEditor::make('content_body')
                    ->columnSpan('full')
                    ->hintAction(
                        TranslateFieldAction::make('content_body')->label('')
                    )
                    ->visible(function (Schemas\Components\Utilities\Get $get) {
                        return $get('content_type') !== 'page';
                    }),

                // task-2026-05-04-novice — full-form variant.
                // Renamed Excerpt → Short summary so the label
                // matches the live-edit modal a novice user sees
                // first; helper text rewritten to plain English.
                Forms\Components\Textarea::make('description')
                    ->label('Short summary')
                    ->helperText('Shown in your blog list and search results. Leave empty to use the first lines of your post.')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->hintAction(
                        TranslateFieldAction::make('description')->label('')
                    )
                    ->visible(function (Schemas\Components\Utilities\Get $get) {
                        return $get('content_type') === 'post';
                    }),

                // URL slug moved out of the top-of-form field stack
                // and into a collapsed "Permalink" section. Customers
                // almost never set this manually — auto-generated
                // from title. Power users can expand the section when
                // they need to. task-2026-05-02-4c244f.
                Schemas\Components\Section::make('Permalink')
                    ->description('URL slug for this content. Leave blank to auto-generate from the title.')
                    ->collapsed()
                    ->collapsible()
                    ->compact()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('URL slug')
                            ->maxLength(255)
                            ->placeholder('auto-generated from title')
                            ->hintAction(
                                TranslateFieldAction::make('url')->label('')
                            )->columnSpanFull(),
                    ]),
            ])
            ->columnSpanFull()
            ->columns(2);
    }

    protected static function mediaSection($relType, $relId, $mediaIds): Schemas\Components\Section
    {
        return Schemas\Components\Section::make('Media')
            ->icon('heroicon-m-photo')
            ->schema([
                MwMediaBrowser::make('mediaIds')
                    ->label('Add images')
                    ->setRelType($relType)
                    ->setRelId($relId)
                    ->default(function () use ($relType, $relId, $mediaIds) {
                        return $mediaIds;
                    })
            ]);
    }

    protected static function pricingSection(): Schemas\Components\Section
    {
        return Schemas\Components\Section::make('Pricing')
            ->icon('heroicon-m-currency-dollar')
            // task-2026-05-05-e75581 — Price + Special price are
            // related short numeric inputs. Stacking them full-width
            // wasted horizontal space in the modal and made the
            // pricing section taller than necessary. Force the
            // section into a 2-column grid so they sit side-by-side
            // (Price | Special price) on lg+ screens and stack only
            // on narrow mobile.
            ->columns(['default' => 1, 'sm' => 2])
            ->schema([
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    // Audit-test 2026-05-07 (Products audit items 2+3):
                    // server-only validation accepted negative prices
                    // and arbitrary precision (step="any") because no
                    // ->minValue / ->step was set. Add both as
                    // client-side guards in addition to the existing
                    // regex rule so iOS/Android numeric keyboards
                    // refuse to advance past invalid input.
                    ->minValue(0)
                    ->step(0.01)
                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                    // Currency prefix from store settings — drunk-
                    // designer audit #11 (task-2026-05-04-a8d5bb).
                    // The Price input was naked text in the
                    // commerce flow's headline field; a leading
                    // symbol from option_get('currency') anchors
                    // the field as a money input at first glance.
                    //
                    // task-2026-05-17-fc0b22 / AI-818 — Robustness
                    // upgrade: add `?: '$'` fallback so an unset
                    // / empty / missing-helper state still shows a
                    // visible currency anchor instead of a naked
                    // numeric input. Microweber's canonical
                    // `currency_symbol()` helper reads the shop
                    // currency option; the `$` fallback only fires
                    // when the helper is unavailable OR returns an
                    // empty string. Non-USD shops keep their
                    // configured symbol (€ / £ / ¥ / …).
                    ->prefix(fn () => (function_exists('currency_symbol') ? currency_symbol() : null) ?: '$')
                    ->placeholder('19.99')
                    ->helperText('Price shown to customers')
                    ->required(),

                // task-2026-05-05-1db9bd (Audit-#9) — "Special price"
                // is colloquial; industry-standard term for this field
                // is "Sale price" (Shopify, WooCommerce, Magento all
                // use it). Renamed the visible label without changing
                // the database column name.
                Forms\Components\TextInput::make('special_price')
                    ->label('Sale price')
                    ->afterStateHydrated(function (?Model $record, Schemas\Components\Utilities\Get $get, Schemas\Components\Utilities\Set $set) {
                        if ($record) {
                            $getSpecialPrice = $record->getSpecialPriceAttribute();
                            $set('special_price', $getSpecialPrice);
                        } else {
                            $set('special_price', '');
                        }
                    })
                    ->numeric()
                    // Audit-test 2026-05-07 Products audit items 2+3:
                    // bound + step + cross-field constraint so the
                    // sale price cannot go negative, cannot have
                    // junk decimals, and cannot exceed the regular
                    // price (server-only catch was a round-trip).
                    ->minValue(0)
                    ->step(0.01)
                    ->lt('price')
                    // task-2026-05-17-fc0b22 / AI-818 — see Price
                    // above; same `?: '$'` robustness fallback so
                    // Sale price always carries a visible currency
                    // anchor matching the shop default.
                    ->prefix(fn () => (function_exists('currency_symbol') ? currency_symbol() : null) ?: '$')
                    ->placeholder('14.99')
                    ->helperText('Optional discount, lower than regular price')
                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                    ->visible(function_exists('offers_get_price')),
            ])->columnSpanFull()->visible(function (Schemas\Components\Utilities\Get $get) {
                return $get('content_type') == 'product';
            });
    }

    protected static function publishedSection(): Schemas\Components\Section
    {
        return Schemas\Components\Section::make('Published')
            ->icon('heroicon-m-signal')
            ->schema([
                Forms\Components\Toggle::make('is_active')
                    ->label('Published')
                    // AI-778 (task-2026-05-17-6d65de) — Published toggle defaults
                    // to FALSE on Create. Previously defaulted to true on Create
                    // (type-title → SAVE → live immediately = "publish on first
                    // save" footgun). Edit form is unaffected — Filament loads
                    // is_active from the record via Eloquent, the default only
                    // applies when the record is null. Operators who want to
                    // publish must now explicitly toggle ON before saving;
                    // explicit > implicit for any state change that affects
                    // public visibility.
                    ->default(false)
                    ->live()
                    ->afterStateUpdated(function (Schemas\Components\Utilities\Get $get, Schemas\Components\Utilities\Set $set) {
                        if ($get('is_active') && !$get('posted_at')) {
                            $set('posted_at', now()->format('Y-m-d H:i:s'));
                        }
                    }),

                Forms\Components\DateTimePicker::make('posted_at')
                    ->label('Publish Date')
                    ->prefixIcon('heroicon-m-calendar')
                    ->helperText(function (Schemas\Components\Utilities\Get $get) {
                        $postedAt = $get('posted_at');
                        if ($postedAt && \Carbon\Carbon::parse($postedAt)->isFuture()) {
                            return 'This post is scheduled for future publication.';
                        }
                        return 'Set a future date to schedule publication. Leave empty to publish immediately.';
                    })
                    ->native(false)
                    ->displayFormat('M d, Y H:i')
                    ->live()
                    ->visible(function (Schemas\Components\Utilities\Get $get) {
                        return $get('content_type') === 'post';
                    }),
            ]);
    }

    protected static function parentPageSection($firstBlogId, $firstShopId): Schemas\Components\Section
    {
        // task-2026-05-04-novice — "Parent page" is information-
        // architecture jargon (folder/parent/child). A first-time
        // site owner reads it as "family tree". Renamed section to
        // "Where to put it" — answers the user's actual question.
        //
        // task-2026-05-17-4c289e / AI-779 Slice A — designer-greenlit
        // structural split: for posts, render TWO labeled sub-sections
        // (Parent page + Categories) instead of one combined tree.
        // Visually separates page-hierarchy placement from taxonomy
        // categorisation — IA confusion designer flagged. Both
        // sub-sections still feed the existing `parent` + `categoryIds`
        // hidden form fields via the mw-tree state binding (no schema
        // change, no new form fields). For pages/products the original
        // single-view shape is preserved.
        return Schemas\Components\Section::make('Where to put it')
            ->icon('heroicon-m-folder')
            ->schema(function (?Model $record, Schemas\Components\Utilities\Get $get) use ($firstBlogId, $firstShopId) {
                $parent = null;
                $categoryIds = [];
                if ($record) {
                    $parent = $record->parent;
                    $categoryIds = $record->getCategoryIdsAttribute();
                }

                $singleSelect = ($record && $record->content_type === 'page') || $get('content_type') === 'page';
                $skipCategories = ($record && $record->content_type === 'page') || $get('content_type') === 'page';
                $contentTypeFilter = ($record && $record->content_type === 'page') || $get('content_type') === 'page' ? 'page' : false;

                $isShopFilter = match (true) {
                    ($record && $record->content_type === 'product') || $get('content_type') === 'product' => 1,
                    ($record && $record->content_type === 'post') || $get('content_type') === 'post' => 0,
                    default => null,
                };

                if ($isShopFilter) {
                    $parent = $firstShopId;
                } elseif ($get('content_type') === 'post' && $firstBlogId) {
                    $parent = $firstBlogId;
                }

                // AI-779 Slice A — post-specific two-section layout.
                $isPost = ($record && $record->content_type === 'post') || $get('content_type') === 'post';
                if ($isPost) {
                    // Sub-section 1: Parent page (pages-only tree,
                    // single-select). Existing skipCategories=true
                    // hides category nodes so this picker is solely
                    // about hierarchy placement.
                    $parentViewData = [
                        'selectedPage' => $parent,
                        'singleSelect' => true,
                        'skipCategories' => true,
                        'contentType' => 'page',
                        'skipPageId' => $record?->id,
                        'isShopFilter' => 0,
                        'selectedCategories' => [],
                    ];
                    // Sub-section 2: Categories (full tree with
                    // category nodes visible + multi-select for
                    // existing categoryIds). Page nodes still appear
                    // — backend mw-tree doesn't yet support a
                    // skipPages flag; AI-779b can ship that
                    // refinement. For Slice A the IA-clarity win is
                    // the LABELED separation of intent (this section
                    // is for taxonomy; the section above is for
                    // hierarchy).
                    $categoriesViewData = [
                        'selectedPage' => null,
                        'singleSelect' => false,
                        'skipCategories' => false,
                        'contentType' => false,
                        'skipPageId' => $record?->id,
                        'isShopFilter' => 0,
                        'selectedCategories' => $categoryIds,
                    ];
                    return [
                        Schemas\Components\Section::make('Parent page')
                            ->icon('heroicon-m-document-text')
                            ->description('Where this post lives in the site hierarchy. Pick a page to nest under.')
                            ->schema([
                                Schemas\Components\View::make('mw-filament::admin.mw-tree')
                                    ->viewData($parentViewData),
                            ]),
                        Schemas\Components\Section::make('Categories')
                            ->icon('heroicon-m-tag')
                            ->description('How readers discover this post via taxonomy. Pick one or more categories.')
                            ->schema([
                                Schemas\Components\View::make('mw-filament::admin.mw-tree')
                                    ->viewData($categoriesViewData),
                            ]),
                    ];
                }

                // Non-post path (pages, products, default) — preserve
                // the original single-view shape verbatim.
                $viewData = [
                    'selectedPage' => $parent,
                    'singleSelect' => $singleSelect,
                    'skipCategories' => $skipCategories,
                    'contentType' => $contentTypeFilter,
                    'skipPageId' => $record?->id,
                    'isShopFilter' => $isShopFilter,
                    'selectedCategories' => $categoryIds
                ];

                return [
                    Schemas\Components\View::make('mw-filament::admin.mw-tree')
                        ->viewData($viewData)
                ];
            });
    }

    protected static function tagsSection(): Schemas\Components\Section
    {
        return Schemas\Components\Section::make('Tags')
            ->icon('heroicon-m-tag')
            ->schema([
                Forms\Components\TagsInput::make('tags')
                    ->label(false)
                    ->reorderable()
                    ->helperText('Separate using commas or Enter key.')
                    ->placeholder('Add a tag'),
            ]);
    }

    /**
     * task-2026-05-17-6d65de / AI-776 — Posts admin Menus rail anti-leak filter.
     * task-2026-05-17-378d85 / AI-784 — refactored to delegate to the systemic
     * AdminFixtureGuard helper (single source of truth for the blocklist).
     *
     * Designer's Round-10 audit caught dev/test fixture menu names
     * surfacing in the production Posts admin form right-rail (e.g.
     * "Menu Test 6A030E7B650Aa (209 items)"). The AI-776 ship added
     * a per-resource blocklist; AI-784 (task-378d85) lifted the
     * blocklist into MicroweberPackages\Filament\Support\AdminFixtureGuard
     * so every admin form that surfaces fixture-prone data can call
     * the same canonical filter. This method is kept as a stable
     * call surface for the existing AI-776 contract test + future
     * grep-discoverability — it now thinly delegates.
     */
    public static function ai776MenuShouldRender(array $menu): bool
    {
        return \MicroweberPackages\Filament\Support\AdminFixtureGuard::shouldRenderItem(
            $menu['title'] ?? null
        );
    }

    protected static function menusSection(): Schemas\Components\Section
    {
        return Schemas\Components\Section::make('Menus')
            ->icon('heroicon-m-bars-3')
            ->schema([
                Forms\Components\CheckboxList::make('menuIds')
                    ->label('Menus')
                    ->helperText('Select menu where this content will appear')
                    ->searchable()
                    ->bulkToggleable()
                    ->options(function (?Model $record) {
                        $menus = get_menus();
                        $menusCheckboxes = [];
                        if ($menus) {
                            // Count items per menu for sorting by most used
                            $menuItems = [];
                            foreach ($menus as $menu) {
                                // AI-776 (task-2026-05-17-6d65de) — fixture-leak filter.
                                // Skip empty-titled + test-pattern menus before the
                                // expensive per-menu item-count query.
                                if (! self::ai776MenuShouldRender($menu)) {
                                    continue;
                                }
                                $itemCount = app()->menu_manager->get_menu_items('count=1&parent_id=' . $menu['id']);
                                $menuItems[] = [
                                    'id' => $menu['id'],
                                    'title' => $menu['title'],
                                    'count' => (int) $itemCount,
                                ];
                            }
                            // Sort by item count descending (most used first)
                            usort($menuItems, fn($a, $b) => $b['count'] - $a['count']);
                            foreach ($menuItems as $menu) {
                                $label = Str::headline($menu['title']);
                                if ($menu['count'] > 0) {
                                    $label .= ' (' . $menu['count'] . ' items)';
                                }
                                $menusCheckboxes[$menu['id']] = $label;
                            }
                        }
                        return $menusCheckboxes;
                    }),
            ]);
    }

    protected static function contentTabsSchema(array $mainForm): array
    {
        return [
            Tabs::make('ContentTabs')
                ->schema([
                    Tabs\Tab::make('Content')
                        ->schema($mainForm),
                    Tabs\Tab::make('Template')
                        ->schema([
                            Schemas\Components\Section::make('Select Template')
                                ->schema([
                                    MwSelectTemplateForPage::make(
                                        'active_site_template',
                                        'layout_file')
                                        ->columnSpanFull(),
                                ])
                                ->columnSpanFull()
                        ])
                        ->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('content_type') == 'page';
                        }),
                    Tabs\Tab::make('Product Details')
                        ->schema(
                            static::productDetailsSection()
                        )
                        ->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('content_type') == 'product';
                        }),
                    Tabs\Tab::make('Variants')
                        ->schema(function (Content|null $record) {
                            $productId = $record?->id ?? 0;
                            return [
                                Livewire::make('admin-product-variant-manager', [
                                    'productId' => $productId,
                                ]),
                            ];
                        })
                        ->icon('heroicon-o-swatch')
                        ->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('content_type') == 'product';
                        }),
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
                            }

                            return [Livewire::make('admin-list-custom-fields', $customFieldParams)];
                        }),
                    Tabs\Tab::make('SEO')
                        ->schema(
                            static::seoSection()
                        ),
                    Tabs\Tab::make('Advanced')
                        ->schema(static::advancedSection()),
                ])->columnSpanFull()
        ];
    }

    public static function form(Schema $schema): Schema
    {
        $params = [];
        $record = $schema->getRecord();

        if ($record && $record->id) {
            $params['id'] = $record->id;
        }

        return $schema->schema(static::formArray($params));
    }

    public static function productDetailsSection()
    {
        return [
            Schemas\Components\Section::make('Pricing')
                ->icon('heroicon-m-currency-dollar')
                ->schema([

                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        // Audit-test 2026-05-07 Products audit items 2+3 —
                        // mirror the lite-modal hardening on the deep
                        // editor: bound + step so negative + junk-
                        // decimal input is blocked client-side.
                        ->minValue(0)
                        ->step(0.01)
                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                        // task-2026-05-17-fc0b22 / AI-818 — mirror the
                        // compact pricingSection's currency prefix on
                        // the full admin Pricing section so non-USD
                        // shops see their configured symbol here too.
                        // Same robustness fallback shape: helper if
                        // available + empty -> '$' last-resort.
                        ->prefix(fn () => (function_exists('currency_symbol') ? currency_symbol() : null) ?: '$')
                        ->columnSpan(['lg' => 2, 'sm' => 2])
                        ->required(),


                    // task-2026-05-05-1db9bd (Audit-#9) — see
                    // pricingSection() above; same rename applied
                    // to the long-form admin variant of this field.
                    Forms\Components\TextInput::make('special_price')
                        ->label('Sale price')
                        ->afterStateHydrated(function (?Model $record, Schemas\Components\Utilities\Get $get, Schemas\Components\Utilities\Set $set) {

                            if ($record) {
                                $getSpecialPrice = $record->getSpecialPriceAttribute();

                                $set('special_price', $getSpecialPrice);
                            } else {
                                $set('special_price', '');
                            }
                        })
                        ->numeric()
                        // Audit-test 2026-05-07 Products audit items 2+3 —
                        // mirror the lite-modal hardening: bound +
                        // step + lt('price') cross-field constraint.
                        ->minValue(0)
                        ->step(0.01)
                        ->lt('price')
                        // task-2026-05-17-fc0b22 / AI-818 — see Price
                        // above; same currency-aware prefix shape on
                        // the full admin Sale price.
                        ->prefix(fn () => (function_exists('currency_symbol') ? currency_symbol() : null) ?: '$')
                        ->columnSpan(['lg' => 2, 'sm' => 2])
                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                        ->visible(function_exists('offers_get_price'))
                    ,


                ])->columnSpanFull(),

            Schemas\Components\Section::make('Inventory')
                ->icon('heroicon-m-cube')
                ->schema([


                    Forms\Components\TextInput::make('content_data.sku')
                        ->helperText('Stock Keeping Unit'),

                    Forms\Components\TextInput::make('content_data.barcode')
                        ->helperText('ISBN, UPC, GTIN, etc.'),

                    Forms\Components\Toggle::make('content_data.track_quantity')
                        ->label('Track Quantity')
                        ->live()
                        ->default(false),


                    Schemas\Components\Group::make([
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

                        Forms\Components\TextInput::make('low_stock_threshold')
                            ->numeric()
                            ->rules(['regex:/^\d{1,6}$/'])
                            ->label('Low stock threshold')
                            ->helperText('Get alerted when stock falls below this number.')
                            ->default(10)
                            ->placeholder('10'),
                    ])->hidden(function (Schemas\Components\Utilities\Get $get) {
                        return !$get('content_data.track_quantity');
                    }),


                ])->columnSpanFull(),

            Schemas\Components\Section::make('Shipping')
                ->icon('heroicon-m-truck')
                ->schema([

                    // This is a physical product
                    Forms\Components\Toggle::make('content_data.physical_product')
                        ->label('This is a physical product')
                        ->default(true)
                        ->live(),

                    Schemas\Components\Group::make([
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

                    ])->columns(2)->hidden(function (Schemas\Components\Utilities\Get $get) {
                        return !$get('content_data.physical_product');
                    }),


                    Schemas\Components\Section::make('Shipping Advanced')
                        ->heading('Advanced')
                        ->description('Advanced product shipping settings.')
                        ->schema([
                            Forms\Components\TextInput::make('content_data.shipping_weight')
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->label('Weight (kg)')
                                ->default(0),

                            Forms\Components\TextInput::make('content_data.shipping_width')
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->label('Width (cm)')
                                ->default(0),

                            Forms\Components\TextInput::make('content_data.shipping_height')
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->label('Height (cm)')
                                ->default(0),

                            Forms\Components\TextInput::make('content_data.shipping_depth')
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
                        ->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('content_data.shipping_advanced_settings');
                        }),

                ])->columnSpanFull(),
        ];
    }

    public static function productDetailsFormArray()
    {
        return static::productDetailsSection();
    }

    public static function seoSection()
    {
        return [
            Schemas\Components\Section::make('Search engine optimisation (SEO)')
                ->description('Add a title and description to see how this product might appear in a search engine listing')
                ->schema([
                    Schemas\Components\Grid::make()
                        ->schema([
                            // Replace Button with Actions\Action which is the correct component in Filament v3
                            Schemas\Components\Actions::make([
                                \Filament\Actions\Action::make('generateSeoContent')
                                    ->label('Generate SEO Content')
                                    ->visible(app()->has('ai'))
                                    ->icon('heroicon-o-sparkles')
                                    ->color('primary')
                                    ->action(function (Schemas\Components\Utilities\Get $get, Schemas\Components\Utilities\Set $set) {
                                        // Get content details to generate better SEO
                                        $title = $get('title');
                                        $description = $get('description');
                                        $content_body = $get('content_body');

                                        $contentToAnalyze = "Title: {$title}\n\nDescription: {$description}\n\nContent: {$content_body}";
                                        $prompt = "Generate SEO metadata for the following content. Include a meta title (max 60 characters), meta description (max 160 characters), and relevant keywords separated by commas:\n\n{$contentToAnalyze}";

                                        /*
                                         * @var \Modules\Ai\Agents\BaseAgent $agent
                                         */
                                        $agent = app('ai.agents')->agent('base');

                                        $class = new class {
                                            public string $meta_title;
                                            public string $meta_description;
                                            public string $meta_keywords;
                                        };

                                        $resp = $agent->structured(
                                            new \NeuronAI\Chat\Messages\UserMessage($prompt),
                                            $class::class
                                        );

                                        if ($resp) {
                                            $set('content_meta_title', $resp->meta_title);
                                            $set('content_meta_description', $resp->meta_description);
                                            $set('content_meta_keywords', $resp->meta_keywords);
                                        }
                                    }),
                            ])
                        ])
                        ->visible(app()->has('ai'))
                        ->columnSpanFull(),

                    // Basic SEO Fields
                    Forms\Components\TextInput::make('content_meta_title')
                        ->label('Meta Title')
                        ->helperText('Describe for what is this page about in short title. Max 60 characters recommended.')
                        ->maxLength(500)
                        ->hintAction(
                            TranslateFieldAction::make('content_meta_title')->label('')
                        )
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('content_meta_description')
                        ->label('Meta Description')
                        ->helperText('Provide a brief summary of this web page. Max 160 characters recommended.')
                        ->maxLength(1000)
                        ->rows(3)
                        ->hintAction(
                            TranslateFieldAction::make('content_meta_description')->label('')
                        )
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('content_meta_keywords')
                        ->label('Meta Keywords')
                        ->helperText('Separate keywords with a comma and space. Example: Blog, Online News, Phones for sale')
                        ->maxLength(500)
                        ->hintAction(
                            TranslateFieldAction::make('content_meta_keywords')->label('')
                        )
                        ->columnSpanFull(),

                    // Canonical URL
                    Forms\Components\TextInput::make('canonical_url')
                        ->label('Canonical URL')
                        ->helperText('Specify the canonical URL if this content has duplicate versions. Leave empty to use the default URL.')
                        ->maxLength(1000)
                        ->columnSpanFull(),

                    // Robots Meta
                    Forms\Components\Select::make('robots_meta')
                        ->label('Robots Meta')
                        ->helperText('Control search engine indexing behavior')
                        ->options([
                            'index, follow' => 'Index, Follow',
                            'index, nofollow' => 'Index, No Follow',
                            'noindex, follow' => 'No Index, Follow',
                            'noindex, nofollow' => 'No Index, No Follow',
                        ])
                        ->default('index, follow')
                        ->columnSpanFull(),

                    // Open Graph Fields
                    Schemas\Components\Section::make('Open Graph (Facebook)')
                        ->description('Social media sharing settings for Facebook and other platforms')
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            Forms\Components\TextInput::make('og_title')
                                ->label('OG Title')
                                ->helperText('The title when shared on Facebook. Defaults to Meta Title if empty.')
                                ->maxLength(500)
                                ->hintAction(
                                    TranslateFieldAction::make('og_title')->label('')
                                )
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('og_description')
                                ->label('OG Description')
                                ->helperText('The description when shared on Facebook. Defaults to Meta Description if empty.')
                                ->maxLength(1000)
                                ->rows(2)
                                ->hintAction(
                                    TranslateFieldAction::make('og_description')->label('')
                                )
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('og_type')
                                ->label('OG Type')
                                ->helperText('Type of content (website, article, product)')
                                ->default('website')
                                ->maxLength(50),

                            Forms\Components\TextInput::make('og_image')
                                ->label('OG Image URL')
                                ->helperText('Full URL to image shown when shared on Facebook')
                                ->maxLength(1000)
                                ->columnSpanFull(),
                        ])->columnSpanFull(),

                    // Twitter Card Fields
                    Schemas\Components\Section::make('Twitter Card')
                        ->description('Twitter sharing settings')
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            Forms\Components\Select::make('twitter_card')
                                ->label('Twitter Card Type')
                                ->helperText('Type of Twitter card to display')
                                ->options([
                                    'summary' => 'Summary',
                                    'summary_large_image' => 'Summary with Large Image',
                                    'app' => 'App Card',
                                    'player' => 'Player Card',
                                ])
                                ->default('summary_large_image'),

                            Forms\Components\TextInput::make('twitter_title')
                                ->label('Twitter Title')
                                ->helperText('The title when shared on Twitter. Defaults to Meta Title if empty.')
                                ->maxLength(500)
                                ->hintAction(
                                    TranslateFieldAction::make('twitter_title')->label('')
                                )
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('twitter_description')
                                ->label('Twitter Description')
                                ->helperText('The description when shared on Twitter. Defaults to Meta Description if empty.')
                                ->maxLength(1000)
                                ->rows(2)
                                ->hintAction(
                                    TranslateFieldAction::make('twitter_description')->label('')
                                )
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('twitter_image')
                                ->label('Twitter Image URL')
                                ->helperText('Full URL to image shown when shared on Twitter')
                                ->maxLength(1000)
                                ->columnSpanFull(),
                        ])->columnSpanFull(),

                    // Sitemap Settings
                    Schemas\Components\Section::make('Sitemap Settings')
                        ->description('Configure how this content appears in the XML sitemap')
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            Forms\Components\Toggle::make('exclude_from_sitemap')
                                ->label('Exclude from Sitemap')
                                ->helperText('Prevent this content from being included in the XML sitemap')
                                ->default(false)
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('sitemap_priority')
                                ->label('Sitemap Priority')
                                ->helperText('Priority from 0.0 to 1.0. Default is 0.5.')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(1)
                                ->step(0.1)
                                ->default(0.5),

                            Forms\Components\Select::make('sitemap_changefreq')
                                ->label('Change Frequency')
                                ->helperText('How frequently the page is likely to change')
                                ->options([
                                    'always' => 'Always',
                                    'hourly' => 'Hourly',
                                    'daily' => 'Daily',
                                    'weekly' => 'Weekly',
                                    'monthly' => 'Monthly',
                                    'yearly' => 'Yearly',
                                    'never' => 'Never',
                                ])
                                ->placeholder('Auto-detect based on content type'),
                        ])->columnSpanFull(),
                ])
        ];
    }

    public static function seoFormArray()
    {
        return static::seoSection();
    }

    public static function seoForm(Form $schema): Form
    {
        return $schema
            ->schema(static::seoSection());
    }


    public static function advancedSection()
    {
        return [
            Schemas\Components\Section::make('Advanced Settings')
                ->description('You can configure advanced settings for this content')
                ->schema([

                    Forms\Components\TextInput::make('original_link')
                        ->label('Redirect URL')
                        ->helperText('Redirect to another URL when this content is accessed')
                        ->columnSpanFull(),


                    Forms\Components\Toggle::make('require_login')
                        ->label('Require login')
                        ->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('id');
                        })
                        ->helperText('Require user to be logged in to view this content')
                        ->columnSpanFull(),


Forms\Components\Select::make('created_by')
->visible(function (Schemas\Components\Utilities\Get $get) {
return $get('id');
})
->label('Author')
->placeholder('Select author')
->options(function () {
return \MicroweberPackages\User\Models\User::query()
    ->whereNotNull('email')
    ->limit(100)
    ->pluck('email', 'id');
})
->searchable()
->preload(),


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
                            'page' => 'Page',
                            'post' => 'Post',
                            'product' => 'Product',
                            'dynamic' => 'Dynamic',
                        ]),


                    Forms\Components\Toggle::make('is_shop')
                        ->label('Is Shop')
                        ->default(0)
                        ->helperText('This page will accept products to be added to it.')
                        ->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('content_type') === 'page';
                        })
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_home')
                        ->label('Is Homepage')
                        ->default(0)
                        ->helperText('This will be the first page of your website.')
                        ->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('content_type') === 'page';
                        })
                        ->columnSpanFull(),


                    Forms\Components\DateTimePicker::make('created_at')
                        ->label('Created At')
                        ->format('Y-m-d H:i:s')
                        ->native(false)
                        ->displayFormat('Y-m-d H:i:s')
                        ->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('id');
                        })
                        ->columnSpanFull(),

                    Forms\Components\DateTimePicker::make('updated_at')
                        ->label('Updated At')
                        ->format('Y-m-d H:i:s')
                        ->native(false)
                        ->displayFormat('Y-m-d H:i:s')
                        ->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('id');
                        })
                        ->columnSpanFull(),

                    Forms\Components\Placeholder::make('id')
                        ->label('ID')
                        ->inlineLabel(true)
                        ->content(function ($record) {
                            return $record?->id;
                        })->visible(function (Schemas\Components\Utilities\Get $get) {
                            return $get('id');
                        }),


                    Schemas\Components\Section::make('Access Settings')
                        ->description('You can configure advanced settings for this content')
                        ->collapsed(true)
                        ->collapsible(true)
                        ->compact()
                        ->schema([

                            Forms\Components\Select::make('content_data.custom_access')
                                ->label('Custom Access')
                                ->reactive()
                                ->options([
                                    null => 'Normal',
                                    'require_product_purchase' => 'Require Product Purchase',
                                    'require_subscription_plan_group' => 'Require Subscription plan',
                                ]),


                            Forms\Components\Select::make('content_data.custom_access_product_id')
                                ->reactive()
                                ->visible(function (Schemas\Components\Utilities\Get $get) {
                                    return $get('content_data.custom_access') === 'require_product_purchase';
                                })
                                ->label('Product ID')
                                ->helperText('The user must purchase this product to access the content.')
                                ->label('Product ID')
                                ->options(function () {
                                    return Content::where('content_type', 'product')->pluck('title', 'id');
                                })
                                ->searchable(),


                            Forms\Components\Select::make('content_data.custom_access_require_subscription_plan_group_id')
                                ->reactive()
                                ->visible(function (Schemas\Components\Utilities\Get $get) {
                                    return $get('content_data.custom_access') === 'require_subscription_plan_group';
                                })
                                ->label('Subscription Plan ID')
                                ->helperText('The user must be subscribed to this billing plan to access the content.')
                                ->options(function () {
                                    return \Modules\Billing\Models\SubscriptionPlanGroup::pluck('name', 'id');
                                })
                                ->searchable(),


                        ])


                ])
        ];
    }

    public static function advancedSettingsFormArray()
    {
        return static::advancedSection();
    }

    public static function advancedSettingsForm(Form $schema): Form
    {
        return $schema
            ->schema(static::advancedSection());
    }


    public static function getListTableColumns(): array
    {

        return [
            ImageUrlColumn::make('media_url')
                ->label('Image')
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

            Tables\Columns\TextColumn::make('created_by')
                ->label('Author')
                ->icon('heroicon-m-user')
                ->formatStateUsing(fn ($state) => $state ? user_name($state) : '—')
                ->toggleable(isToggledHiddenByDefault: false),

            Tables\Columns\TextColumn::make('posted_at')
                ->label('Published')
                ->dateTime('M d, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->visible(fn ($livewire) => $livewire instanceof \Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts),

            Tables\Columns\TextColumn::make('stock_status')
                ->label('Stock')
                ->badge()
                ->getStateUsing(function (Model $record) {
                    if ($record->content_type !== 'product') {
                        return null;
                    }
                    $trackQuantity = $record->getContentDataByFieldName('track_quantity');
                    if (!$trackQuantity) {
                        return 'In Stock';
                    }
                    $qty = (int) ($record->qty ?? 0);
                    if ($qty <= 0) {
                        return 'Out of Stock';
                    }
                    $threshold = $record->low_stock_threshold ?? 10;
                    if ($qty <= $threshold) {
                        return 'Low Stock';
                    }
                    return 'In Stock';
                })
                ->color(fn (?string $state): string => match ($state) {
                    'In Stock' => 'success',
                    'Low Stock' => 'warning',
                    'Out of Stock' => 'danger',
                    default => 'gray',
                })
                ->icon(fn (?string $state): ?string => match ($state) {
                    'In Stock' => 'heroicon-m-check-circle',
                    'Low Stock' => 'heroicon-m-exclamation-triangle',
                    'Out of Stock' => 'heroicon-m-x-circle',
                    default => null,
                })
                ->toggleable(isToggledHiddenByDefault: false)
                ->visible(fn ($livewire) => $livewire instanceof \Modules\Product\Filament\Admin\Resources\ProductResource\Pages\ListProducts),

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
                    ->searchable(app(Content::class)->getSearchableByKeyword())
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
            ->searchable(true)
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

                Tables\Filters\SelectFilter::make('content_type')
                    ->label('Content Type')
                    ->options([
                        'page' => 'Page',
                        'post' => 'Post',
                        'product' => 'Product',
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            return $query->where('content_type', $data['value']);
                        }
                        return $query;
                    }),


                Tables\Filters\SelectFilter::make('content_subtype')
                    ->label('Content Subtype')
                    ->options([
                        'static' => 'Static',
                        'post' => 'Post',
                        'product' => 'Product',
                        'dynamic' => 'Dynamic',
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            return $query->where('subtype', $data['value']);
                        }
                        return $query;
                    }),


                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Published',
                        0 => 'Unpublished',
                    ])
                    ->query(function ($query, array $data) {

                        if (isset($data['value'])) {
                            return $query->where('is_active', '=', intval($data['value']));
                        }
                        return $query;
                    }),


                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->searchable()
                    ->options(function () {
                        return \Modules\Category\Models\Category::query()
                            ->orderBy('title')
                            ->pluck('title', 'id')
                            ->toArray();
                    })
                    ->query(function ($query, array $data) {


                        if (!empty($data['value'])) {


                            return $query->whereCategoryIds([$data['value']]);
                        }
                        return $query;
                    }),

                Tables\Filters\SelectFilter::make('created_by')
                    ->label('Author')
                    ->searchable()
                    ->options(function () {
                        return \MicroweberPackages\User\Models\User::query()
                            ->whereIn('id', Content::query()->whereNotNull('created_by')->distinct()->pluck('created_by'))
                            ->pluck('email', 'id')
                            ->toArray();
                    }),
            ])
            ->filtersFormWidth(MaxWidth::Medium)
            ->actions([
                ActionGroup::make([
                    // TASK-020 / TICKET-K / AI-38 (cycle-60 2026-05-08):
                    // Per-row contextual labels carry the record title so
                    // screen readers + tooltips announce
                    //   `Edit "My Post Title"`
                    // instead of a bare `Edit` repeated 50x down the
                    // table. ->label(fn) feeds Filament's accessible
                    // name (and the visible dropdown text inside an
                    // ActionGroup); ->tooltip(fn) shows the same string
                    // on hover. The contextualRowLabel helper anchors
                    // on $title with id-fallback for untitled drafts.
                    Tables\Actions\Action::make('live_edit')
                        ->label(fn (Content $record): string => 'Edit "' . static::contextualRowLabel($record) . '"')
                        ->tooltip(fn (Content $record): string => 'Edit "' . static::contextualRowLabel($record) . '"')
                        ->url(function (Content $record) {


                            return $record->link() . '?editmode=y';
                        })
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\EditAction::make('edit')
                        ->label(fn (Content $record): string => 'Settings for "' . static::contextualRowLabel($record) . '"')
                        ->tooltip(fn (Content $record): string => 'Settings for "' . static::contextualRowLabel($record) . '"')
                        ->icon('heroicon-o-pencil'),


                    Tables\Actions\DeleteAction::make('delete')
                        ->label(fn (Content $record): string => 'Delete "' . static::contextualRowLabel($record) . '"')
                        ->tooltip(fn (Content $record): string => 'Delete "' . static::contextualRowLabel($record) . '"')
                        ->icon('heroicon-o-trash'),

])->icon('heroicon-o-ellipsis-vertical')
            ->color(Color::Gray)
            ->iconSize('lg')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(fn ($records) => $records->each->update(['is_active' => 1])),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Unpublish')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(fn ($records) => $records->each->update(['is_active' => 0])),
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
        return $record->title ?? 'Content #' . $record->id;
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

    /**
     * TASK-020 / TICKET-K / AI-38 (cycle-60 2026-05-08): build a
     * record-contextual label for screen-reader announcements + hover
     * tooltips on row actions. Anchors on $title (Posts / Pages /
     * Products / Categories canonical attribute) with $name fallback,
     * and finally `#{id}` so untitled drafts still get a non-empty
     * announcement. Returns the bare label — callers wrap it in
     * `'Edit "..."'` etc. so the verb stays in their layer.
     */
    public static function contextualRowLabel($record): string
    {
        $title = trim((string) ($record->title ?? ''));
        if ($title !== '') {
            return $title;
        }
        $name = trim((string) ($record->name ?? ''));
        if ($name !== '') {
            return $name;
        }
        $id = $record->id ?? $record->getKey();
        return $id !== null && $id !== '' ? '#' . $id : 'untitled';
    }
}
