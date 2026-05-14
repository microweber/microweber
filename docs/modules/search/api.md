# API Reference

Class, method, and template signatures for the Search module.

---

## SearchComponent (Livewire)

`Modules\Search\Livewire\SearchComponent`. Registered as `module-search` via `SearchServiceProvider`. The entire search experience runs through this component.

### Public properties

| Property | Type | Default | Purpose |
|---|---|---|---|
| `$moduleId` | `string` | `null` | The module instance id passed by the `<module type="search" />` tag — used to look up per-instance options. |
| `$placeholder` | `string` | `'Search...'` | The `placeholder=""` attribute on the input. Set from `options.placeholder`. |
| `$dataContentId` | `int` | `0` | Parent content id for scoped search. `0` = whole site. Set from `options.data-content-id`. |
| `$autocomplete` | `bool` | `false` | Whether the autocomplete template variant is active. Set from `options.autocomplete`. |
| `$searchQuery` | `string` | `''` | Bound to the input via `wire:model.live.debounce.300ms`. |
| `$searchResults` | `array` | `[]` | The current result set (≤ 10 rows from `get_content()`). |
| `$isLoading` | `bool` | `false` | Toggled around the `search()` call for the spinner UI. |

### Public methods

#### `mount(string $moduleId): void`

Called once on first render. Loads the per-instance options from the Option service, populates the public properties, checks the URL `?q=<keyword>` query string (via the shipped `search.js`), and runs an initial `search()` if a keyword is present.

#### `updatedSearchQuery(): void`

Livewire lifecycle hook — fires automatically whenever `$searchQuery` changes (via the debounced wire:model binding). Internal behaviour:

1. If the trimmed keyword is shorter than 2 characters → clear `$searchResults`, return early.
2. Otherwise → call `$this->search()`.

This is the entry point for live-typed searches.

#### `search(): void`

The query method. Pseudocode:

```php
$this->isLoading = true;
$kw = mb_substr(strip_tags($this->searchQuery), 0, 200);

$this->searchResults = get_content([
    'search_in_fields' => 'title,content,description',
    'keyword'          => $kw,
    'limit'            => 10,
    'no_cache'         => true,
    'search_in'        => 'content',
    'parent'           => (int) $this->dataContentId,
]);

$this->isLoading = false;
```

Throws nothing — `get_content()` returns `[]` on no matches or any underlying error.

#### `clearSearch(): void`

Resets `$searchQuery = ''` and `$searchResults = []`. Bound to the input's clear-X button.

#### `render(): \Illuminate\View\View`

Returns `view('modules.search::livewire.search.index', [...])`.

---

## SearchModule (module-tag bridge)

`Modules\Search\Microweber\SearchModule`. Implements the Microweber `ModuleInterface`. Bridges the legacy `<module type="search" />` tag rendering to the modern Livewire component.

### `render(array $params = []): string`

Called by the Microweber template engine when it encounters a `<module type="search">` tag.

1. Resolves the `moduleId` from `$params`.
2. Reads per-instance options via `get_module_options($moduleId, 'search')`.
3. Picks the template:
   - If `options.template` is set explicitly → uses that.
   - Else if `options.autocomplete === true` → `autocomplete.blade.php`.
   - Else → `default.blade.php`.
4. Returns the rendered Blade output (which itself embeds `<livewire:module-search :moduleId="..." />`).

You normally don't call this directly — the module tag does it for you.

---

## SearchSettings (Filament form)

`Modules\Search\Filament\SearchSettings`. Extends `MicroweberPackages\LiveEdit\Filament\Forms\LiveEditModuleSettings`. Drives the live-edit settings panel for a Search module instance.

### Form schema

The form has two tabs:

**Content tab:**

```php
TextInput::make('options.placeholder')
    ->label('Placeholder')
    ->default('Search...');

Select::make('options.data-content-id')
    ->label('Parent page')
    ->options($contentPages)            // 1000 most-recent pages
    ->default(0);                       // 0 = whole site

Select::make('options.searchPosition')
    ->options(['start' => 'Start', 'center' => 'Center', 'end' => 'End'])
    ->default('center');

TextInput::make('options.searchWidth')->default('500');
TextInput::make('options.searchHeight')->default('100');
Toggle::make('options.autocomplete')->default(false);
```

