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

Current date reference: March 2026  
Filament version focus: **v5.x** (stable series v5.3.x, released Jan 2026 onward)  
Built on **Livewire v4**, Tailwind v4, Alpine.js  
Official docs (always check latest): https://filamentphp.com/docs/5.x

Filament = powerful PHP-based admin panel / CRUD / form / dashboard framework for Laravel.  
Server-rendered UI with minimal custom JS.

## 1. Core Mindset & Philosophy

- Pure PHP fluent builders → chain everything
- Three pillars: **Forms**, **Tables**, **Actions**
- Panels = isolated contexts (admin, app, customer, partner…)
- Resources = model + CRUD + relations
- Livewire v4 foundation → stricter lifecycle, better .live / .debounce reactivity, no more wire:model shorthand in many cases

## 2. Installation & Project Setup (v5)

```bash
# Fresh Laravel 11/12 project
composer create-project laravel/laravel filament-app
cd filament-app

# Install latest Filament v5
composer require filament/filament:"^5.0" -W

# Install panel(s)
php artisan filament:install --panels

# Publish assets (CSS/JS) – run after theme changes too
php artisan filament:assets

# Optional: install support for dark mode, etc. (usually automatic now)
```

Upgrading from v4 → v5:

```bash
composer require filament/filament:"^5.0" -W
# Usually low-risk – mostly Livewire v4 compatibility
# Run upgrade command if needed (check docs for any manual steps)
```

Key requirements: PHP 8.2+, Laravel 11.28+, Tailwind 4.1+

## 3. Panels – Core Configuration

```php
// app/Providers/Filament/AdminPanelProvider.php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->colors([
            'primary' => '#2563eb', // or use ->primary() helper
        ])
        ->font('Inter')
        ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
        ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
        ->pages([
            \Filament\Pages\Dashboard::class,
        ])
        ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
        ->widgets([
            // StatsOverview::class,
        ])
        ->middleware([
            // EncryptCookies::class, ...
        ])
        ->authMiddleware([
            \App\Http\Middleware\Authenticate::class,
        ]);
}
```

Multi-panel setups: duplicate provider class → change id/path → register in config/filament.php or boot method.

## 4. Resources – Advanced CRUD Patterns

```php
// app/Filament/Resources/PostResource.php
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\RichEditor::make('body')
                            ->required()
                            ->fileAttachmentsDisk('s3')
                            ->fileAttachmentsDirectory('posts'),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([...]),
                    ])
                    ->columns(2),

                Forms\Components\Toggle::make('is_published'),
                Forms\Components\DateTimePicker::make('published_at'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('success'),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published'),
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ExportBulkAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
            // TagsRelationManager::class,
        ];
    }
}
```

Pro tips:

- Scoped queries: `->modifyQueryUsing(fn ($q) => $q->whereBelongsTo(auth()->user()->currentTeam))`
- Custom pages: override `getPages()` method
- Reusable form schemas: `protected static function getMainContentSchema(): array { … }`

## 5. Forms – Reactivity & Advanced Components

- `->live()` / `->live(onBlur: true)` / `->debounce(500)`
- `->afterStateUpdated()` / `->afterStateHydrated()`
- `->dependsOn(['other_field'], fn (callable $set) => …)`
- RichEditor: mentions, attachments, toolbar config
- FileUpload: `->image()` `->imageEditor()` `->multiple()` `->preserveFilenames()`
- Builder / Repeater: nested blocks, collapsible, orderable
- Layout: `Grid`, `Split`, `Section`, `Tabs`, `Wizard`

## 6. Tables – Power User Features

- Custom sorting: `->sortable(query: fn ($q, $dir) => …)`
- Summaries: `->summarize(Sum::make())`
- Grouping: `->groups(['category.name', 'published_at'])`
- Polling: `->poll('10s')`
- Stacked layout (mobile-friendly, new in v5)
- Deferred filters (chart widgets)

## 7. Actions – Versatile & Modal

- `->requiresConfirmation()` `->modalDescription()` `->modalSubmitActionLabel()`
- Wizard actions, bulk actions, standalone actions
- `->after()` hooks + notifications

## 8. Widgets & Dashboards

- StatsOverviewWidget, ChartWidget (ApexCharts)
- TableWidget
- Official **Custom Dashboards** plugin (drag & drop, released ~early 2026)

## 9. Advanced / Production-Grade Skills

- Custom themes (Tailwind v4 config)
- Authorization: `canAccessPanel()` `->tenant()` multi-tenancy
- Plugins: Shield, Spatie Media Library, Impersonate, etc.
- Relation managers: HasMany, MorphToMany, BelongsToMany
- Custom fields: `ViewField`, inline Livewire components
- Testing: Filament test helpers (`->get('/admin')`, form filling, table assertions)
- Performance: lazy widgets, query eager loading, `->hiddenOn(['mobile'])`

## 10. Common Gotchas (March 2026)

- Livewire v4: use `wire:model.live` / `wire:model.debounce.500ms`
- Asset publishing required after theme/JS changes
- Dark mode: ensure Tailwind dark classes don't conflict
- Plugin compatibility: check v5.x support tag
- Upgrade path: usually smooth, but test custom Livewire/JS

## Quick Component Cheat Sheet

| Goal                          | Best Approach / Component                     | Notes / v5 Notes                     |
|-------------------------------|-----------------------------------------------|--------------------------------------|
| Rich text + attachments       | RichEditor::make()->fileAttachmentsDisk('s3') | Mentions & toolbar config improved   |
| Dynamic slug              | TextInput::make('title')->live()->afterStateUpdated(...) | Classic pattern                      |
| Nested repeatable blocks      | Builder::make() or Repeater with schema       | Collapsible & orderable              |
| Conditional fields            | ->hidden(fn (Get $get): bool => …)            | Or ->visibleOn() helpers             |
| Relation badges in table      | TextColumn::make('tags.name')->badge()        | Works with ->formatStateUsing()      |
| Export CSV/Excel              | ExportBulkAction::make()                      | Built-in                             |
| Callout / alert in form       | Callout::make() (new in v5.2+)                | Great for notices                    |

Update this file as you master new features (e.g. Custom Dashboards plugin, upcoming v5.x releases).

