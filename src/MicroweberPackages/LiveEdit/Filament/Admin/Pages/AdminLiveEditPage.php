<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages;


use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\LocaleSwitcher;
use Filament\Schemas\Components\Livewire;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width as MaxWidth;
use Illuminate\Contracts\View\View;
use MicroweberPackages\LiveEdit\Filament\Actions\CustomViewAction;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\Modules\Logo\Http\Livewire\LogoModuleSettings;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Category\Filament\Admin\Resources\CategoryResource;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Models\Content;
use function Clue\StreamFilter\fun;

class AdminLiveEditPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | null $navigationLabel = 'Live Edit';
    protected static string $description = '';
    protected static ?string $slug = 'live-edit';

    protected static ?int $navigationSort = 10;
    protected string $view = 'microweber-live-edit::iframe-page';
    protected static string $layout = 'filament-panels::components.layout.live-edit';

    use InteractsWithActions;
    use InteractsWithForms;

    /**
     * URL of the page currently shown in the live-edit canvas, captured
     * on initial page load via the `?url=` query param. Persisted as a
     * Livewire property so subsequent action mounts (which run on a
     * different request to /livewire/update) can still resolve which
     * page the user is editing — task-2026-05-01-30153f.
     */
    public string $liveEditUrl = '';

    public function mount(): void
    {
        $this->liveEditUrl = (string) request()->get('url', '');
    }

    public function render(): View
    {
        $params = request()->all();
        return view($this->getView(), $this->getViewData())
            ->layout($this->getLayout(), [
                'livewire' => $this,
                'params' => $params,
                'maxContentWidth' => $this->getMaxContentWidth(),
                ...$this->getLayoutData(),
            ]);
    }


    public function addContentAction(): Action
    {
        // task-2026-05-04-novice — plain-English descriptions for
        // Brenda the novice customer. The previous strings ("linked
        // to category of main page on your website", "organize ... in
        // the right way") read like translated developer notes; a
        // first-time site owner cannot tell from those whether they
        // need a Page or a Post. Each description now answers the
        // single question the user has at this moment: "Which one
        // do I want?" with one concrete example.
        $actions = [];
        $actions[] = [
            'title' => 'New Page',
            'description' => 'A standalone page like About, Services or Contact.',
            'action' => 'addPageAction',
            'icon' => 'mw-add-page',
        ];
        $actions[] = [
            'title' => 'New Post',
            'description' => 'A blog article or news story that appears in your Blog list.',
            'action' => 'addPostAction',
            'icon' => 'mw-add-post',
        ];
        $actions[] = [
            'title' => 'New Category',
            'description' => 'A folder to group your blog posts or shop items.',
            'action' => 'addCategoryAction',
            'icon' => 'mw-add-category',
        ];
        $actions[] = [
            'title' => 'New Product',
            'description' => 'An item to sell in your online shop.',
            'action' => 'addProductAction',
            'icon' => 'mw-add-product',
        ];

        return Action::make('addContentAction')
            ->modalHeading('Add new content')
            ->form([
                \Filament\Schemas\Components\View::make('microweber-live-edit::add-content-modal')
                    ->viewData([
                        'actions' => $actions
                    ])
            ])
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            // Centered modal at the same width tier as the
            // generateAction modals — so the +ADD picker visually
            // matches the modal that opens after the user picks a
            // type. The previous slideOver was a tiny right-edge
            // drawer that felt like a developer tool, not a friendly
            // "what do you want to add?" dialog. task-2026-05-02-4c1606.
            ->modalWidth(MaxWidth::TwoExtraLarge)
            ->extraModalWindowAttributes(['class' => 'mw-content-form-modal mw-content-picker-modal']);
    }

    public function addPageAction(): Action
    {
        return $this->generateAction('addPageAction', 'page');
    }

    public function addPostAction(): Action
    {
        return $this->generateAction('addPostAction', 'post');
    }

    public function addProductAction(): Action
    {
        return $this->generateAction('addProductAction', 'product');
    }

    public function addCategoryAction(): Action
    {
        return $this->generateAction('addCategoryAction', 'category');
    }

    public function openModuleSettingsAction(): Action
    {
        return Action::make('openModuleAction')
            ->modalIcon(function (array $arguments) {
                $data = $arguments['data'];
                if (isset($data['moduleSettingsComponent'])) {
                    if (isset($data['moduleSettingsComponent'])) {
                        $exists = class_exists($data['moduleSettingsComponent']);
                        if ($exists) {

                            /** @var LiveEditModuleSettings $resourceClass */
                            $resourceClass = $data['moduleSettingsComponent'];
                            if (method_exists($resourceClass, 'getNavigationIcon')) {
                                return $resourceClass::getNavigationIcon();
                            }
                        }
                    }
                }

                return 'heroicon-o-cog-6-tooth';
            })
            ->label(function (array $arguments) {
                $data = $arguments['data'];
                if (isset($data['moduleSettingsComponent'])) {
                    if (isset($data['moduleSettingsComponent'])) {
                        $exists = class_exists($data['moduleSettingsComponent']);
                        if ($exists) {
                            $resourceClass = $data['moduleSettingsComponent'];
                            /** @var LiveEditModuleSettings $resourceClass */
                            if (method_exists($resourceClass, 'getNavigationLabel')) {
                                return $resourceClass::getNavigationLabel();

                            }
                        }
                    }
                }

                return 'Module Settings';
            })
            //  ->modalContent(view('microweber::livewire.no-settings'))
            //->modalContent(view('microweber::livewire.no-settings'))
            ->form(
                function (array $arguments) {
                    $data = $arguments['data'];
                    $params = $data['params'];

                    if (isset($data['moduleSettingsComponent'])) {
                        $componentClass = $data['moduleSettingsComponent'];
                        $exists = class_exists($componentClass);

                        if (!$exists) {
                            return [
                                TextInput::make('error')
                                    ->label('Error')
                                    ->readOnly()
                                    ->default('Livewire or Filament Component not found: '
                                        . $componentClass)
                            ];
                        }

                        // Check if the component is a Filament Page with a URL
                        // If so, use an iframe to render it
                        if (is_subclass_of($componentClass, \Filament\Pages\Page::class)
                            && method_exists($componentClass, 'getUrl')) {
                            $url = $componentClass::getUrl();
                            if ($url) {
                                $iframeUrl = $url;
                                if (!empty($params)) {
                                    $iframeUrl .= (str_contains($iframeUrl, '?') ? '&' : '?') . http_build_query($params);
                                }
                                return [
                                    \Filament\Schemas\Components\View::make('microweber-live-edit::module-settings-iframe')
                                        ->viewData([
                                            'iframeUrl' => $iframeUrl,
                                            'resourceClass' => $componentClass,
                                            'data' => $data,
                                            'params' => $params
                                        ])
                                ];
                            }
                        }

                        // For regular Livewire components, embed directly
                        $liveEditIframeData = [];
                        if (isset($data['liveEditIframeData']) and !empty($data['liveEditIframeData'])) {
                            $liveEditIframeData = $data['liveEditIframeData'];
                        }
                        return [
                            Livewire::make($componentClass,
                                ['params' => $params, 'liveEditIframeData' => $liveEditIframeData])
                        ];

                    }
                }
            )
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->stickyModalHeader(true)
            // task-2026-05-04-b2dfc1 — user reported "on posts
            // and product module in live edit we must pop up
            // the central edit modal instead of slide". Dropped
            // `->slideOver()` so this Module Settings panel
            // opens as a centered modal (like the +ADD toolbar
            // already does), and bumped the width from Medium
            // (~448px) to FiveExtraLarge (1024px) to match the
            // Create-Content modal so the Items-list table +
            // inner Create/Edit modal both have desktop room.
            ->modalWidth(MaxWidth::FiveExtraLarge)
            ->extraModalWindowAttributes(['class' => 'mw-module-settings-live-edit-modal']);


    }

    public function generateAction($actionName, $contentType)
    {


        if ($contentType == 'category') {
            // Lean live-edit category form — no SEO/Advanced tabs,
            // matches the post/page/product compact modal.
            // task-2026-05-04-e0fe54.
            $formArray = CategoryResource::formArrayCompact();
        } else {
            // The lean live-edit variant — title + body + published
            // + parent + (product) pricing only. Power users can
            // open the full admin form to fill in SEO / Custom
            // Fields / Tags / Menus etc. task-2026-05-04-1d68c7.
            $formArray = ContentResource::formArrayCompact([
                'contentType' => $contentType
            ]);
        }

        // Resolve the page currently shown in the live-edit canvas
        // (captured from `?url=` at mount() into $this->liveEditUrl —
        // request()->get('url') is empty during the /livewire/update
        // POST that runs the action) to a Content id so newly-created
        // posts/products/categories land under that page automatically.
        // Without this, "Create Post" while editing a blog page produced
        // an orphan post the user could never see in the listing —
        // task-2026-05-01-30153f.
        //
        // task-2026-05-01-3dff3c: when the user opens /admin/live-edit
        // with no `?url=` (the most common entry point — clicking
        // "Live edit" from the dashboard), the canvas defaults to the
        // homepage but $this->liveEditUrl is empty. Calling
        // getContentIdFromUrl('') falls through to the current
        // request URL (/livewire/update) — wrong. Fall back to
        // homepage() explicitly so the new post lands under the home
        // page id rather than as an orphan.
        $currentPageId = null;
        $iframeUrl = $this->liveEditUrl;
        if ($iframeUrl !== '') {
            $resolved = app()->content_manager->getContentIdFromUrl($iframeUrl);
            if ($resolved) {
                $currentPageId = (int) $resolved;
            }
        } else {
            $home = app()->content_manager->homepage();
            if (is_array($home) && !empty($home['id'])) {
                $currentPageId = (int) $home['id'];
            }
        }

        return Action::make($actionName)
            ->label('Create ' . $contentType)
            ->modalHeading('Create ' . $contentType)
            // Green "Save" button matches the green main-toolbar SAVE
            // pill — visually says "this is the primary commit
            // action". The previous default was a dark grey button
            // that looked secondary and made users reach for the
            // toolbar SAVE instead. task-2026-05-02-4c1606.
            ->color('success')
            // Centered modal (NOT a right-side slideOver) so the form
            // gets the full-width column the title/url/content-body
            // fields actually need. The previous slideOver pinned the
            // modal to the right edge at ~Medium width — too narrow
            // for the rich text editor inside Content body, with the
            // canvas peeking through behind it. The user reported the
            // modal as "too small" and "not slide right" —
            // task-2026-05-02-82ca03. Width bumped from
            // ThreeExtraLarge (768px) to FiveExtraLarge (1024px) per
            // task-2026-05-04-3337c0 — at 768px the tabs + two-column
            // form + rich-text editor still felt cramped on standard
            // 1080p+ desktop viewports; 1024px gives the editor proper
            // breathing room while still leaving the live-edit canvas
            // visible behind the backdrop tint.
            ->modalWidth(MaxWidth::FiveExtraLarge)
            // `mw-content-form-modal` re-enables the close-overlay
            // backdrop tint (the microweber-filament-theme globally
            // forces `.fi-modal-close-overlay` to bg-transparent —
            // fine for slide-overs, catastrophic for centered
            // content-creation modals where the user can't tell where
            // the modal ends). CSS lives in iframe-page.blade.php.
            // task-2026-05-02-df09aa.
            ->extraModalWindowAttributes(['class' => 'mw-content-form-modal'])
            // Don't let a stray backdrop click or Escape keystroke
            // destroy a half-typed Add Post form. Filament defaults
            // both to true; for content-creation modals where the user
            // has invested keystrokes, that default is catastrophic
            // (no draft, no warning, no recovery) — see screenshot
            // `audit-2-after-click-outside.png` from
            // task-2026-05-02-354958. Cancel still works via the X
            // button or the explicit "Cancel" footer action.
            ->closeModalByClickingAway(false)
            ->closeModalByEscaping(false)
            // Long forms (Title + URL + Content body w/ rich text +
            // Excerpt + …) push the in-modal Save button below the
            // viewport. Sticky footer keeps Save/Cancel visible while
            // the form scrolls. task-2026-05-02-354958.
            ->stickyModalFooter()
            // "Open in admin" escape hatch — the live-edit modal
            // intentionally ships only the essentials (Title +
            // body + published + parent + maybe pricing). Power
            // users who need SEO / Custom Fields / Tags / Menus /
            // Variants can click this to open the full admin
            // create form in a new tab without losing their
            // place in the live-edit canvas. task-2026-05-04-76275d.
            ->extraModalFooterActions(fn () => [
                Action::make('openInAdmin')
                    // task-2026-05-04-novice — "Open in admin" is
                    // jargon. A novice user has no idea what "admin"
                    // means in this context (vs the green SAVE pill
                    // that's also "in admin"). Renamed to "Show all
                    // options" so the verb describes what the user
                    // actually gets — the full form with SEO / Tags /
                    // Custom Fields / Permalink — and so it stops
                    // looking like a synonym for Save.
                    ->label('Show all options')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    // ->color('gray') used to render as a near-
                    // invisible dark-grey rectangle on the dark-
                    // mode modal — switch to ->outlined() which
                    // picks up an explicit border in both
                    // themes. task-2026-05-04-1e4af3 (P2 finding).
                    ->outlined()
                    ->extraAttributes(['class' => 'mw-open-in-admin-btn'])
                    // The title-carry-forward from the typed
                    // input to the URL query string is wired up
                    // client-side in iframe-page.blade.php — see
                    // the `mw-open-in-admin-btn` JS hook there.
                    // task-2026-05-04-1e4af3 (P1-3).
                    ->url(static::resolveAdminCreateUrl($contentType, $currentPageId))
                    ->openUrlInNewTab(),
            ])
            ->form($formArray)
            ->action(function ($data) use ($contentType, $currentPageId) {

                $data['content_type'] = $contentType;

                // Default-link the new item to the page being edited so
                // it appears under that listing immediately after save
                // (task-2026-05-01-30153f). Pages stay top-level.
                if ($currentPageId && $contentType !== 'page' && empty($data['parent'])) {
                    $data['parent'] = $currentPageId;
                }

                $model = new Content();
                $model->fill($data);
                $model->save();

                $contentTypeFriendly = ucfirst($contentType);

                $newContentLink = (string) content_link($model->id);

                // Build a useful next-step action for the toast.
                // The previous "View {Type}" action was a no-op
                // because we already navigate the canvas to the
                // new content URL on the next line — clicking
                // "View" landed the user on the page they were
                // already on. Replace it with "Edit details"
                // pointing at the FULL admin form, so power
                // users who skipped SEO/Tags/Custom Fields in
                // the compact modal can refine those without
                // re-opening the lean modal. Categories don't
                // have a public URL anyway and skip the canvas
                // navigation, so for them the "Edit details"
                // link is doubly useful as the only way back to
                // the new record. task-2026-05-04-f575c7.
                $editDetailsUrl = ($contentType === 'category')
                    ? CategoryResource::getUrl('edit', ['record' => $model])
                    : ContentResource::getUrl('edit', ['record' => $model]);

                // task-2026-05-04-6adcfe — user reported "I still
                // don't think the content adding is ok". Walking
                // the flow live: title-only Save lands on a near-
                // empty public page (just title + date + footer),
                // toast fades in 5s — user has zero idea what to
                // do next. Three changes:
                //   1. Toast title now includes the saved title
                //      so the user sees "TestPost-… created" not
                //      just "Post created" — strong recognition
                //      that THIS specific item went through.
                //   2. Body explicitly tells them what's next:
                //      "Now click anywhere on the page to start
                //      writing." That's the actual mental model.
                //   3. ->persistent() so the toast doesn't fade
                //      until the user dismisses it. Five seconds
                //      isn't enough when the user is also reading
                //      the (mostly-empty) page they just landed
                //      on; persistent feels like a real status
                //      indicator, not a flash.
                $titleText = trim((string) ($model->title ?? ''));
                $shortTitle = $titleText !== ''
                    ? '"' . mb_strimwidth($titleText, 0, 40, '…') . '"'
                    : strtolower($contentTypeFriendly);

                Notification::make()
                    ->success()
                    ->title($shortTitle . ' created')
                    ->body('Now click anywhere on the page to start writing — or "Edit details" for SEO, tags, and more.')
                    ->persistent()
                    ->actions([
                        Action::make('editDetails')
                            ->label('Edit details')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->url($editDetailsUrl)
                            ->openUrlInNewTab()
                            ->button(),
                    ])
                    ->send();

                // Navigate the canvas to the just-created content (or
                // refresh the current page when no link is available
                // — e.g. categories don't have public URLs). Plain
                // `mw.app.canvas.refresh()` is not enough on its own
                // because the host page (often the homepage) doesn't
                // necessarily list posts — the user clicked "Add Post"
                // and would still see the unchanged homepage, then
                // file a bug saying "add posts is not working" because
                // nothing visibly happened (task-2026-05-01-3dff3c).
                // Navigating to the new item turns Save into "I see my
                // post, it worked" — the strongest possible signal.
                $this->dispatch('liveEditAddContentSaved', url: $newContentLink);
            })
            ->modalSubmitActionLabel('Save');
    }

    /**
     * Resolve the full-admin create URL for the "Open in admin"
     * escape hatch in the live-edit compact modal. Carries the
     * current canvas page as `?parent_page_id=` so the admin form
     * defaults to the same parent the user implicitly chose by
     * editing that page. task-2026-05-04-76275d.
     */
    protected static function resolveAdminCreateUrl(string $contentType, ?int $currentPageId): string
    {
        $query = [];
        if ($currentPageId && $contentType !== 'page') {
            $query['parent_page_id'] = $currentPageId;
        }

        if ($contentType === 'category') {
            return CategoryResource::getUrl('create', $query);
        }

        $query['content_type'] = $contentType;
        return ContentResource::getUrl('create', $query);
    }
}
