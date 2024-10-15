# Tax Module for Microweber

## Overview
The Tax module is designed to manage tax types and calculations within the Microweber framework. It allows for the creation, editing, and deletion of tax types, as well as the application of these taxes during checkout.

## Features
- Create, read, update, and delete tax types.
- Support for fixed and percentage-based tax rates.
- Integration with the checkout process to apply taxes automatically.

## Installation
To install the Tax module, follow these steps:

1. Clone the repository or download the module files.
2. Place the module in the `Modules` directory of your Microweber installation.

## Run module migrations
```sh
php artisan module:migrate Tax
```

## Publish module assets
```sh
php artisan module:publish Tax
```

## Usage
To use the Tax module, you can access the tax management features through the admin panel. You can create new tax types, edit existing ones, and view the applied taxes during the checkout process.

## Configuration
You can configure the module settings in the `config/config.php` file. Adjust the settings as needed for your application.

## License
This module is part of the Microweber framework and is subject to the same licensing terms. For full license information, please refer to the [LICENSE](https://github.com/microweber/microweber/blob/master/LICENSE) file.
