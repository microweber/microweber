# Refactoring Execution Plan — Filament 5 Admin Panel

**Date:** 2026-04-03
**Branch:** filament-5
**Prerequisite:** [REFACTOR-ASSESSMENT.md](REFACTOR-ASSESSMENT.md) — Go/No-Go: GO

---

## Current State Inventory

| File | Lines | Longest Method | External Callers |
|------|-------|---------------|-----------------|
| ContentResource.php | 1529 | `formArray()` — 555 lines | ContentTableList:44, AdminLiveEditPage:223 |
| OrderResource.php | 818 | `form()` — 240 lines | Filament framework only (no user code) |
| MenusList.php | 491 | `editAction()` — 169 lines | Filament framework only |
| MediaLibrary.php | 740 | `searchUnsplash()` — 59 lines | None (Livewire self-contained) |
| Settings.php | 312 | `getViewData()` — 167 lines | Filament framework only |

### Subclass Map (ContentResource)

| Subclass | File | Overrides Form? |
|----------|------|----------------|
| PageResource | Modules/Page/Filament/Resources/PageResource.php | No |
| PostResource | Modules/Post/Filament/Admin/Resources/PostResource.php | No |
| ProductResource | Modules/Product/Filament/Admin/Resources/ProductResource.php | No |

All 3 subclasses inherit `formArray()` via late-static binding. No form method overrides.

### External Callers of `formArray()`

| Caller | File | Line | Usage |
|--------|------|------|-------|
| ContentTableList | Modules/Content/Filament/ContentTableList.php | 44 | `ContentResource::formArray($params)` in `editFormArray()` |
| AdminLiveEditPage | src/.../LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php | 223 | `ContentResource::formArray(['contentType' => $contentType])` |

Internal-only methods (no external callers): `productDetailsFormArray()`, `seoFormArray()`, `advancedSettingsFormArray()`

---

## Target State

### ContentResource.php — After Refactoring

```
ContentResource.php (~600 lines, down from 1529)
├── formArray($params)          — ~80 lines (orchestrator only, delegates to section builders)
��── form(Schema $schema)        — ~5 lines (unchanged)
├── table(Table $table)         — ~200 lines (unchanged, separate concern)
├── getListTableColumns()       — existing
├── getTableFilters()           — existing
└── static section builders:
    ├── contentBodySection()    — ~40 lines (title, slug, content editor)
    ├── publishedSection()      — ~30 lines (is_active toggle, posted_at)
    ├── categorySection()       — ~50 lines (category tree, blog filter)
    ├── parentPageSection()     — ~30 lines (mw-tree component)
    ├── postFieldsSection()     — ~25 lines (excerpt, author)
    ├── productDetailsSection() — existing productDetailsFormArray() renamed
    ├── seoSection()            — existing seoFormArray() renamed
    ├── advancedSection()       — existing advancedSettingsFormArray() renamed
    └── templateSection()       — ~20 lines (template/layout chooser)
```

**Key constraint:** `formArray()` signature and return type must stay identical — 2 external callers depend on it.

### OrderResource.php — After Refactoring

```
OrderResource.php (~600 lines, down from 818)
├── form(Schema $schema)        — ~30 lines (orchestrator, delegates to tab builders)
├── getLatestPayment($record)   — ~5 lines (replaces 5x duplicated query)
├── orderDetailsTab()           — ~60 lines
├── orderItemsTab()             — ~40 lines
├── shippingTab()               — ~50 lines
├── paymentTab()                — ~50 lines
├── refundsTab()                — ~30 lines
├── statusTimelineTab()         — ~20 lines
└── table(Table $table)         — ~200 lines (unchanged)
```

### MenusList.php — After Refactoring

```
MenusList.php (~400 lines, down from 491)
├── findMenuOrFail($id)         — ~5 lines (replaces 5x Menu::find())
├── getMenuItemFormSchema()     — ~60 lines (extracted from editAction)
├── editAction()                — ~80 lines (down from 169)
└── addMenuItemAction()         — reduced via shared schema
```

---

## Execution Steps (Additive Order)

Each step ends with "verify tests pass." Steps are independent commits.

### Phase A: ContentResource (7 steps)

