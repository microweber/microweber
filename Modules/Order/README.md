# Order

Order management for e-commerce. Handles the full order lifecycle including status tracking, refunds, cancellations, shipping tracking, and status history.

## Key Features

- Order lifecycle: New, Processing, Shipped, Delivered, Completed, Cancelled, Refunded
- Order status history audit trail
- Refund management
- Cancellation with reason tracking
- Shipping tracking number support
- Anonymous client orders (guest checkout)
- OrderManager and OrderRepository for programmatic access

## Key Classes

| Class | Purpose |
|---|---|
| `Repositories\OrderManager` | Order operations (`app('order_manager')`) |
| `Repositories\OrderRepository` | Query layer (`app('order_repository')`) |
| `Models\Order` | Core order model |
| `Models\OrderStatusHistory` | Status change audit log |
| `Models\OrderRefund` | Refund records |
| `Models\OrderCancelReason` | Cancellation reason catalog |
| `Models\OrderAnonymousClient` | Guest checkout client data |
| `Enums\OrderStatus` | Status enum: New, Processing, Shipped, Delivered, Completed, Cancelled, Refunded |

## Events

- `OrderIsCreating` / `OrderIsUpdating` -- before save
- `OrderWasCreated` / `OrderWasUpdated` -- after save
- `OrderWasPaid` -- triggers inventory updates and stats tracking
- `OrderWasCanceled` / `OrderWasDeleted` / `OrderWasDestoyed` -- cancellation/deletion

## Database Tables

- `cart_orders` -- main orders table (with user_id, shipping tracking)
- `order_status_history` -- status change log
- `order_refunds` -- refund records
- `order_cancel_reasons` -- cancellation reasons

## Admin Panel (Filament)

- **OrderResource** -- full order management CRUD

## API Endpoints

Routes defined in `routes/api.php` and `routes/admin.php`.

## Usage

```php
$orderManager = app('order_manager');
$orders = app('order_repository')->all();
$order = \Modules\Order\Models\Order::find(1);
$order->status; // OrderStatus enum
```
