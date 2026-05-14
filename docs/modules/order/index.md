# Order Module

The Order module is the **persistence and fulfillment** layer of the e-commerce sub-cluster. It owns the `cart_orders` table where every paid (or in-progress) order lives, the seven-state lifecycle the order passes through after [Checkout](/modules/checkout/) places it, the audit trails (status history + refunds), and the admin surface where staff manage post-conversion fulfillment.

> **TL;DR** — Order is the durable record. [Cart](/modules/cart/) holds the in-progress state; [Checkout](/modules/checkout/) converts that state into an `Order` via `order_manager->place_order()`; from there Order owns the row, the `OrderStatus` enum transitions, the `payments`/`order_refunds`/`order_status_history` joins, and the Filament admin CRUD. Eight events fire across the lifecycle so other modules (inventory, mail, accounting) react without coupling.

---

## What this module owns

| Concern | Storage / surface |
|---|---|
| Durable order row | `cart_orders` table (44 columns) |
| Seven-state lifecycle | `OrderStatus` enum (New, Processing, Shipped, Delivered, Completed, Cancelled, Refunded) |
| Status-change audit | `order_status_history` table (auto-populated by `Order::updating()` boot hook) |
| Refunds | `order_refunds` table + `OrderRefund` model |
| Cancellation reasons | `order_cancel_reasons` table |
| Anonymous-buyer metadata | `OrderAnonymousClient` model |
| Order-side admin CRUD | `OrderResource` (3-tab Filament form + relation managers + widgets) |
| Order-side REST API | `/api/order/*` (deprecated) and `/api/module/orders/*` (current) |
| Order export / import | `OrderExporter` (CSV/Excel) + `OrderImporter` |
| Stats & analytics | `OrderStatsService` (revenue, count, best sellers, time series) |
| Eight lifecycle events | `OrderIsCreating`, `OrderIsUpdating`, `OrderWasCreated`, `OrderWasUpdated`, `OrderWasDeleted`, `OrderWasPaid`, `OrderWasCanceled` |
| New-order admin notification | `NewOrderNotification` (database + mail channels) |

What this module does **NOT** own:

- Cart line items — they live in the `cart` table owned by the [Cart module](/modules/cart/); Order links to them via `Order::cart()` (HasCartItems trait).
- The checkout flow — wizard, payment gateway round-trip, return URL verification → [Checkout module](/modules/checkout/).
- Payment records — `payments` table owned by Payment module; Order has a `morphMany` to them.
- Inventory deduction on payment — Product module listens for `OrderWasPaid` and adjusts stock.
- Customer profile — Customer model owned elsewhere; Order `BelongsTo` it.
- Shipping cost calculation — Shipping module (Order just persists `shipping_amount` denormalized).
- Tax calculation — Cart/Tax modules (Order persists `taxes_amount` denormalized).
- Mail template rendering — MailTemplate module; Order's `NewOrderNotification` looks up templates by id.

---

## Architectural fact: persistence is the boundary, not the kitchen

Order is **deliberately thin on business logic**. The audit-worthy facts are:

- `OrderService::place_order()` is the only path that creates an Order. Direct `Order::create()` calls in app code are anti-pattern — they bypass cart-linking and the `OrderIsCreating` event chain.
- Status transitions are **admin-driven, not state-machine enforced**. An admin can set any status to any status via the Filament form. The `OrderStatusHistory` audit trail captures every transition automatically (via `Order::updating()` boot hook) so post-hoc reconstruction stays possible.
- Payment is **polymorphic**. `Order::payments()` is a `MorphMany` over `payments.rel_type = Order::class`. This means Order doesn't care which gateway processed the charge — it just sees its own `rel_id` matches.
- The seven-state enum is **shape-stable**. Adding a new state means adding to `OrderStatus` + reseeding the badge widget + adding migration data for in-flight orders. Don't shortcut this.

---

## The seven-state lifecycle

