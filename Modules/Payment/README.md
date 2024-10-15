# Payment Module for Microweber

## Overview
The Payment module provides a flexible and extensible way to manage payment providers in your application. It supports multiple payment methods, including PayPal, Stripe, and Pay on Delivery.

## Features
- Add, edit, and delete payment providers.
- Support for multiple payment methods.
- Integration with Livewire for dynamic forms.
- Event-driven architecture for payment processing.

## Installation
1. Clone the repository or download the module.
2. Place the module in the `Modules` directory of your Laravel application.

## Run module migrations
```sh
php artisan module:migrate Payment
```

## Publish module assets
```sh
php artisan module:publish Payment
```

## Usage
- Navigate to the Payment Providers section in the admin panel to manage your payment providers.
- Use the provided forms to set up new payment methods.

## Available Payment Providers
- **PayPal**: A widely used online payment system.
- **Stripe**: A powerful payment processing platform.
- **Pay on Delivery**: Allows customers to pay upon receiving their order.

## Contributing
Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License
This module is licensed under the MIT License.