**Design tab:**

Inherits the standard live-edit template-selection schema from `LiveEditModuleSettings::getTemplatesFormSchema()` — registers the two shipped templates plus any custom templates dropped into `Modules/Search/resources/views/templates/`.

---

## Blade templates

### `default.blade.php`

The standard form variant. Embeds:

```blade
<livewire:module-search :moduleId="$moduleId" :autocomplete="false" />
```

with a wrapping `<div>` that applies `options.searchPosition` and `options.searchWidth`.

### `autocomplete.blade.php`

The typeahead variant. Embeds the same Livewire component with `:autocomplete="true"` and wraps it with absolute-positioned popover styling.

### `livewire/search/index.blade.php`

The Livewire component's view. Contains:

- The `<input>` with `wire:model.live.debounce.300ms="searchQuery"` and a clear-X button bound to `wire:click="clearSearch"`.
- The loading spinner (`@if($isLoading)`).
- The results list (`@foreach($searchResults as $result)`) — each `<a>` uses `content_link($result['id'])` for the href and renders `$result['title']` as text.
- The "No results found" empty state.

If you need to customise the result-row rendering (e.g. show a thumbnail, show the matched excerpt), copy this file to your app-level Blade override path and edit the foreach body.

---

## Underlying `get_content()` contract

Search's query is a direct call into the Content module's `get_content()` helper. Search relies on the following parameter contract — if Content ever changes these key names, Search breaks:

| Param | Type | What Search passes | Effect |
|---|---|---|---|
| `search_in_fields` | comma-string | `'title,content,description'` | which Content columns to LIKE against |
| `keyword` | string | sanitized user input | the LIKE pattern (Content wraps it in `%...%`) |
| `limit` | int | `10` | max rows returned |
| `no_cache` | bool | `true` | bypass the Content query cache (the cache key uses the keyword, so live typing would explode the cache) |
| `search_in` | string | `'content'` | content type filter (`'content'` matches all types) |
| `parent` | int | from `$dataContentId` | restrict to descendants of this content id; 0 = unrestricted |

The returned rows are arrays with at least `id`, `title`, `description`, `content_type`, `parent`, `url` (or `link`) — Search renders the title and uses `content_link($id)` for navigation.

If you write a fork of Search that doesn't use `get_content()`, mirror this return shape (at minimum `id` + `title`) so the existing `livewire/search/index.blade.php` view still works.

---

## Events

There are **no Search-owned events fired anywhere**. The `EventServiceProvider` exists with an empty `$listen` array and `$shouldDiscoverEvents = true` — reserved for future analytics work but not yet wired.

If you need to track search activity, the integration points are:

- **Inside `SearchComponent::search()`** — add a `$this->dispatch('mw-search-performed', keyword: $kw)` line so JS listeners can react.
- **Inside `get_content()`** — wrap with a custom service that fires whatever you need.

Neither requires changes to this module's public API.

---

## Helpers

Search does **not** define any global helpers. It uses these from other modules:

| Helper | Owner | Purpose |
|---|---|---|
| `get_content($params)` | Content module | the actual search query |
| `content_link($id)` | Content module | builds the permalink for a result row |
| `get_module_options($moduleId, 'search')` | Option service | per-instance settings lookup |
| `_e($key)` | translation helper | i18n for the "No results found" / "Search..." strings |

---

## Tests

The Search module has **no `Tests/` directory**. `composer.json` declares the `Modules\Search\Tests\` PSR-4 namespace but no files exist under it. Coverage of the search behaviour today comes from manual QA + the Content module's `get_content()` tests (which exercise the underlying query).

If you add tests, mirror the Content module's pattern: instantiate the Livewire component via `Livewire::test(SearchComponent::class, ['moduleId' => $id])->set('searchQuery', 'hello')->assertSee('Result title')`.
