# Customer Module for Microweber

## Overview
The Customer module is part of the Microweber framework, designed to manage customer data and interactions within the application.

## Features
- Create, read, update, and delete customer records.
- Manage customer addresses.
- Integration with order management.

## Installation
To install the Customer module, follow these steps:

1. Clone the repository or download the module files.
2. Place the module in the `Modules` directory of your Microweber installation.

## Run module migrations
To set up the database tables required for the Customer module, run the following command:

```sh
php artisan module:migrate Customer
```

## Publish module assets
To publish the module's assets, use the following command:

```sh
php artisan module:publish Customer
```

## Configuration
You can configure the Customer module by editing the `config/config.php` file within the module directory.

## Usage
To use the Customer model, you can follow these examples:

### Creating a Customer
```php
use Modules\Customer\Models\Customer;

$customer = Customer::create([
    'name' => 'John Doe',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '1234567890',
    'email' => 'john.doe@example.com',
    'active' => 1,
    'user_id' => 1,
    'currency_id' => 1,
    'company_id' => 1
]);
```

### Retrieving a Customer
```php
$customer = Customer::find(1);
```

### Updating a Customer
```php
$customer = Customer::find(1);
$customer->email = 'new.email@example.com';
$customer->save();
```

### Deleting a Customer
```php
$customer = Customer::find(1);
$customer->delete();
```

### Adding an Address to a Customer
To add an address to a customer, you can use the following example:

```php
use Modules\Address\Models\Address;

$address = Address::create([
    'name' => 'Home',
    'address_street_1' => '123 Main St',
    'address_street_2' => 'Apt 4B',
    'city' => 'Anytown',
    'state' => 'Anystate',
    'country_id' => 1,
    'zip' => '12345',
    'phone' => '1234567890',
    'type' => Address::BILLING_TYPE,
    'customer_id' => $customer->id // Assuming $customer is the customer instance
]);
```

## Testing
To run the tests for the Customer module, use the following command:

```sh
php artisan test
```

## License
This module is licensed under the MIT License. See the [LICENSE](https://github.com/microweber/microweber/blob/master/LICENSE) file for more information.
