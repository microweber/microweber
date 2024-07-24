# Payment Manager

This package is responsible for managing  payment methods.

To add new payment method you need to create a new class that extends `MicroweberPackages\Payment\PaymentMethod` and implement the abstract methods.

## Add new provider

```php
// In YourServiceProvider.php in register method add this
public function register()
{
    $this->app->resolving('payment_method_manager', function (PaymentMethodManager $paymentManager) {
        $paymentManager->extend('pay_on_delivery', function () {
            return new \MicroweberPackages\Payment\Drivers\PayOnDelivery();
        });
    });

}
```



## Publish assets

```sh
php artisan vendor:publish --tag=microweber-packages/payment
```
 
