# Filament v5 Migration: Panel Architecture Decision

**Date:** March 04, 2026
**Decision:** Multi-Panel Architecture (Retain and Modernize)
**Decision Maker:** Senior Developer / Tech Lead
**Status:** ✅ **MIGRATION COMPLETE** (Updated: 2026-03-07)

---

## Executive Summary

After thorough analysis of the existing codebase, we will **retain the multi-panel architecture** and modernize it for Filament v5. The system already successfully uses separate panels for distinct user contexts (admin, customer, billing), and this approach provides clear separation of concerns while maintaining modularity.

---

## Current State Analysis

### Existing Panel Providers

| Panel ID | Path | Purpose | Extends | User Context |
|----------|------|---------|---------|--------------|
| `admin` | `/admin` | Main administration | `PanelProvider` | Super admins |
| `admin-billing` | `/admin/billing` | Billing management | `FilamentAdminPanelProvider` | Billing admins |
| `admin-newsletter` | `/admin/newsletter` | Newsletter campaigns | `FilamentAdminPanelProvider` | Marketing team |
| `billing` | `/billing` | Customer billing frontend | `PanelProvider` | Customers |
| `profile` | `/profile` | User profile management | `PanelProvider` | Authenticated users |
| `checkout` | `/checkout` | Checkout flow | `PanelProvider` | Shoppers |

### Architecture Pattern

```
PanelProvider (Filament)
    ↓
FilamentAdminPanelProvider (Microweber)
    ↓
Module-specific Panel Providers (Billing, Newsletter, etc.)
```

**Key Patterns:**
1. **Base Provider:** `FilamentAdminPanelProvider` provides shared configuration (colors, middleware, theme)
2. **Discovery:** All panels use `discoverResources()`, `discoverPages()`, `discoverWidgets()`
3. **Registration:** Panels register via `FilamentRegistry` for dynamic extension
4. **Path Prefix:** Admin panels use `mw_admin_prefix_url()` for path consistency

---

## Decision Matrix: Single Panel vs Multi-Panel

### Option 1: Single Panel (Rejected)

| Pros | Cons |
|------|------|
| Simpler initial migration | Loss of context isolation |
| One auth middleware stack | Customer/admin resources mixed |
| Single URL namespace | Complex permission checks required |
| Easier navigation management | Risk of accidental exposure |
| Faster development initially | Harder to maintain long-term |

**Verdict:** Rejected due to security and maintainability concerns.

### Option 2: Multi-Panel (Selected) ✅

| Pros | Cons |
|------|------|
| Clear user context separation | More files to maintain |
| Separate auth guards per panel | Slightly more complex routing |
| Module isolation (Billing can be disabled) | Need to keep panel registrations in sync |
| Different themes per panel | More configuration files |
| Better security boundaries | Requires understanding of panel relationships |
| Scales with team growth | — |

**Verdict:** Selected. Aligns with existing architecture and provides long-term benefits.

---

## Modernization Plan for Filament v5

### 1. Panel Provider Updates

All panel providers require updates for Filament v5 compatibility:

```php
// BEFORE (v3)
use Filament\PanelProvider;

class FilamentAdminPanelProvider extends PanelProvider
{
    // ...
}

// AFTER (v5) - No signature change, but internals updated
use Filament\PanelProvider;

class FilamentAdminPanelProvider extends PanelProvider
{
    // Fluent API additions available
}
```

### 2. Navigation Group Standardization

**Current:** Mix of string and object-based navigation groups

**v5 Standard:**
```php
use Filament\Navigation\NavigationGroup;

->navigationGroups([
    NavigationGroup::make('Dashboard')
        ->label('')
        ->collapsible(false),
    NavigationGroup::make('Website')
        ->icon('mw-website')
        ->label('Website'),
    NavigationGroup::make('Shop')
        ->icon('mw-shop')
        ->label('Shop'),
    NavigationGroup::make('Other')
        ->collapsible(true),
    NavigationGroup::make('Settings')
        ->collapsible(true),
])
```

### 3. Middleware Stack

All panels use consistent middleware:

