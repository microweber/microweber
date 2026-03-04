# Ticket: Livewire Component Registration Issue (LiveEditLivewireComponentsAccessTest)

**Status:** Open  
**Priority:** High  
**Created:** 2026-03-04  
**Related:** Filament v5 Migration  
**Assignee:** TBD

---

## Summary

The test `LiveEditLivewireComponentsAccessTest::testIfCanViewComponentAsAdmin` is failing with a Livewire component registration error. This is a pre-existing issue that blocks Phase 0 completion of the Filament v5 migration.

---

## Error Details

```
Component access error MicroweberPackages\LiveEdit\Http\Livewire\ModuleTemplateSelectComponent
Unable to find component: [microweber-live-edit::module-select-template]
Failed asserting that false is true.
```

**Location:** `src/MicroweberPackages/User/tests/UserLivewireComponentsAccessTest.php:60`

---

## Root Cause Analysis

### Current Implementation

In `LiveEditServiceProvider.php` (line 58):
```php
Livewire::component('microweber-live-edit::module-select-template', ModuleTemplateSelectComponent::class);
```

In `LiveEditLivewireComponentsAccessTest.php`:
```php
public $componentsList = [
    ModuleTemplateSelectComponent::class,
];
```

### The Problem

1. **Livewire v4 Registration Mismatch**: The component is registered with a custom name using `::` namespace syntax (`microweber-live-edit::module-select-template`), but the test passes the **class name** (`ModuleTemplateSelectComponent::class`) to `Livewire::test()`.

2. **Livewire v4 Auto-Discovery**: In Livewire v4, when passing a class name to `Livewire::test()`, Livewire attempts to resolve the component using auto-discovery or the class-based registry. However, since the component was registered with a custom string name, the class-based lookup fails.

3. **Deprecated Component**: `ModuleTemplateSelectComponent` is marked as `@deprecated` and extends `ModuleSettingsComponent`, suggesting it may be legacy code from Livewire v3.

---

## Technical Details

### Affected Files

1. **Test File:**
   - `src/MicroweberPackages/LiveEdit/tests/LiveEditLivewireComponentsAccessTest.php`

2. **Component File:**
   - `src/MicroweberPackages/LiveEdit/Http/Livewire/ModuleTemplateSelectComponent.php`

3. **Service Provider:**
   - `src/MicroweberPackages/LiveEdit/Providers/LiveEditServiceProvider.php`

4. **Parent Classes:**
   - `src/MicroweberPackages/LiveEdit/Http/Livewire/ModuleSettingsComponent.php`
   - `src/MicroweberPackages/Admin/Http/Livewire/AdminComponent.php`

### Component Registration Pattern (Livewire v4)

Livewire v4 supports two registration patterns:

1. **Auto-discovery** (recommended): Components in `app/Http/Livewire` are auto-registered.
2. **Manual registration**: Using `Livewire::component('name', ComponentClass::class)`

The issue arises when using manual registration with custom names, then trying to reference the component by class name.

---

## Proposed Solutions

### Option 1: Update Test to Use Component Name (Quick Fix)

Modify `LiveEditLivewireComponentsAccessTest.php` to test using the registered component name:

```php
public function testIfCanViewComponentAsAdmin()
{
    $this->actingAsAdmin();
    
    // Test using component name instead of class name
    Livewire::test('microweber-live-edit::module-select-template')
        ->assertOk();
}
```

**Pros:**
- Minimal changes
- Tests the actual component registration

**Cons:**
- Doesn't fix underlying architectural issue
- Test is less self-documenting

---

### Option 2: Register Component with Class Name (Recommended)

Update `LiveEditServiceProvider.php` to register components with their class names:

```php
// Option A: Register with class name as key
Livewire::component(ModuleTemplateSelectComponent::class, ModuleTemplateSelectComponent::class);

// Option B: Use Livewire v4 auto-discovery
// Move components to app/Livewire or add discovery path
```

**Pros:**
- Aligns with Livewire v4 conventions
- Enables class-based component resolution
- Cleaner architecture

**Cons:**
- Requires updating all references to the component
- May break existing blade templates using the old name

---

### Option 3: Fix Component Auto-Discovery

Update `LiveEditServiceProvider.php` to configure Livewire v4 auto-discovery for the package:

```php
public function boot()
{
    // Configure Livewire namespace for auto-discovery
    Livewire::componentNamespace('MicroweberPackages\\LiveEdit\\Http\\Livewire', 'microweber-live-edit');
}
```

**Pros:**
- Leverages Livewire v4 auto-discovery
- No manual registration needed
- Follows modern patterns

**Cons:**
- Requires testing all components
- Namespace configuration may conflict with existing registrations

---

### Option 4: Remove Deprecated Component

Since `ModuleTemplateSelectComponent` is marked as `@deprecated`, investigate if it can be removed entirely:

```php
// Check usage across codebase
grep -r "ModuleTemplateSelectComponent" --include="*.php" .
grep -r "microweber-live-edit::module-select-template" --include="*.php" --include="*.blade.php" .
```

**Pros:**
- Removes technical debt
- Simplifies codebase

**Cons:**
- Requires thorough dependency analysis
- Risk of breaking existing functionality

---

## Recommended Approach

**Short-term (Phase 0 unblocking):** Implement Option 1 to update the test to use the registered component name.

**Long-term (Phase 2 or later):** Implement Option 3 to properly configure Livewire v4 auto-discovery for the package, then migrate all components to use standard Livewire v4 patterns.

---

## Acceptance Criteria

- [ ] `LiveEditLivewireComponentsAccessTest` passes
- [ ] All Livewire components in LiveEdit package are accessible
- [ ] No breaking changes to existing functionality
- [ ] Documentation updated if component usage pattern changes

---

## Related Tasks

- Phase 0: Preparation & Infrastructure
- Phase 2: Per-Module Migration Tasks (LiveEdit Module)

---

## Notes

- This issue is blocking completion of Phase 0
- The test was already failing before Filament v5 migration (pre-existing issue)
- Component is deprecated but still in use
- Other components in the same provider may have similar issues

---

## References

1. [Livewire v4 Documentation - Component Registration](https://livewire.laravel.com/docs/components#manually-registering-components)
2. [Livewire v4 Upgrade Guide](https://livewire.laravel.com/docs/upgrading)
3. [Filament v5 Livewire Integration](https://filamentphp.com/docs/5.x/getting-started/installation)
