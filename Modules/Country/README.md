# Country Module

The Country module provides functionality to manage country data, including country codes, names, and phone codes. It supports loading country data from CSV and JSON files.

## Installation

1. Clone the repository or download the module files.
2. Place the module in the `Modules` directory of your Laravel application.

 
## Run Module Migrations

To set up the database tables for the Country module, run the following command:

```sh
php artisan module:migrate Country
```

## Usage

You can use the `CountriesHelper` class to access country data:

```php
use Modules\Country\Support\CountriesHelper;

// Get a list of country names
$countries = CountriesHelper::countriesList();

// Get country data from JSON
$countriesJson = CountriesHelper::countriesListFromJson();
```

## Testing

To run the tests for the Country module, use the following command:

```sh
php artisan test
```

This will execute all the tests defined in the `tests` directory, including model tests and helper function tests.

## License

This module is licensed under the MIT License. See the LICENSE file for more information.