```php
->middleware([
    EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
    AuthenticateSession::class,
    ShareErrorsFromSession::class,
    VerifyCsrfToken::class,
    SubstituteBindings::class,
    DisableBladeIconComponents::class,
    DispatchServingFilamentEvent::class,
])
->authMiddleware([
    Authenticate::class, // or custom admin auth
]);
```

### 4. Theme Integration

All panels must register `MicroweberFilamentTheme` plugin:

```php
$panel->plugin(new MicroweberFilamentTheme());
```

---

## Tenant Model Decision

**Decision:** No tenant model for v5 migration (current implementation)

**Rationale:**
- Current system uses role-based access control (RBAC), not multi-tenancy
- Panels are role-based (admin vs customer), not tenant-based
- Adding tenancy would significantly increase migration complexity
- Can be added later without breaking existing architecture

**Future Consideration:** If multi-tenancy becomes a requirement, evaluate:
- `filament/spatie-laravel-multitenancy` plugin
- Custom tenant resolver in middleware
- Panel-level tenancy with `->tenant()` method

---

## Panel Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        FILAMENT v5                               │
│                     Panel Registry                               │
└─────────────────────────────────────────────────────────────────┘
                               │
        ┌──────────────────────┼──────────────────────┐
        │                      │                      │
┌───────▼───────┐    ┌─────────▼──────────┐   ┌──────▼──────┐
│  Admin Panel  │    │   Billing Panels    │   │   Other    │
│  (Default)    │    │                     │   │   Panels    │
├───────────────┤    ├──────────┬──────────┤   ├─────────────┤
│ • Dashboard   │    │  Admin   │ Customer │   │ • Newsletter│
│ • Content     │    │  Billing │ Billing  │   │ • Profile   │
│ • Shop        │    │          │          │   │ • Checkout  │
│ • Settings    │    │ /admin/  │ /billing/│   │             │
│               │    │ /billing │          │   │             │
│ /admin/       │    │          │          │   │             │
└───────────────┘    └──────────┴──────────┘   └─────────────┘
        │                      │                      │
        └──────────────────────┼──────────────────────┘
                               │
              ┌────────────────┴────────────────┐
              │   FilamentRegistry (Dynamic)    │
              │   - discoverResources()         │
              │   - discoverPages()             │
              │   - discoverWidgets()           │
              └─────────────────────────────────┘
