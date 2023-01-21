# Shipping Manager

This package is responsible for managing shipping methods.

To add new Shipping Method, you need to create a new class that extends `MicroweberPackages\Shipping\ShippingMethod` and implement the `cost` method.

```php
// In YourServiceProvider.php in register method add this
public function register()
    {
    $this->app->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager) {
        $shippingManager->extend('pickup', function () {
            return new \MicroweberPackages\Shipping\Providers\PickupDriver();
        });
    });
}
```
