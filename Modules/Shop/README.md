# Shop

Core e-commerce framework module. Provides the storefront display component, shop-wide settings, and coordinates the Product, Order, Payment, Invoice, Shipping, and Coupon modules.

## Key Features

- Configurable storefront rendered as a Livewire component
- Centralized shop settings in the admin panel
- ShopManager singleton for shop-wide operations
- Integration point for all e-commerce sub-modules

## Key Classes

| Class | Purpose |
|---|---|
| `Services\ShopManager` | Central shop operations (`app('shop_manager')`) |
| `Livewire\ShopComponent` | Frontend shop display (tag: `module-shop`) |
| `Microweber\ShopModule` | Microweber module system registration |

## Admin Panel (Filament)

- **ShopModuleSettings** -- general shop configuration (currency, tax, display)

## Related Modules

| Module | Responsibility |
|---|---|
| Product | Product catalog and inventory |
| Order | Order processing and tracking |
| Payment | Payment gateway integration |
| Invoice | Invoice generation |
| Cart | Shopping cart management |
| Checkout | Checkout flow |
| Shipping | Shipping methods and rates |
| Coupons | Discount codes |
| Currency | Multi-currency support |
| Tax | Tax calculations |

## Usage

```html
<module type="shop" />
```

```php
$shopManager = app('shop_manager');
```