```

---

## Migration Checklist by Panel

### Primary Panel: Admin (`admin`)
- [x] Update `FilamentAdminPanelProvider` to v5 fluent API - Completed 2026-03-06
- [x] Standardize navigation groups - Completed 2026-03-06
- [x] Verify `->default()` is set - Completed 2026-03-06
- [x] Confirm middleware stack - Completed 2026-03-06
- [x] Test resource discovery - Completed 2026-03-06

### Billing Admin (`admin-billing`)
- [x] Keep separate panel for isolation - Completed 2026-03-06
- [x] Update to extend modernized `FilamentAdminPanelProvider` - Completed 2026-03-06
- [x] Verify `admin-billing` path - Completed 2026-03-06
- [x] Test subscription resource CRUD - Completed 2026-03-06

### Newsletter Admin (`admin-newsletter`)
- [x] Update `NewsletterFilamentAdminPanelProvider` - Completed 2026-03-06
- [x] Verify campaign resource migration - Completed 2026-03-06
- [x] Test widget rendering - Completed 2026-03-06

### Customer Panels (billing, profile, checkout)
- [x] Keep separate for security - Completed 2026-03-06
- [x] Update to v5 PanelProvider base - Completed 2026-03-06
- [x] Verify frontend themes - Completed 2026-03-06
- [x] Test public access (checkout) - Completed 2026-03-06
- [x] Test authenticated access (billing, profile) - Completed 2026-03-06

---

## Files to Create/Update

### New Files
1. `docs/filament-migration.md` (this document) ✅

### Updated Files (in migration order)
1. `src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php`
2. `Modules/Billing/Providers/BillingFilamentAdminPanelProvider.php`
3. `Modules/Billing/Providers/BillingFilamentFrontendPanelProvider.php`
4. `Modules/Profile/Providers/FilamentProfilePanelProvider.php`
5. `Modules/Checkout/Providers/FilamentCheckoutPanelProvider.php`
6. `Modules/Newsletter/Providers/NewsletterFilamentAdminPanelProvider.php`
7. `bootstrap/providers.php` (if adding new panels)

---

## Security Considerations

1. **Panel Isolation:** Each panel has separate auth middleware
2. **URL Patterns:** Admin panels under `/admin/*`, customer panels at root
3. **Auth Guards:** Admin uses custom `AuthenticateAdmin`, customer uses standard `Authenticate`
4. **Cross-Panel Access:** Prevented by middleware configuration

---

## Performance Impact

| Aspect | Single Panel | Multi-Panel |
|--------|--------------|-------------|
| Memory per request | Higher (loads all resources) | Lower (loads panel-specific) |
| Initial load | Slower | Faster per-panel |
| Resource discovery | Once | Per-panel |
| Caching | Complex | Isolated per panel |
| Scalability | Limited | Better |

**Conclusion:** Multi-panel provides better performance characteristics.

---

## Appendix: Panel Registration Reference

### Provider Registration Pattern

```php
// In ModuleServiceProvider::boot()
public function boot(): void
{
    FilamentRegistry::registerPlugin(
        self::class,
        SomePlugin::class
    );
    
    FilamentRegistry::registerResource(
        self::class,
        SomeResource::class
    );
}
```

### Bootstrap Providers

```php
// bootstrap/providers.php
return [
    App\Providers\Filament\AdminPanelProvider::class,
    // Additional panels registered via module service providers
];
```

---

## Decision Sign-off

**Architecture Decision:** Retain multi-panel architecture  
**Tenant Strategy:** No tenancy for v5 (role-based only)  
**Migration Approach:** Incremental per-panel  
**Target Completion:** Phase 1 of TODO.md  

**Approved by:** Senior Developer
**Date:** 2026-03-04
**Version:** 1.0

---

## Module Developer Upgrade Guide

This section provides practical step-by-step instructions for module developers upgrading from Filament v3 to v5.

### Quick Reference: v3 vs v5 Patterns

| Component | v3 Pattern | v5 Pattern |
|-----------|------------|------------|
| **Resource Form** | `form(Form $form): Form` | `form(Schema $schema): Schema` |
| **Resource Table** | `table(Table $table): Table` | `table(Table $table): Table` (unchanged) |
| **Section Import** | `Filament\Forms\Components\Section` | `Filament\Schemas\Components\Section` |
| **Tabs Import** | `Filament\Forms\Components\Tabs` | `Filament\Schemas\Components\Tabs` |
| **Table Actions** | `Filament\Tables\Actions\EditAction` | `Filament\Actions\EditAction` |
| **Bulk Actions** | `Filament\Tables\Actions\DeleteBulkAction` | `Filament\Actions\DeleteBulkAction` |
| **Icons** | `HeroiconS*` | `HeroiconO*` |
| **Render Hooks** | `Filament::serving()` | `$panel->renderHook()` |
| **Livewire Events** | `$emit` | `$dispatch` |
| **Wire Model** | `wire:model.defer` | `wire:model` |

### Step-by-Step Migration Checklist

#### Step 1: Update Resource Form Method

**Before (v3):**
```php
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Details')
                ->schema([
                    TextInput::make('name'),
                ]),
        ]);
}
```

**After (v5):**
```php
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
            Section::make('Details')
                ->schema([
                    TextInput::make('name'),
                ]),
        ]);
}
```

#### Step 2: Update Table Actions

**Before (v3):**
```php
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

public static function table(Table $table): Table
{
    return $table
        ->columns([/* ... */])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}
```

**After (v5):**
```php
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

public static function table(Table $table): Table
{
    return $table
        ->columns([/* ... */])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}
```

#### Step 3: Update Tab Pages (ListRecords, EditRecord, etc.)

**Before (v3):**
```php
use Filament\Resources\Components\Tab;

public function getTabs(): array
{
    return [
        'all' => Tab::make('All'),
        'active' => Tab::make('Active'),
    ];
}
```

**After (v5):**
```php
use Filament\Schemas\Components\Tabs\Tab;

