# Filament v5 Migration: Panel Architecture Decision

**Date:** March 04, 2026  
**Decision:** Multi-Panel Architecture (Retain and Modernize)  
**Decision Maker:** Senior Developer / Tech Lead  
**Status:** ✅ **APPROVED**

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
- [ ] Update `FilamentAdminPanelProvider` to v5 fluent API
- [ ] Standardize navigation groups
- [ ] Verify `->default()` is set
- [ ] Confirm middleware stack
- [ ] Test resource discovery

### Billing Admin (`admin-billing`)
- [ ] Keep separate panel for isolation
- [ ] Update to extend modernized `FilamentAdminPanelProvider`
- [ ] Verify `admin-billing` path
- [ ] Test subscription resource CRUD

### Newsletter Admin (`admin-newsletter`)
- [ ] Update `NewsletterFilamentAdminPanelProvider`
- [ ] Verify campaign resource migration
- [ ] Test widget rendering

### Customer Panels (billing, profile, checkout)
- [ ] Keep separate for security
- [ ] Update to v5 PanelProvider base
- [ ] Verify frontend themes
- [ ] Test public access (checkout)
- [ ] Test authenticated access (billing, profile)

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
