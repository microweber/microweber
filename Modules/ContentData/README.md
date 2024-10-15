# ContentData module for Microweber 

## Overview
The ContentData module provides a way to manage custom content data associated with various content types in the application. It allows for easy storage, retrieval, and manipulation of additional data fields.

## Features
- Store custom fields for content types.
- Retrieve custom fields easily.
- Delete custom fields when no longer needed.
- Support for multiple content types through polymorphic relationships.

## Installation
To install the ContentData module, follow these steps:

1. Clone the repository or download the module files.
2. Place the module in the `Modules` directory of your Laravel application.

## Run module migrations
After placing the module, run the following command to create the necessary database tables:

```sh
php artisan module:migrate ContentData
```

## Publish module assets
To publish the module assets, run:

```sh
php artisan module:publish ContentData
```

## Usage
To use the ContentData functionality, you can include the `ContentDataTrait` in your Eloquent models. This will allow you to set and get custom content data easily.

### Example
```php
use Modules\ContentData\Traits\ContentDataTrait;

class Product extends Model
{
    use ContentDataTrait;

    // Your model code...
}

// Setting content data
$product = new Product();
$product->setContentData(['color' => 'red', 'size' => 'M']);
$product->save();

// Getting content data
$contentData = $product->getContentData(['color', 'size']);
```

## Testing
To run the tests for the ContentData module, use the following command:

```sh
php artisan test
```

## License
This module is licensed under the MIT License. See the [LICENSE](https://github.com/microweber/microweber/blob/master/LICENSE) file for more information.