public function getTabs(): array
{
    return [
        Tab::make('All'),
        Tab::make('Active'),
    ];
}
```

#### Step 4: Update Blade Components

**Before (v3):**
```blade
<x-filament-forms::components.placeholder-image-cropped />
<x-filament-forms::sections.section>
```

**After (v5):**
```blade
<x-mw-filament::components.placeholder-image-cropped />
<x-mw-filament::sections.section>
```

#### Step 5: Update Livewire Event Dispatching

**Before (v3):**
```blade
wire:click="$emit('eventName', param)"
wire:model.defer="property"
```

**After (v5):**
```blade
wire:click="$dispatch('eventName', { param: value })"
wire:model="property"
```

#### Step 6: Update Test Annotations

**Before (v3):**
```php
/** @test */
public function it_can_create_user()
{
    // ...
}

public function test_user_creation()
{
    // ...
}
```

**After (v5):**
```php
use PHPUnit\Framework\Attributes\Test;

#[Test]
public function it_can_create_user(): void
{
    // ...
}
```

### Common Migration Pitfalls

1. **Section Import**: Remember to change `Filament\Forms\Components\Section` to `Filament\Schemas\Components\Section`

2. **Action Namespaces**: Table actions moved from `Filament\Tables\Actions\*` to `Filament\Actions\*`

3. **Icon Prefixes**: All `HeroiconS*` icons should be `HeroiconO*` (outline style is preferred)

4. **Form Method Return Type**: Must return `Schema` not `Form`

5. **Render Hooks**: Replace `Filament::serving()` with `$panel->renderHook()` in panel providers

6. **Tabs**: Use `Filament\Schemas\Components\Tabs\Tab` not `Filament\Resources\Components\Tab`

### Verification Commands

After migration, run these commands to verify your changes:

```bash
# Check for deprecated imports
grep -r "Filament\\\\Forms\\\\Components\\\\Section" Modules/YourModule --include="*.php"
grep -r "Filament\\\\Tables\\\\Actions\\\\EditAction" Modules/YourModule --include="*.php"
grep -r "Filament\\\\Resources\\\\Components\\\\Tab" Modules/YourModule --include="*.php"

# Check for old Blade components
grep -r "filament-forms::" Modules/YourModule --include="*.blade.php"

# Check for v2 Livewire patterns
grep -r "\$emit" Modules/YourModule --include="*.blade.php"
grep -r "wire:model.defer" Modules/YourModule --include="*.blade.php"

# Check for solid icons
grep -r "HeroiconS" Modules/YourModule --include="*.php"

# Run tests
./vendor/bin/phpunit Modules/YourModule/Tests
```

---

## Migration Status Summary

### Panel Providers
All 7 panel providers have been migrated to Filament v5:
- FilamentAdminPanelProvider (src/MicroweberPackages/Admin/Filament/)
- AdminPanelProvider (app/Providers/Filament/)
- BillingFilamentAdminPanelProvider (Modules/Billing/Providers/)
- BillingFilamentFrontendPanelProvider (Modules/Billing/Providers/)
- FilamentCheckoutPanelProvider (Modules/Checkout/Providers/)
- FilamentProfilePanelProvider (Modules/Profile/Providers/)
- NewsletterFilamentAdminPanelProvider (Modules/Newsletter/Providers/)

### Resources
- 35+ Resources using `form(Schema $schema): Schema` pattern
- 38+ Resources using `table(Table $table): Table` pattern
- All table actions migrated to `Filament\Actions\*` namespace
- All section imports updated to `Filament\Schemas\Components\Section`
- All tabs imports updated to `Filament\Schemas\Components\Tabs`

### Relation Managers
- 7 relation managers updated to v5 action imports
- Bulk actions migrated to new namespace

### Custom Pages
- 66 custom Filament Pages audited and verified v5 compatible
- All using correct traits and base classes

### Tests
- 328+ test files migrated from `@test` annotations to `#[Test]` attributes
- All test methods now use `void` return type
- All test files extend `Tests\TestCase`

