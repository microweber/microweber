---
name: filament-v5-developer
description: Advanced skills for building and maintaining production-grade admin panels, CRUD systems, forms, tables, dashboards and custom workflows with Filament v5 (Laravel + Livewire v4 + Tailwind v4).
category: laravel-frontend-backend
tags: [filament, laravel, livewire, tailwind, admin-panel, crud, php]
version_focus: Filament ^5.0 – ^5.3.x (as of March 2026)
related: [laravel-developer, livewire-v4, tailwind-v4]
level: advanced
---

# SKILL.md – Filament v5 Development Mastery

**Current reference:** March 2026  
**Primary version:** Filament **v5.3.x** series (stable, Livewire v4 foundation, Tailwind v4 compatible)  
**Official docs:** https://filamentphp.com/docs/5.x

Filament = full-featured, server-rendered admin panel framework for Laravel — forms, tables, actions, panels, resources, widgets — all in pure PHP.

## 1. Core Philosophy & Mental Model

- Fluent builder pattern everywhere (`->method()->chain()`)
- Three fundamental building blocks: **Forms**, **Tables**, **Actions**
- **Panels** = isolated application contexts (admin / app / customer / partner …)
- **Resources** = Eloquent model + full CRUD + relation managers
- Live under **Livewire v4** → stricter lifecycle, `.live`, `.debounce`, no legacy `wire:model` sugar in many cases

## 2. Project Setup & Installation (2026 best practice)

```bash
# Laravel 11 / 12
composer create-project laravel/laravel filament-app
cd filament-app

composer require filament/filament:"^5.0" -W

php artisan filament:install --panels

# Always run after theme changes or plugin installs
php artisan filament:assets
```

**Upgrade v4 → v5 checklist**
- Update to Livewire ^4.0 first if you have custom components
- `composer require filament/filament:"^5.0" -W`
- Usually minimal manual changes — check breaking changes in upgrade guide

## 3. Panel Configuration (app/Providers/Filament/AdminPanelProvider.php)

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->registration()           // optional
        ->passwordReset()          // optional
        ->colors([
            'primary' => '#2563eb',
        ])
        ->font('Inter')
        ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
        ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
        ->pages([ \Filament\Pages\Dashboard::class ])
        ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
        ->middleware([/* … */])
        ->authMiddleware([/* … */]);
}
```

## 4. Resource – Modern CRUD Example (2026 style)

```php
class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        RichEditor::make('body')
                            ->required()
                            ->fileAttachmentsDisk('s3')
                            ->fileAttachmentsDirectory('posts/ bodies'),

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([/* mini form */]),
                    ])
                    ->columns(2),

                Toggle::make('is_published'),
                DateTimePicker::make('published_at')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('category.name')->badge()->color('success'),
                IconColumn::make('is_published')->boolean(),
                TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published'),
                SelectFilter::make('category_id')->relationship('category', 'name'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                ExportBulkAction::make(),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }
}
```

## 5. Quick Reference – Most Used Patterns

| Goal                              | Component / Technique                                 | Key v5 Tip / Syntax                                 |
|-----------------------------------|-------------------------------------------------------|-----------------------------------------------------|
| Auto-slug from title              | `TextInput::make('title')->live(onBlur: true)`        | `afterStateUpdated(fn (Set $set, $state) => …)`     |
| Rich text + uploads               | `RichEditor::make()->fileAttachmentsDisk('s3')`       | Supports mentions, resize, toolbar config           |
| Conditional field visibility      | `->hidden(fn (Get $get) => $get('type') !== 'paid')`  | Or `->visible()` / `->hiddenOn(['mobile'])`         |
| Nested repeatable blocks          | `Builder::make()` or `Repeater::make()`               | Collapsible, orderable, addable blocks              |
| Relation badges in table          | `TextColumn::make('tags.name')->badge()`              | `->formatStateUsing()` for custom display           |
| Scoped records per tenant/team    | `->modifyQueryUsing(fn($q)=> $q->where('team_id', …))`| Common in multi-tenant apps                         |
| Export                            | `ExportBulkAction::make()`                            | Built-in CSV/XLSX                                   |

## 6. Testing – Comprehensive Filament v5 Testing Guide

Filament v5 components are Livewire v4 components under the hood → test with **Livewire::test()** (PHPUnit) or **livewire()** helper (Pest + Livewire plugin).

**Recommended setup (Pest + Livewire plugin):**
```bash
composer require pestphp/pest pestphp/pest-plugin-livewire --dev
php artisan pest:install
```

Always **act as** an authorized user:
```php
actingAs(User::factory()->admin()->create());
```

### 6.1 Testing Resource Pages (List / Create / Edit / View)

```php
it('can list records', function () {
    $posts = Post::factory()->count(5)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertOk()
        ->assertCanSeeTableRecords($posts);
});
```

Search + filter + sort:
```php
livewire(ListPosts::class)
    ->searchTable('Laravel')
    ->assertCanSeeTableRecords($matchingPosts)
    ->assertCanNotSeeTableRecords($nonMatching)
    ->sortTable('title', 'desc')
    ->assertCanSeeTableRecords($sortedPosts, inOrder: true);
```

Create page:
```php
livewire(CreatePost::class)
    ->fillForm([
        'title' => 'New Post',
        'slug' => 'new-post',
        'body' => '<p>Content</p>',
    ])
    ->call('create')
    ->assertHasNoFormErrors()
    ->assertRedirect(PostResource::getUrl('index'));

// Assert database
expect(Post::latest()->first())
    ->title->toBe('New Post')
    ->is_published->toBeFalse();
```

Edit page:
```php
$post = Post::factory()->create(['title' => 'Old']);