**A1. Extract `contentBodySection()` from `formArray()`**
- Add new static method `contentBodySection($params)` returning the title/slug/content_body fields array
- Call it from `formArray()` replacing the inline fields
- Verify: 70 Content tests pass

**A2. Extract `publishedSection()` from `formArray()`**
- Add new static method for is_active toggle + posted_at date picker
- Call from `formArray()` replacing inline code
- Verify: 70 Content tests pass

**A3. Extract `categorySection()` from `formArray()`**
- Add new static method for category tree + blog filter logic
- Call from `formArray()`
- Verify: 70 Content tests pass

**A4. Extract `parentPageSection()` from `formArray()`**
- Add new static method for mw-tree parent page selector
- Call from `formArray()`
- Verify: 70 Content tests pass

**A5. Extract `postFieldsSection()` from `formArray()`**
- Add new static method for excerpt + author fields (post-only)
- Call from `formArray()`
- Verify: 70 Content tests pass

**A6. Extract `templateSection()` from `formArray()`**
- Add new static method for template/layout chooser tab
- Call from `formArray()`
- Verify: 70 Content tests pass

**A7. Clean up `formArray()` — rename old methods**
- Rename `productDetailsFormArray()` → `productDetailsSection()` (internal only, safe)
- Rename `seoFormArray()` → `seoSection()` (internal only, update 2 call sites)
- Rename `advancedSettingsFormArray()` → `advancedSection()` (internal only, update 2 call sites)
- Remove unused variables, dead code
- Verify: 70 Content tests + 2 external callers still work

### Phase B: OrderResource (3 steps)

**B1. Extract `getLatestPayment()` helper**
- Add private method `getLatestPayment($record)` returning the latest payment
- Replace 5x duplicated `$record->payments()->latest()->first()` calls
- Verify: 80 Order tests pass

**B2. Extract form tab sections**
- Add `orderDetailsTab()`, `orderItemsTab()`, `shippingTab()`, `paymentTab()`
- Call from `form()` replacing inline tab contents
- Verify: 80 Order tests pass

**B3. Extract refund and timeline tabs**
- Add `refundsTab()`, `statusTimelineTab()`
- Call from `form()`
- Verify: 80 Order tests pass

### Phase C: MenusList (2 steps)

**C1. Extract `findMenuOrFail()` and `getMenuItemFormSchema()`**
- Add `findMenuOrFail($id)` helper replacing 5x `Menu::find()`
- Add `getMenuItemFormSchema()` for shared form fields
- Verify: 7 Menu tests pass

**C2. Simplify `editAction()` using extracted methods**
- Rewrite `editAction()` to use `findMenuOrFail()` and `getMenuItemFormSchema()`
- Reduce from 169 to ~80 lines
- Verify: 7 Menu tests pass + manual UI check

### Phase D: MediaLibrary (1 step)

**D1. Extract `isImageMedia()` helper and improve naming**
- Add `isImageMedia($media)` helper for repeated type checks
- Rename generic `$data` variables to descriptive names
- Verify: 44 MediaLibrary tests pass

---

## Success Criteria

| Metric | Before | Target | Measurement |
|--------|--------|--------|-------------|
| ContentResource `formArray()` lines | 555 | <100 | `wc -l` on method |
| ContentResource total lines | 1529 | <900 | `wc -l` on file |
| OrderResource `form()` lines | 240 | <40 | `wc -l` on method |
| OrderResource total lines | 818 | <650 | `wc -l` on file |
| MenusList `editAction()` lines | 169 | <90 | `wc -l` on method |
| Payment query duplication | 5x | 1x | `grep` count |
| Menu::find duplication | 5x | 1x | `grep` count |
| Test suite | All pass | All pass | `./run-tests.sh` |
| External caller compatibility | 2 callers work | 2 callers work | Manual verify |

---

## Risk Mitigation

1. **Additive order:** Add new methods first, then redirect callers, then clean up. Never delete before verifying.
2. **One commit per step:** Each step is independently revertable.
3. **Late-static binding preserved:** All extracted methods use `static::` for subclass compatibility.
4. **Signature preservation:** `formArray($params)` return type and signature unchanged — external callers unaffected.
5. **Run full suite after Phase A:** The Content module touches many areas.