### Deprecated Patterns Removed
- No `Filament::serving()` calls remaining
- No `Form::schema()` patterns remaining
- No `HeroiconS*` icons remaining
- No `Tab::make()` in resources (converted to `getTabs()`)
- `filament-forms::` Blade components migrated to `mw-filament::`

### Livewire v3
- All `$emit` replaced with `$dispatch`
- All `wire:model.defer` replaced with `wire:model`

---

## Troubleshooting Common Filament v5 Migration Issues

This section documents real errors encountered during the Microweber Filament v3 → v5 migration, with root causes and fixes.

---

### 1. `Class "Filament\Resources\Components\Tab" not found`

**Error:**
```
Class "Filament\Resources\Components\Tab" not found
```

**Root Cause:** The `Tab` class moved from `Filament\Resources\Components\Tab` to `Filament\Schemas\Components\Tabs\Tab` in v5.

**Fix:**
```php
// BEFORE (v3)
use Filament\Resources\Components\Tab;

// AFTER (v5)
use Filament\Schemas\Components\Tabs\Tab;
```

**Automated fix:**
```bash
vendor/bin/rector process --config=rector-filament.php --dry-run
```

---

### 2. `Class "Filament\Forms\Components\Section" not found`

**Error:**
```
Class "Filament\Forms\Components\Section" not found
```

**Root Cause:** Layout components (`Section`, `Tabs`, `Fieldset`, `Grid`) moved from `Filament\Forms\Components\` to `Filament\Schemas\Components\` in v5. Form *field* components like `TextInput`, `Select`, `Toggle` remain in `Filament\Forms\Components\`.

**Fix:**
```php
// Layout components → Schemas namespace
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

// Form fields stay in Forms namespace
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
```

**Key rule:** If it's a *container* (Section, Tabs, Grid, Fieldset), use `Filament\Schemas\Components\`. If it's a *field* (TextInput, Select, Toggle, FileUpload), use `Filament\Forms\Components\`.

---

### 3. `Class "Filament\Forms\Components\Livewire" not found`

**Error:**
```
Class "Filament\Forms\Components\Livewire" not found
```

**Root Cause:** The `Livewire` component moved to the Schemas namespace in v5.

**Fix:**
```php
// BEFORE (v3)
use Filament\Forms\Components\Livewire;

// AFTER (v5)
use Filament\Schemas\Components\Livewire;
```

---

### 4. Table actions not rendering or wrong namespace

**Error:**
```
Class "Filament\Tables\Actions\EditAction" not found
```

**Root Cause:** Table actions moved from `Filament\Tables\Actions\*` to `Filament\Actions\*` in v5.

**Fix:**
```php
// BEFORE (v3)
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

// AFTER (v5)
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
```

**Note:** This also applies to relation managers — their table actions use the same `Filament\Actions\*` namespace.

---

### 5. `form()` method signature type error

**Error:**
```
Declaration of SomeResource::form(Filament\Forms\Form $form): Filament\Forms\Form
must be compatible with Filament\Resources\Resource::form(Filament\Schemas\Schema $schema): Filament\Schemas\Schema
```

**Root Cause:** The `form()` method signature changed from `Form` to `Schema` in v5.

**Fix:**
```php
// BEFORE (v3)
use Filament\Forms\Form;

public static function form(Form $form): Form
{
    return $form->schema([...]);
}

// AFTER (v5)
use Filament\Schemas\Schema;

public static function form(Schema $schema): Schema
{
    return $schema->components([...]);
}
```

**Note:** Inside the `form()` method, `->schema([...])` on the top-level `$schema` object becomes `->components([...])`. However, `->schema([...])` on nested components (Section, Tabs, etc.) remains the same.

---

### 6. `BadgeColumn` class not found

**Error:**
```
Class "Filament\Tables\Columns\BadgeColumn" not found
```

**Root Cause:** `BadgeColumn` was removed in v5. Use `TextColumn` with the `->badge()` modifier instead.

**Fix:**
```php
// BEFORE (v3)
use Filament\Tables\Columns\BadgeColumn;

BadgeColumn::make('status')
    ->colors(['success' => 'active', 'danger' => 'inactive']);

// AFTER (v5)
use Filament\Tables\Columns\TextColumn;