livewire(EditPost::class, ['record' => $post])
    ->assertFormSet(['title' => 'Old'])
    ->fillForm(['title' => 'Updated'])
    ->call('save')
    ->assertHasNoFormErrors()
    ->assertRedirect();
```

### 6.2 Testing Forms & Schemas

```php
livewire(CreatePost::class)
    ->assertFormExists()
    ->assertFormFieldExists('title')
    ->assertFormFieldIsRequired('title')
    ->assertFormFieldIsHidden('published_at') // conditional
    ->set('data.title', 'Test')
    ->assertFormSet(['slug' => 'test']); // reactivity test
```

Validation:
```php
->fillForm(['title' => ''])
->call('create')
->assertHasFormErrors(['title' => 'required']);
```

### 6.3 Testing Tables

```php
livewire(ListPosts::class)
    ->assertCanSeeTableRecords(Post::all())
    ->filterTable('is_published', true)
    ->assertCanSeeTableRecords($publishedOnly)
    ->assertSee('No records found...') // empty state
    ->assertSee('Create post'); // empty action
```

Bulk actions:
```php
->selectTableRecords($postsToDelete)
->callTableBulkAction('delete')
->assertHasNoTableBulkActionErrors()
->assertDatabaseMissing('posts', ['id' => $postsToDelete->first()->id]);
```

### 6.4 Testing Actions (including modals)

```php
livewire(EditPost::class, ['record' => $post])
    ->mountAction('publish')
    ->assertActionMounted('publish')
    ->assertSee('Are you sure?') // modal content
    ->callMountedAction()
    ->assertActionUnmounted()
    ->assertHasNoActionErrors()
    ->assertNotificationSent('success', 'Post published!');
```

Bulk action example:
```php
->callTableBulkAction('approve', $selectedPosts)
->assertHasNoTableBulkActionErrors();
```

### 6.5 Testing Notifications

```php
// After action
->callMountedAction()
->assertNotificationSent('success', 'Saved successfully');

// Or custom
->assertSee('Post created successfully');
```

### 6.6 Testing Relation Managers

```php
livewire(PostResource\RelationManagers\CommentsRelationManager::class, [
    'ownerRecord' => $post,
])
    ->assertCanSeeTableRecords($post->comments)
    ->fillForm(['content' => 'Great post!'])
    ->call('create')
    ->assertHasNoFormErrors();
```

### 6.7 Testing Custom Pages & Widgets

Custom page = plain Livewire component:
```php
livewire(DashboardStats::class)
    ->assertSee('Total Posts: 42')
    ->assertSee('Active Users');
```

Widget:
```php
livewire(StatsOverview::class)
    ->assertSee('$12,345 revenue');
```

### 6.8 Best Practices & Gotchas

- Use factories + actingAs() for auth/tenancy
- Prefer Pest + `livewire()` helper
- Chain assertions fluently
- Test reactivity with `->set()` / `->assertFormSet()`
- For complex reactivity → test after `->call('updatedDataFieldName')`
- Use `assertDatabaseHas()` / `assertDatabaseCount()` after mutations
- Coverage goal: 80–100% on resource pages, actions, and custom logic
- Run `php artisan test --coverage` regularly

Update this section as new assertions or plugins emerge (e.g. better mocking for file uploads or relation managers).

## 7. Navigation & Routing Gotchas

### 7.1 `getSlug()` Signature in Filament v5

When overriding `getSlug()` in **both Resources and Pages**, you MUST include the
optional `\Filament\Panel $panel = null` parameter to match the parent class signature:

```php
// WRONG in Filament v5 — PHP throws declaration incompatibility:
public static function getSlug(): string
{
    return 'my-resource';
}

// CORRECT — matches Filament v5 parent signature:
public static function getSlug(?\Filament\Panel $panel = null): string
{
    return 'my-resource';
}
```

**Error without it:**
```
Declaration of MyResource::getSlug(): string must be compatible with
Filament\Resources\Resource::getSlug(?Filament\Panel $panel = null): string
```

**Applies to:** `Filament\Resources\Resource` subclasses AND `Filament\Pages\Page`
subclasses. The parameter is nullable so callers without it work fine; only the
*signature declaration* needs to match.

**Contract test regex** for this signature pattern:
```php
// Allow for optional parameter — use [^)]* instead of ()
"~function\s+getSlug\([^)]*\)[^{]*\{[^}]*return\s+['\"]my-slug['\"]~s"
```

### 7.2 `shouldRegisterNavigation` — Hidden Resources

If a Filament Resource has `protected static bool $shouldRegisterNavigation = false;`,
the resource is completely invisible in the sidebar. All `/admin/<slug>/*` URLs still
work when accessed directly — only the nav entry is suppressed.

**To surface a hidden resource:**
1. Remove the `$shouldRegisterNavigation = false` line
2. Set `$navigationIcon`, `$navigationLabel`, `$navigationGroup`, `$navigationSort`
3. Add a custom `getSlug()` if the default auto-generated slug is wrong

**Common symptom:** "Guessing `/admin/backup`... 404" — usually `$shouldRegisterNavigation = false`.

### 7.3 Custom Page with Embedded Table

To create a Filament **Page** that contains a table (e.g. `/admin/restore`):

```php
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class MyTablePage extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'my-module::filament.pages.my-table-page';

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'my-table';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(MyModel::query())
            ->columns([...])
            ->actions([...]);
    }
}
```

**Blade view** (minimal):
```blade
<x-filament-panels::page>
    {{ $this->table }}
</x-filament-panels::page>
```

**Register via FilamentRegistry:**
```php
FilamentRegistry::registerPage(MyTablePage::class);
```

Happy building & testing! 
