# Migration Inventory

> **Cycle-113 / AI-119 / TICKET-BA (2026-05-09)** — per-module
> inventory of the Filament / Livewire / legacy admin surface.
> Helps identify migration debt + plan the next sweep.

---

## Status legend

- **Filament** — fully migrated to Filament v5 (the current target).
- **Livewire** — uses Livewire v4 components but not Filament.
- **Legacy** — pre-Livewire admin (jQuery + Blade).
- **Mixed** — has surfaces in 2+ states.

---

## Module inventory

| Module | Admin surface | Status | Notes |
|---|---|---|---|
| `Cart` | Cart manager, item rows | Mixed | Front-end primary; admin via Order. |
| `Checkout` | Checkout wizard | Livewire | Filament wrap pending. |
| `Coupons` | Coupon CRUD | Filament | cycle-88 admin-on-behalf-of fix. |
| `Customer` | CustomerResource | Filament | cycle-96 phone/website validation. |
| `Order` | OrderResource | Filament | cycle-96 phone validation. |
| `Product` | ProductResource + variants | Filament | Inventory mgmt module. |
| `Shop` | Shop filters | Livewire | Front-end primary. |
| `Posts`, `Content` | Posts + Content resource | Filament | cycle-89/90 skin sweep. |
| `Categories` | Categories tree | Filament | cycle-93 raw-PHP→Blade. |
| `Menu` | Menu builder | Mixed | Knp\Menu + Filament wrapper. |
| `Newsletter` | Campaigns + subscribers | Filament | cycle-30+ HMAC + allowlist. |
| `Faq` | FAQ list | Mixed | Filament admin; legacy public skin. |
| `Accordion` | Accordion list | Mixed | Filament admin; cycle-92 BS5 skin. |
| `Testimonials` | Testimonials list | Filament | cycle-103 CSP fixes. |
| `Teamcard` | Teamcard list | Filament | cycle-96 website validation. |
| `Gallery` | Gallery layouts | Legacy | Migration pending. |
| `Forms` | Form builder | Legacy | Migration pending — high priority. |
| `Search` | Site search | Livewire | Filament wrap pending. |
| `Captcha` | Captcha config | Filament | cycle-43 hardening. |
| `Media` | Media library | Filament | cycle-94 image-picker. |
| `MediaLibrary` | MediaLibraryPage | Filament | cycle-105 + 108 AI-117. |
| `Layouts` | Layout picker | Filament | Live-edit primary. |
| `Billing` | Subscription plans | Filament | cycle-96 price validation. |
| `Payment` | Payment routes | Mixed | Gateway-specific. |
| `Invoice` | InvoiceResource | Filament | cycle-96 email/phone. |
| `Profile` | EditProfile + Register | Filament | cycle-96 email validation. |
| `Ai` | AI chat + image gen | Filament | cycle-94 picker AI tab. |
| `LiveEdit` (core) | AdminLiveEditPage | Filament | cycle-86/107 toolbar. |

---

## Known migration debt

### Forms module (legacy → Filament)

**Priority: High.** The Forms module's admin surface is jQuery +
Blade with no Livewire wrapper. Form-builder UX, submission inbox,
and field-config editor all need migration. Estimated effort:
medium (2-3 cycles).

### Gallery module (legacy → Filament)

**Priority: Medium.** Gallery item-management UI is legacy. The
public-facing gallery skins are fine; just the admin needs
migration.

### Live-Edit Vue surfaces

The Element Style Editor + Right Sidebar are Vue 3 mounted INSIDE
the Filament-rendered live-edit canvas. Not "legacy" per se but
they're a non-Filament tech island; migration to Filament-native
slots is a separate decision (likely never — Vue is the right
tool for the in-place style editor's state model).

---

## Migration template (per-module)

When you migrate a module's admin surface from legacy → Filament:

1. Create `Filament/Admin/Resources/<Name>Resource.php`.
2. Move table columns from the Livewire/Blade list to Filament
   `Tables\Columns\*`.
3. Move form fields to `Forms\Components\*` — declare validation
   chains per AI-85 / cycle-96.
4. Wire into the panel via the `FilamentRegistry::getPlugins()`
   discovery.
5. Drop the legacy admin route + view files.
6. Add a contract test pinning that the resource exists +
   declares the right resource model.