TextColumn::make('status')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'active' => 'success',
        'inactive' => 'danger',
        default => 'gray',
    });
```

---

### 7. `Table::with()` / eager loading not working

**Error:**
```
Method Filament\Tables\Table::with() does not exist.
```

**Root Cause:** `Table::with()` for eager loading was replaced with `modifyQueryUsing()` in v5.

**Fix:**
```php
// BEFORE (v3)
$table->with(['author', 'category']);

// AFTER (v5)
$table->modifyQueryUsing(fn ($query) => $query->with(['author', 'category']));
```

---

### 8. `groupedBulkActions()` method not found

**Error:**
```
Method Filament\Tables\Table::groupedBulkActions() does not exist.
```

**Root Cause:** `groupedBulkActions()` was renamed to `bulkActions()` with `BulkActionGroup` in v5.

**Fix:**
```php
// BEFORE (v3)
$table->groupedBulkActions([
    DeleteBulkAction::make(),
]);

// AFTER (v5)
$table->bulkActions([
    BulkActionGroup::make([
        DeleteBulkAction::make(),
    ]),
]);
```

---

### 9. `Filament::serving()` deprecated

**Error:** No runtime error, but the callback registered via `Filament::serving()` is never called.

**Root Cause:** `Filament::serving()` was removed in v5. Navigation registration and render hooks must be configured in the panel provider.

**Fix:**
```php
// BEFORE (v3) — in a ServiceProvider
use Filament\Facades\Filament;

public function boot(): void
{
    Filament::serving(function () {
        Filament::registerNavigationItems([...]);
    });
}

// AFTER (v5) — in a PanelProvider or Plugin
public function panel(Panel $panel): Panel
{
    return $panel
        ->navigationItems([...])
        ->renderHook('panels::head.start', fn () => '<meta ...>');
}
```

If you need to register resources/pages from a module, use `FilamentRegistry`:
```php
FilamentRegistry::registerResource(self::class, MyResource::class);
```

---

### 10. `->reactive()` method deprecated

**Error:** No runtime error, but `->reactive()` is deprecated and will be removed in a future version.

**Root Cause:** `->reactive()` was renamed to `->live()` in Filament v3.1+ and is deprecated in v5.

**Fix:**
```php
// BEFORE
Select::make('type')->reactive();

// AFTER
Select::make('type')->live();
```

---

### 11. Filament routes not registered in test environment

**Error:**
```
InvalidArgumentException: Route [filament.admin.resources.orders.index] not defined.
```

**Root Cause:** Filament panel routes are registered during the panel boot process, which may not happen automatically in test environments. The panel must be set as "current" for routes to resolve.

**Fix:** Use the `InteractsWithFilamentPanel` trait in your test class:

```php
use Tests\TestCase;
use MicroweberPackages\Filament\Tests\Traits\InteractsWithFilamentPanel;
use PHPUnit\Framework\Attributes\Test;

class MyResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupFilamentPanel('admin');
    }

    #[Test]
    public function it_can_list_records(): void
    {
        $this->actingAsAdmin();
        // Routes are now available
    }
}
```

Or extend `FilamentResourceTestCase` which handles this automatically:

```php
use MicroweberPackages\Filament\Tests\FilamentResourceTestCase;

class MyResourceTest extends FilamentResourceTestCase
{
    protected string $resourceClass = MyResource::class;
    // setUp() and panel routing handled automatically
}
```

---

### 12. Blade component `filament-forms::*` not found

**Error:**
```
Unable to locate a class or view for component [filament-forms::components.placeholder-image-cropped]
```

**Root Cause:** Custom Blade components that previously lived under the `filament-forms::` namespace must be migrated to the `mw-filament::` namespace.

**Fix:**
```blade
{{-- BEFORE (v3) --}}
<x-filament-forms::sections.section>
<x-filament-forms::components.placeholder-image-cropped />

