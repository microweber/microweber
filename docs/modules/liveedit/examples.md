# Examples

Four end-to-end recipes for common LiveEdit integrations.

---

## 1. Add a custom +ADD picker card

Suppose your custom module ships a "Quick Quote" content type that admins can add inline from the +ADD picker. Three steps.

### Step 1 — extend `AdminLiveEditPage::addContentAction`

```php
namespace App\Filament\Admin\Pages;

use MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditPage as BaseLiveEditPage;
use Filament\Actions\Action;
use Filament\Schemas\MaxWidth;

class AppLiveEditPage extends BaseLiveEditPage
{
    public function addContentAction(): Action
    {
        $action = parent::addContentAction();

        // Append your card to the action's modal data
        $action->modalContent(function () use ($action) {
            $defaultActions = $this->getDefaultPickerActions();

            $defaultActions[] = [
                'name'        => 'addQuickQuoteAction',
                'label'       => 'New quick quote',
                'description' => 'A short pull-quote block with attribution. Pick this if you want to highlight a customer testimonial. Skip this for a full blog post.',
                'icon'        => 'heroicon-o-chat-bubble-left-right',
                // No js_dispatch — this card opens a Filament create-record form via standard action flow
            ];

            return view('modules.liveedit::add-content-modal', [
                'actions' => $defaultActions,
            ]);
        });

        return $action;
    }

    public function addQuickQuoteAction(): Action
    {
        return $this->generateAction('addQuickQuoteAction', 'quick_quote');
    }
}
```

### Step 2 — register the page override

```php
// App\Providers\AppServiceProvider::boot()

\MicroweberPackages\Filament\FilamentRegistry::registerPage(
    \App\Filament\Admin\Pages\AppLiveEditPage::class
);

// Unregister the default (if needed; depends on how the registry handles duplicates)
```

### Step 3 — ensure your content type has a `formArrayCompact` shape

Mirror what `ContentResource::formArrayCompact()` does for your `quick_quote` content type. The compact form should be lean: title + body + maybe attribution + published toggle. NO SEO / tags / menus — those belong to the full admin form, reached via "Show all options".

After deployment, opening the +ADD picker shows your card alongside the defaults. Typing "quote" or "testimonial" in the search filters to it (extend the synonym map in your forked `add-content-modal.blade.php` if you want the synonym match).

---

## 2. Build a custom module-settings page

Module-settings pages extend `LiveEditModuleSettings`. Suppose your module is `WidgetCarousel` and it needs admin-editable settings (slide duration, autoplay, navigation visibility).

```php
namespace App\Modules\WidgetCarousel\Filament\Admin\Pages;

use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class WidgetCarouselSettingsPage extends LiveEditModuleSettings
{
    protected static ?string $navigationLabel = 'Widget Carousel Settings';

    protected function getFormSchema(): array
    {
        return [
            Toggle::make('autoplay')
                ->label('Autoplay slides')
                ->default(true),

            TextInput::make('slide_duration_ms')
                ->label('Slide duration (milliseconds)')
                ->numeric()
                ->minValue(1000)
                ->maxValue(30000)
                ->default(5000),

            Select::make('navigation_style')
                ->label('Navigation arrows')
                ->options([
                    'arrows'  => 'Arrows only',
                    'dots'    => 'Dots only',
                    'both'    => 'Arrows + dots',
                    'none'    => 'None',
                ])
                ->default('both'),
        ];
    }
}
```

Register the page + the Live Edit settings URL:

```php
// App\Modules\WidgetCarousel\Providers\WidgetCarouselServiceProvider::boot()

\MicroweberPackages\Filament\FilamentRegistry::registerPage(
    \App\Modules\WidgetCarousel\Filament\Admin\Pages\WidgetCarouselSettingsPage::class
);

\MicroweberPackages\Module\Facades\ModuleAdmin::registerLiveEditSettingsUrl(
    'widget-carousel',
    \App\Modules\WidgetCarousel\Filament\Admin\Pages\WidgetCarouselSettingsPage::getUrl()
);
```

Now when the canvas-side JS dispatches `openModuleSettingsAction` with `component: 'widget-carousel'`, the `AdminLiveEditPage::openModuleSettingsAction()` resolves the URL and opens the page inside a same-origin iframe slide-over.

