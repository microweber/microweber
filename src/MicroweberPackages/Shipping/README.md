# Shipping Manager

This package is responsible for managing shipping methods.

To add new Shipping Method, you need to create a new class that extends `MicroweberPackages\Shipping\ShippingMethod` and implement the `cost` method.

```php
// In YourServiceProvider.php in register method add this
public function register()
    {
    $this->app->resolving('shipping_method_manager', function (\Modules\Shipping\ShippingMethodManager $shippingManager) {
        $shippingManager->extend('pickup', function () {
            return new \Modules\Shipping\Drivers\PickupFromAddress();
        });
    });
}
 
```


 