```
                ┌────────────────────────┐
                │  Cart (state manager)  │
                └────────────┬───────────┘
                             ↓
                   Checkout::place_order()
                             ↓
                ┌────────────────────────┐
   New ────►   │  Processing  ──►  Shipped  ──►  Delivered  ──►  Completed  │
                │      │                                                     │
                │      ├──────►  Cancelled                                   │
                │      └──────►  Refunded                                    │
                └────────────────────────┘
```

| State | Filament colour | Trigger |
|---|---|---|
| `New` | info | `OrderService::place_order()` default |
| `Processing` | warning | admin marks as in-fulfilment |
| `Shipped` | success | admin sets shipping_tracking_number + status |
| `Delivered` | success | admin confirms delivery (or carrier webhook updates it) |
| `Completed` | success | admin marks done (post-delivery grace period elapsed) |
| `Cancelled` | danger | admin cancels (creates `OrderCancelReason` row optionally) |
| `Refunded` | danger | admin creates `OrderRefund` row and sets status |

There is **no enforcement** of the arrows above — they are the recommended flow, not a rule the model enforces. An admin can move `New → Refunded` directly if needed (e.g. duplicate order, refund before fulfillment).

---

## The eight lifecycle events

| Event | Constructor | Fired by | Typical listeners |
|---|---|---|---|
| `OrderIsCreating` | `(array $attributes)` | `Order::creating()` | pre-create validators (none yet) |
| `OrderWasCreated` | `(Order $order)` | `Order::created()` | `OrderCreatedListener` → admin notification |
| `OrderIsUpdating` | `(Order $order)` | `Order::updating()` | future audit hook |
| `OrderWasUpdated` | `(Order $order)` | `Order::updated()` | analytics |
| `OrderWasDeleted` | `(Order $order)` | `Order::deleting()` | cleanup |
| `OrderWasPaid` | `(Order $order, $utmData = null)` | `OrderService::place_order()` when `is_paid = 1` | `OrderWasPaidListener` → mail; Product → inventory deduction |
| `OrderWasCanceled` | `(Order $order, $utmData = null)` | admin cancel action | refund processors, accounting |
| `OrderWasDestoyed` | *(typo, dead-event)* | not fired anywhere | n/a — flagged for cleanup |

`OrderWasPaid` is the canonical extension point. Listeners that fire on it are guaranteed: the row exists, the row has `is_paid = 1`, the payment_data JSON is set, and the cart items are linked.

---

## Surfaces

| Surface | Where | Audience |
|---|---|---|
| Filament admin CRUD | `Filament\Admin\Resources\OrderResource` | staff |
| Order detail page (3 tabs + 4 sidebar sections) | `OrderResource\Pages\EditOrder` | staff |
| Order list with filters + bulk actions | `OrderResource\Pages\ListOrders` | staff |
| Export to CSV/Excel | `Filament\Exports\OrderExporter` + admin route `/admin/order/export` | staff |
| Import from CSV/Excel | `Filament\Imports\OrderImporter` | staff |
| Stats widget (revenue, count, best sellers) | `Filament\Widgets\OrderStats` | staff (dashboard) |
| Headless API (read public, write scoped) | `/api/module/orders/*` (`OrdersApiController`) | mobile apps, SPAs |
| Legacy REST API | `/api/order/*` (`OrderApiController`) | older integrations, deprecated |
| New-order admin notification | `NewOrderNotification` (database + mail) | admin users |

---

## Where to next

- [Installation](./installation.md) — service provider, migrations, config keys, sibling-module dependencies.
- [Usage](./usage.md) — running place_order from Checkout, listening to events, status transitions, refunds, exports.
- [API](./api.md) — `OrderService`, `OrderManager`, `OrderRepository`, `OrderStatsService`, controllers, model, enum, events, helpers.
- [Examples](./examples.md) — query orders with filters, listen to `OrderWasPaid`, custom Filament action, monthly revenue report, refund flow.
- [Troubleshooting](./troubleshooting.md) — order not created, status history missing, payment_data not persisting, refund total mismatch.