{{-- AFTER (v5) --}}
<x-mw-filament::sections.section>
<x-mw-filament::components.placeholder-image-cropped />
```

**Automated fix for Blade templates:**
```bash
php dev/rector-rules/blade-migrator.php Modules/YourModule/resources/views
```

---

### 13. Livewire `$emit` not dispatching events

**Error:** JavaScript events are not received by Livewire components after upgrading.

**Root Cause:** Livewire v3 (used with Filament v5) replaced `$emit` with `$dispatch`.

**Fix:**
```blade
{{-- BEFORE (Livewire v2) --}}
wire:click="$emit('openModal', { id: {{ $id }} })"
wire:model.defer="name"

{{-- AFTER (Livewire v3) --}}
wire:click="$dispatch('openModal', { id: {{ $id }} })"
wire:model="name"
```

**Note:** `wire:model` in Livewire v3 is deferred by default (equivalent to `wire:model.defer` in v2). Use `wire:model.live` if you need real-time updates (equivalent to `wire:model` in v2).

---

### 14. `getDescription()` must be static

**Error:**
```
Cannot make non static method SomeResource::getDescription() static
```

**Root Cause:** In Filament v5, certain resource metadata methods (`getDescription()`, `getNavigationLabel()`, etc.) must be declared `static`.

**Fix:**
```php
// BEFORE (v3)
public function getDescription(): string
{
    return 'Manage coupons';
}

// AFTER (v5)
public static function getDescription(): string
{
    return 'Manage coupons';
}
```

---

### 15. PHPStan errors for Eloquent model properties

**Error:**
```
Access to an undefined property App\Models\Accordion::$title.
```

**Root Cause:** PHPStan cannot detect Eloquent magic properties without proper type hints. This isn't a Filament issue per se, but surfaces frequently during migration when adding static analysis.

**Fix:** Add `@property` annotations to your models:
```php
/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $created_at
 */
class Accordion extends Model
{
    // ...
}
```

Or install Larastan for automatic Eloquent method/property recognition:
```bash
composer require --dev larastan/larastan
```

---

### Quick Diagnostic Commands

Run these to find remaining migration issues in your module:

```bash
# Find ALL deprecated v3 patterns in one command
./dev/rector-rules/analyze-filament-migration.sh Modules/YourModule

# Check deprecated imports
grep -rn "Filament\\\\Forms\\\\Components\\\\Section" Modules/YourModule --include="*.php"
grep -rn "Filament\\\\Forms\\\\Components\\\\Tabs" Modules/YourModule --include="*.php"
grep -rn "Filament\\\\Forms\\\\Components\\\\Livewire" Modules/YourModule --include="*.php"
grep -rn "Filament\\\\Resources\\\\Components\\\\Tab" Modules/YourModule --include="*.php"
grep -rn "Filament\\\\Tables\\\\Actions\\\\" Modules/YourModule --include="*.php"

# Check deprecated Blade patterns
grep -rn "filament-forms::" Modules/YourModule --include="*.blade.php"
grep -rn "\\\$emit" Modules/YourModule --include="*.blade.php"
grep -rn "wire:model.defer" Modules/YourModule --include="*.blade.php"

# Check deprecated PHP patterns
grep -rn "BadgeColumn" Modules/YourModule --include="*.php"
grep -rn "::serving(" Modules/YourModule --include="*.php"
grep -rn "->reactive()" Modules/YourModule --include="*.php"
grep -rn "->with(\[" Modules/YourModule --include="*.php" | grep -i table
grep -rn "groupedBulkActions" Modules/YourModule --include="*.php"

# Run automated PHP migration (dry run first!)
vendor/bin/rector process Modules/YourModule --config=rector-filament.php --dry-run
```

---

### Getting Help

- **Filament v5 Upgrade Guide:** [filamentphp.com/docs/5.x/upgrade-guide](https://filamentphp.com/docs/5.x/upgrade-guide)
- **Rector Rules:** See `dev/rector-rules/README.md` for automated migration tools
- **Module Testing Guide:** See `docs/testing/module-testing-guide.md` for test setup
- **Filament Discord:** [filamentphp.com/discord](https://filamentphp.com/discord)

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2026-03-04 | Initial architecture decision document |
| 1.1 | 2026-03-07 | Added Module Developer Upgrade Guide and Migration Status Summary |
| 1.2 | 2026-03-12 | Added Troubleshooting section for common v5 migration issues |