Reading the values from your module's render code:

```php
$autoplay  = \MicroweberPackages\Option\Models\Option::getValue('autoplay', 'widget-carousel-' . $moduleId);
$duration  = (int) \MicroweberPackages\Option\Models\Option::getValue('slide_duration_ms', 'widget-carousel-' . $moduleId);
$nav       = \MicroweberPackages\Option\Models\Option::getValue('navigation_style', 'widget-carousel-' . $moduleId);
```

The `$moduleId` scope is per-instance — each embedded `<module type="widget-carousel" />` gets its own settings.

---

## 3. Listen for `liveEditAddContentSaved` and run custom logic

You want to push a custom analytics event every time staff create new content from the +ADD picker.

In your admin-side JS (loaded in the canvas iframe or admin frame — depends on which surface you want to track):

```js
// In your project's admin-extras.js (loaded via AdminManager::addScript())

window.addEventListener('liveEditAddContentSaved', (event) => {
    const url = event?.detail?.url;
    if (!url) return;

    // Tell your analytics provider
    if (window.gtag) {
        gtag('event', 'content_created_inline', {
            url: url,
            timestamp: Date.now(),
            user_id: window.MW_USER_ID || null,
        });
    }

    // Or a simple HTTP beacon
    fetch('/api/internal/track-content-create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.MW_CSRF },
        body: JSON.stringify({ url }),
        keepalive: true,
    });
});
```

Register the script in your provider:

```php
// App\Providers\AppServiceProvider::boot()

\MicroweberPackages\Admin\Facades\AdminManager::addScript(
    asset('js/admin-extras.js')
);
```

(Or the modern Filament render-hook pattern — see the [Admin package usage docs](/modules/admin/usage#injecting-scripts-and-styles).)

The verb fires once per successful `generateAction` save. Listeners can be in either surface (parent admin frame OR canvas iframe) since the verb propagates via `window.dispatchEvent`.

---

## 4. Custom save handler for a bespoke editing surface

Suppose you've built a custom block-editor Filament page that opens inside the Live Edit canvas. You want the toolbar SAVE button to trigger your page's save instead of (or in addition to) the default save-flow.

Default behaviour: the SAVE button dispatches `liveEditSaveCallMountedAction`. The handler in `iframe-page.blade.php` discovers and submits the most-specific mounted form. If your page has a `wire:submit.prevent` form with a `callMountedAction` handler, it's picked up automatically — no extra work.

But suppose your editor has a non-Livewire save mechanism (e.g. it POSTs to a custom REST endpoint). Then:

```js
// Loaded inside your editor page's view

window.addEventListener('liveEditSaveCallMountedAction', async (event) => {
    // Bail if you're not the active editor (avoid stealing other forms' saves)
    if (!document.body.classList.contains('my-bespoke-editor-active')) {
        return;
    }

    // Prevent the default save-flow from running (the form discovery
    // in iframe-page.blade.php) by stopping propagation IF possible
    event.stopPropagation?.();

    try {
        const payload = collectBespokeEditorState();

        const response = await fetch('/api/my-editor/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.MW_CSRF },
            body: JSON.stringify(payload),
        });

        if (!response.ok) throw new Error(`Save failed: ${response.status}`);

        // Surface success — match the toolbar's standard success toast
        window.dispatchEvent(new CustomEvent('mw-save-success', { detail: payload }));

    } catch (err) {
        console.error('Bespoke editor save failed:', err);
        window.dispatchEvent(new CustomEvent('liveEditMountedActionValidationFailed'));
    }
});
```

Two important caveats:

1. **`stopPropagation()` may not actually stop the default save-flow handler** depending on listener registration order. The reliable pattern is to make your custom editor's DOM **NOT** contain a `wire:submit.prevent` form — the default save-flow then finds nothing to submit and bails silently. Your custom handler runs in parallel.
2. **Honour the verb back-channel**: dispatch `liveEditMountedActionValidationFailed` on save errors so the Vue toolbar's failure indicator shows. Dispatch a success verb that the toolbar listens for if you want the green-confirmation flash.

The cleanest design: build your bespoke editor as a Livewire component with a real `wire:submit.prevent` form. Then it integrates with the default save-flow specificity ranker for free — no custom listener needed.
