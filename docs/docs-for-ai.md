# Microweber Modules Documentation

<chunk>

## Accordion Module




## Run module migrations

```sh
php artisan module:migrate Accordion
```



## Publish module assets

```sh
php artisan module:publish Accordion
```



---

</chunk>

<chunk>

## Attributes Module




### Attribute

The `Attribute` model is defined in `Models/Attribute.php`. 

#### Attributes

- `attribute_name`: The name of the attribute.
- `attribute_value`: The value of the attribute.
- `rel_type`: The type of relation.
- `rel_id`: The ID of the related entity.
- `attribute_type`: The type of the attribute.
- `session_id`: The session ID associated with the attribute.
- `created_at`: Timestamp for when the attribute was created.
- `updated_at`: Timestamp for when the attribute was last updated.
- `created_by`: The user who created the attribute.
- `edited_by`: The user who last edited the attribute.

## Repositories

### AttributesManager.php

The `AttributesManager` class is defined in `Repositories/AttributesManager.php`. It provides methods to manage attributes:

- `getValues($params)`: Retrieves attribute values based on the provided parameters.
- `get($data)`: Fetches attributes based on various criteria.
- `save($data)`: Saves or updates an attribute.
- `delete($data)`: Deletes an attribute based on the provided criteria.



## License

This module is licensed under the MIT License.


---

</chunk>

<chunk>

## Audio Module


## Description
The Audio module allows you to manage audio files and URLs within your application. You can upload audio files or link to audio files hosted elsewhere.

## Features
- Upload audio files directly.
- Link to audio files via URL.
- Easy integration with the Filament admin panel.

## Installation

### Run module migrations
```sh
php artisan module:migrate Audio
```

### Publish module assets
```sh
php artisan module:publish Audio
```

## Usage
To use the Audio module, navigate to the settings in the Filament admin panel. You can configure audio sources, upload files, and set URLs.

## Configuration
You can configure the module settings in the `AudioModuleSettings` class. The settings include options for audio source type (file or URL) and the respective inputs for each type.

## License
This module is licensed under the MIT License.

## Author
Microweber Team - [info@microweber.com](mailto:info@microweber.com)


---

</chunk>

<chunk>

## Background Module


The Background module allows users to set a background image or video for their website. This module is useful for customizing the appearance of a website and creating a unique design.


---

</chunk>

<chunk>

## Backup Module

https://travis-ci.org/microweber-packages/microweber-backup-manager


---

</chunk>

<chunk>

## BeforeAfter Module


The BeforeAfter module allows users to create before-and-after image comparisons on their website.

## Features
- **Interactive Slider**: Slide between two images to see differences.
- **Customizable Images**: Set URLs for before and after images.
- **Responsive Design**: Works on various screen sizes.

## Installation

### Run module migrations
```sh
php artisan module:migrate BeforeAfter
```

### Publish module assets
```sh
php artisan module:publish BeforeAfter
```


---

</chunk>

<chunk>

## Btn Module




## Publish module assets

```sh
php artisan module:publish Btn
```



---

</chunk>

<chunk>

## Captcha Module




## Run module migrations

```sh
php artisan module:migrate Captcha
```



## Publish module assets

```sh
php artisan module:publish Captcha
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/captcha/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/captcha/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/captcha/img/icon.svg') }}
 ```

### module config values
```php
config('modules.captcha.name')
```



### Module views

Extend master layout

```php
@extends('modules.captcha::layouts.master')
```

Use Module view

```php
view('modules.captcha::index')
```


---

</chunk>

<chunk>

## Cart Module




## Run module migrations

```sh
php artisan module:migrate Cart
```



## Publish module assets

```sh
php artisan module:publish Cart
```



 


---

</chunk>

<chunk>

## Category Module




## Run module migrations

```sh
php artisan module:migrate Category
```



## Publish module assets

```sh
php artisan module:publish Category
```




---

</chunk>

<chunk>

## Checkout Module


E-commerce checkout solution for Microweber that handles contact information, shipping, and payment processing.

## Installation

```sh
# Run module migrations
php artisan module:migrate Checkout

# Publish module assets
php artisan module:publish Checkout
```

## Features

- Multi-step checkout process
- Contact information handling
- Shipping method selection
- Payment gateway integration
- Session management
- Input validation

## Testing

```bash
# Run checkout module tests
php artisan test Modules/Checkout/Tests/Unit

```






---

</chunk>

<chunk>

## Cloudflare Module




## Run module migrations

```sh
php artisan module:migrate Cloudflare
```



## Publish module assets

```sh
php artisan module:publish Cloudflare
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/cloudflare/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/cloudflare/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/cloudflare/img/icon.svg') }}
 ```

### module config values
```php
config('modules.cloudflare.name')
```



### Module views

Extend master layout

```php
@extends('modules.cloudflare::layouts.master')
```

Use Module view

```php
view('modules.cloudflare::index')
```


---

</chunk>

<chunk>

## Comments Module




## Run module migrations

```sh
php artisan module:migrate Comments
```



## Publish module assets

```sh
php artisan module:publish Comments
```

 

Using static assets
```blade
{{ asset('modules/comments/img/icon.svg') }}
 ```

### module config values
```php
config('modules.comments.name')
```



### Module views

Extend master layout

```php
@extends('modules.comments::layouts.master')
```

Use Module view

```php
view('modules.comments::index')
```


---

</chunk>

<chunk>

## Components Module


## Publish module assets

```sh
php artisan module:publish Components
```

# Bootstrap Components

This package provides a set of reusable Bootstrap components for Laravel applications using Blade components with the
`x-` prefix.

## Installation

1. Install the package via composer:

```bash
composer require microweber-modules/components
```

2. The components will be automatically registered via the service provider.

## Available Components

### Alert

Display alert messages with different styles.

```blade
<x-alert type="success" dismissible>
    Your changes have been saved successfully!
</x-alert>
```

**Props:**

- `type` (string): success, danger, warning, info, primary, secondary, light, dark
- `dismissible` (boolean): Adds a close button to dismiss the alert

### Button

Create Bootstrap styled buttons.

```blade
<x-button type="primary" size="lg">
    Click Me
</x-button>
```

**Props:**

- `type` (string): primary, secondary, success, danger, warning, info, light, dark
- `size` (string): sm, md, lg
- `outline` (boolean): Creates an outline button style
- `disabled` (boolean): Disables the button
- `block` (boolean): Creates a block level button

### Card

Create Bootstrap card components.

```blade
<x-card>
    <x-slot name="header">Card Title</x-slot>
    
    Card content goes here
    
    <x-slot name="footer">Card Footer</x-slot>
</x-card>
```

**Props:**

- `headerClass` (string): Additional classes for header
- `bodyClass` (string): Additional classes for body
- `footerClass` (string): Additional classes for footer

### Modal

Create Bootstrap modals.

```blade
<x-modal id="exampleModal" title="Modal Title">
    <x-slot name="body">
        Modal content goes here
    </x-slot>
    
    <x-slot name="footer">
        <x-button type="secondary" data-bs-dismiss="modal">Close</x-button>
        <x-button type="primary">Save changes</x-button>
    </x-slot>
</x-modal>
```

**Props:**

- `id` (string): Modal identifier
- `title` (string): Modal title
- `size` (string): sm, lg, xl
- `centered` (boolean): Vertically centers the modal
- `scrollable` (boolean): Adds a scrollable body

### Navbar

Create responsive navigation bars.

```blade
<x-navbar brand="My App" brandUrl="/">
    <x-nav-item href="/" active>Home</x-nav-item>
    <x-nav-item href="/about">About</x-nav-item>
    <x-nav-item href="/contact">Contact</x-nav-item>
</x-navbar>
```

**Props:**

- `brand` (string): Brand text
- `brandUrl` (string): Brand link URL
- `expand` (string): sm, md, lg, xl
- `dark` (boolean): Dark theme navbar
- `fixed` (string): top, bottom

### Form Components

#### Form Input

```blade
<x-input 
    name="email" 
    label="Email Address"
    type="email" 
    placeholder="Enter email"
    required
/>
```

**Props:**

- `name` (string): Input name
- `label` (string): Input label
- `type` (string): Input type (text, email, password, etc.)
- `value` (string): Input value
- `placeholder` (string): Input placeholder
- `required` (boolean): Makes the input required
- `disabled` (boolean): Disables the input
- `helper` (string): Helper text below input

#### Form Select

```blade
<x-select 
    name="country" 
    label="Select Country"
    :options="$countries"
/>
```

**Props:**

- `name` (string): Select name
- `label` (string): Select label
- `options` (array): Array of options
- `selected` (string|array): Selected value(s)
- `multiple` (boolean): Allows multiple selections

#### Form Checkbox

```blade
<x-checkbox 
    name="terms" 
    label="I agree to the terms"
/>
```

**Props:**

- `name` (string): Checkbox name
- `label` (string): Checkbox label
- `checked` (boolean): Checked state
- `value` (string): Checkbox value

### Pagination

Create Bootstrap styled pagination.

```blade
<x-pagination 
    :items="$posts"
/>
```

**Props:**

- `items` (mixed): The paginator instance
- `size` (string): sm, lg

### Progress Bar

Display progress bars.

```blade
<x-progress-bar 
    value="75" 
    type="success"
    striped
    animated
/>
```

**Props:**

- `value` (integer): Progress value (0-100)
- `type` (string): success, info, warning, danger
- `striped` (boolean): Adds stripes
- `animated` (boolean): Animates the stripes

### Tabs

Create tabbed interfaces.

```blade
<x-tabs>
    <x-tab-pane title="Home" active>
        Home content
    </x-tab-pane>
    <x-tab-pane title="Profile">
        Profile content
    </x-tab-pane>
</x-tabs>
```

**Props:**

- `vertical` (boolean): Creates vertical tabs
- `pills` (boolean): Uses pill style

## Styling

All components use Bootstrap 5 classes and can be customized using standard Bootstrap utilities:

```blade
<x-button 
    type="primary" 
    class="shadow rounded-pill"
>
    Custom Styled Button
</x-button>
```

## RTL Support

All components automatically support RTL when the page direction is set to RTL:

```html

<html dir="rtl">
```

## Example Usage in Blade Templates

```html

<x-container>

    <x-hero editable="true" align="center">

        <x-slot name="image">{{asset('templates/bootstrap/img/heros/illustration-2.png')}}</x-slot>

        <x-slot name="title">
            <h1>Welcome to Microweber</h1>
        </x-slot>
        <x-slot name="content">
            <p>
                Microweber is a drag and drop website builder and a powerful next-generation CMS. It's easy to use, and
                it's a great tool for building websites, online shops, blogs, and more. It's based on the Laravel PHP
                framework and the Bootstrap front-end framework.
                
                
                
            </p>
        </x-slot>
        <x-slot name="actions">
            <a href="#" class="btn btn-primary">
                Get Started
            </a>
            <a href="#" class="btn btn-secondary">
                Learn More
            </a>
        </x-slot>
    </x-hero>

</x-container>


<x-container>

    <x-simple-text align="right">
        <x-slot name="title">
            <h1>Welcome to Microweber</h1>
        </x-slot>
        <x-slot name="content">
            <p>
                Microweber is a drag and drop website builder and a powerful next-generation CMS. It's easy to use, and
                it's a great tool for building websites, online shops, blogs, and more. It's based on the Laravel PHP
                framework and the Bootstrap front-end framework.
            </p>
        </x-slot>
    </x-simple-text>

    <x-row>

        <x-col size="4">
            <x-card>

                <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>

                <x-slot name="title">
                    Microweber Card
                </x-slot>

                <x-slot name="content">
                    <p>
                        Some quick example text to build on the card title and make up the bulk of the card's content.
                    </p>
                </x-slot>

                <x-slot name="footer">
                    <a href="#" class="btn btn-primary">
                        Go somewhere
                    </a>
                </x-slot>

            </x-card>
        </x-col>

        <x-col size="4">
            <x-card theme="success">

                <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>

                <x-slot name="title">
                    CloudVision Cart
                </x-slot>

                <x-slot name="content">
                    <p>
                        Some quick example text to build on the card title and make up the bulk of the card's content.
                    </p>
                </x-slot>

                <x-slot name="footer">
                    <a href="#" class="btn btn-primary">
                        Go somewhere
                    </a>
                </x-slot>

            </x-card>
        </x-col>

        <x-col size="4">
            <x-card theme="danger">

                <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>

                <x-slot name="title">
                    Card
                </x-slot>

                <x-slot name="content">
                    <p>
                        Some quick example text to build on the card title and make up the bulk of the card's content.
                    </p>
                </x-slot>

                <x-slot name="footer">
                    <a href="#" class="btn btn-primary">
                        Go somewhere
                    </a>
                </x-slot>

            </x-card>
        </x-col>
    </x-row>
</x-container>

```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


---

</chunk>

<chunk>

## ContactForm Module




## Run module migrations

```sh
php artisan module:migrate ContactForm
```



## Publish module assets

```sh
php artisan module:publish ContactForm
```


Using static assets
```blade
{{ asset('modules/contact_form/img/icon.svg') }}
 ```

### module config values
```php
config('modules.contact_form.name')
```



### Module views

Extend master layout

```php
@extends('modules.contact_form::layouts.master')
```

Use Module view

```php
view('modules.contact_form::index')
```


---

</chunk>

<chunk>

## Content Module


The Content module provides functionality for managing pages, posts, products and other content types in your application.

## Installation

### Run module migrations
```sh
php artisan module:migrate Content
```

### Publish module assets
```sh
php artisan module:publish Content
```

## Helper Functions

### Content Type Checks

#### `is_page()`
Checks if current content is a page.
```php
if (is_page()) {
    // Current content is a page
}
```

#### `is_post()`
Checks if current content is a post.
```php
if (is_post()) {
    // Current content is a post
}
```

#### `is_home()`
Checks if current page is home page.
```php
if (is_home()) {
    // Current page is home page
}
```

#### `is_category()`
Checks if current content is a category.
```php
if (is_category()) {
    // Current content is a category
}
```

#### `is_product()`
Checks if current content is a product.
```php
if (is_product()) {
    // Current content is a product
}
```

### ID Getters

#### `page_id()`
Gets current page ID.
```php
$pageId = page_id();
```

#### `main_page_id()`
Gets main page ID.
```php
$mainPageId = main_page_id();
```

#### `post_id()`
Gets current post ID.
```php
$postId = post_id();
```

#### `product_id()`
Gets current product ID.
```php
$productId = product_id();
```

#### `content_id()`
Gets current content ID.
```php
$contentId = content_id();
```

#### `category_id()`
Gets current category ID.
```php
$categoryId = category_id();
```

### Content Retrieval

#### `get_content($params = false)`
Gets content items based on parameters.
```php
// Get all active pages
$content = get_content([
    'content_type' => 'page',
    'is_active' => 1
]);
```

#### `get_content_admin($params)`
Gets content items for admin with additional checks.
```php
$content = get_content_admin([
    'content_type' => 'page'
]);
```

#### `get_posts($params = false)`
Gets posts with optional filtering.
```php
$posts = get_posts(['is_active' => 1]);
```

#### `get_pages($params = false)`
Gets pages with optional filtering.
```php
$pages = get_pages(['parent_id' => 0]);
```

#### `get_products($params = false)`
Gets products with optional filtering.
```php
$products = get_products(['is_active' => 1]);
```

#### `get_content_by_id($params)`
Gets content by ID.
```php
$content = get_content_by_id(123);
```

### Content Links & Titles

#### `content_link($id = false)`
Gets content URL.
```php
$url = content_link(123);
```

#### `content_edit_link($id = false)`
Gets content edit URL.
```php
$editUrl = content_edit_link(123);
```

#### `content_create_link($contentType = 'page')`
Gets content creation URL.
```php
$createUrl = content_create_link('post');
```

#### `content_title($id = false)`
Gets content title.
```php
$title = content_title(123);
```

#### `content_description($id = false)`
Gets content description.
```php
$description = content_description(123);
```

### Content Management

#### `save_content($data, $delete_the_cache = true)`
Saves content data.
```php
$contentId = save_content([
    'title' => 'My Page',
    'content_type' => 'page'
]);
```


#### `delete_content($data)`
Deletes content.
```php
delete_content(['id' => 123]);
```

### Navigation & Structure

#### `content_parents($id = 0)`
Gets parent content items.
```php
$parents = content_parents(123);
```

#### `get_content_children($id = 0)`
Gets child content items.
```php
$children = get_content_children(123);
```

#### `pages_tree($params = false)`
Gets pages hierarchy.
```php
$tree = pages_tree();
```

#### `next_content($content_id = false)`
Gets next content item.
```php
$next = next_content(123);
```

#### `prev_content($content_id = false)`
Gets previous content item.
```php
$prev = prev_content(123);
```

#### `next_post($content_id = false)`
Gets next post.
```php
$nextPost = next_post(123);
```

#### `prev_post($content_id = false)`
Gets previous post.
```php
$prevPost = prev_post(123);
```

#### `breadcrumb($params = false)`
Generates breadcrumb navigation.
```php
$breadcrumbs = breadcrumb();
```

### Custom Fields & Data

#### `content_custom_fields($content_id, $full = true, $field_type = false)`
Gets content custom fields.
```php
$fields = content_custom_fields(123);
```

#### `get_content_field($data, $debug = false)`
Gets content field HTML.
```php
$fieldHtml = get_content_field([
    'field_type' => 'text',
    'name' => 'title'
]);
```

#### `save_content_field($data, $delete_the_cache = true)`
Saves content field data.
```php
save_content_field([
    'content_id' => 123,
    'field_type' => 'text',
    'name' => 'title',
    'value' => 'New Title'
]);
```

#### `content_data($content_id, $field_name = false)`
Gets content data.
```php
$data = content_data(123, 'field_name');
```

#### `content_attributes($content_id)`
Gets content attributes.
```php
$attributes = content_attributes(123);
```

### Schema.org Integration

#### `getSchemaOrgScriptByContentIds($contentIds)`
Generates Schema.org script for content items.
```php
$script = getSchemaOrgScriptByContentIds([123, 456]);
```

#### `getSchemaOrgContentFilled($graph, $contentId)`
Fills Schema.org graph with content data.
```php
$graph = getSchemaOrgContentFilled($graph, 123);
```

### Utility Functions

#### `helper_body_classes()`
Gets body CSS classes based on current content.
```php
$classes = helper_body_classes();
```

#### `content_date($id = false)`
Gets content creation date.
```php
$date = content_date(123);
```

#### `paging($params)`
Generates pagination.
```php
$pagination = paging([
    'limit' => 10,
    'current_page' => 1
]);


---

</chunk>

<chunk>

## ContentData Module


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


---

</chunk>

<chunk>

## ContentDataVariant Module


This module allows you to create and manage different data variants for your content. Based on custom field values.


## Run module migrations

```sh
php artisan module:migrate ContentDataVariant
```



## Publish module assets

```sh
php artisan module:publish ContentDataVariant
```





---

</chunk>

<chunk>

## ContentField Module




## Run module migrations

```sh
php artisan module:migrate ContentField
```



## Publish module assets

```sh
php artisan module:publish ContentField
```




---

</chunk>

<chunk>

## CookieNotice Module


A comprehensive cookie consent and notice management module for Microweber CMS, built with Filament v3, Laravel 11, and Livewire v3.

## Features

- Modern, customizable cookie notice popup
- Filament-based admin interface

## Installation

```bash
composer require microweber-modules/cookie-notice
php artisan module:migrate CookieNotice
```

## Configuration

Navigate to Admin Panel > Settings > Cookie Notice to configure:

- Enable/disable cookie notice
- Set cookie policy URL
- Customize appearance (colors, position)

## Usage

The cookie notice will automatically appear to users who haven't set their preferences. Users can:

- Accept all cookies
- Customize cookie preferences
- Access cookie settings via a floating button
- Review cookie policy

## Development

### Build Assets

```bash
cd Modules/CookieNotice
npm install
npm run build
```

### Run Tests

```bash
php artisan test Modules/CookieNotice/Tests
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


---

</chunk>

<chunk>

## Country Module


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


---

</chunk>

<chunk>

## Coupons Module



## Run module migrations

```sh
php artisan module:migrate Coupons
```



## Publish module assets

```sh
php artisan module:publish Coupons
```


---

</chunk>

<chunk>

## Currency Module




## Run module migrations

```sh
php artisan module:migrate Currency
```



## Publish module assets

```sh
php artisan module:publish Currency
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/currency/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/currency/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/currency/img/icon.svg') }}
 ```

### module config values
```php
config('modules.currency.name')
```



### Module views

Extend master layout

```php
@extends('modules.currency::layouts.master')
```

Use Module view

```php
view('modules.currency::index')
```


---

</chunk>

<chunk>

## CustomFields Module




## Run module migrations

```sh
php artisan module:migrate CustomFields
```



## Publish module assets

```sh
php artisan module:publish CustomFields
```


---

</chunk>

<chunk>

## Customer Module


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
use Modules\Customer\Models\Address;

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


---

</chunk>

<chunk>

## Embed Module




## Run module migrations

```sh
php artisan module:migrate Embed
```



## Publish module assets

```sh
php artisan module:publish Embed
```



---

</chunk>

<chunk>

## FacebookLike Module




## Run module migrations

```sh
php artisan module:migrate FacebookLike
```



## Publish module assets

```sh
php artisan module:publish FacebookLike
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/facebook_like/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/facebook_like/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/facebook_like/img/icon.svg') }}
 ```

### module config values
```php
config('modules.facebook_like.name')
```



### Module views

Extend master layout

```php
@extends('modules.facebook_like::layouts.master')
```

Use Module view

```php
view('modules.facebook_like::index')
```


---

</chunk>

<chunk>

## FacebookPage Module




## Run module migrations

```sh
php artisan module:migrate FacebookPage
```



## Publish module assets

```sh
php artisan module:publish FacebookPage
```




---

</chunk>

<chunk>

## Faq Module



 
## Publish module assets

```sh
php artisan module:publish Faq
```


---

</chunk>

<chunk>

## Form Module


## Overview
The Form module provides functionality for creating and managing forms within the Microweber CMS. It allows users to submit data through customizable forms, which can be configured to send notifications and store entries.

## Features
- Create and manage forms with various field types (text, email, file upload, etc.)
- Automatic email notifications for form submissions
- Customizable autoresponders for users
- Validation for required fields and file uploads
- Integration with the Microweber database for storing form entries

## Models

### FormList
The `FormList` model represents a list of forms. It contains the following properties:
- `id`: The unique identifier for the form list.
- `title`: The title of the form list.
- `description`: A description of the form list.
- `created_at`: The timestamp when the form list was created.
- `formsData()`: A relationship method that retrieves all form data associated with this form list.

### FormData
The `FormData` model represents the data submitted through a form. It contains the following properties:
- `id`: The unique identifier for the form data entry.
- `rel_type`: The type of relationship (e.g., module).
- `rel_id`: The identifier of the related entity.
- `form_values`: The submitted values of the form in JSON format.
- `created_at`: The timestamp when the form data was created.
- `formDataValues()`: A relationship method that retrieves all values associated with this form data entry.

### FormDataValue
The `FormDataValue` model represents individual values submitted in a form. It contains the following properties:
- `id`: The unique identifier for the form data value.
- `form_data_id`: The identifier of the associated form data entry.
- `field_type`: The type of the field (e.g., text, email).
- `field_key`: The key of the field.
- `field_name`: The name of the field.
- `field_value`: The value submitted for the field.
- `field_value_json`: The JSON representation of the field value.

### FormRecipient
The `FormRecipient` model represents recipients of form submissions. It contains the following properties:
- `id`: The unique identifier for the form recipient.
- `name`: The name of the recipient.
- `email`: The email address of the recipient.

## Testing

Run the tests using the following command:

```sh
php artisan test --filter Form
```


---

</chunk>

<chunk>

## GoogleAnalytics Module


This module provides Google Analytics 4 (GA4) integration for your Microweber website, including enhanced e-commerce tracking and conversion tracking.

## Features

- Google Analytics 4 (GA4) integration
- Enhanced e-commerce tracking
- Conversion tracking
- User data tracking with privacy compliance
- Event tracking for:
  - Page views
  - User logins
  - Sign ups
  - Add to cart actions
  - Begin checkout
  - Add shipping info
  - Add payment info
  - Purchases
  - Custom conversions

## Installation

### Run module migrations
```sh
php artisan module:migrate GoogleAnalytics
```

### Publish module assets
```sh
php artisan module:publish GoogleAnalytics
```

## Configuration

1. Go to your Google Analytics account and set up a GA4 property
2. Get your Measurement ID (starts with G-)
3. Create an API Secret in your GA4 property settings
4. In your Microweber admin panel:
   - Navigate to Modules > Google Analytics
   - Enable Google Analytics tracking
   - Enter your Measurement ID and API Secret
   - Optionally enable Enhanced Conversions if needed

## Enhanced Conversions Setup

If you want to track conversions with more detailed user data:

1. Enable Enhanced Conversions in your GA4 property
2. Get your Conversion ID and Label
3. In the module settings:
   - Enable Enhanced Conversions
   - Enter your Conversion ID and Label

## Usage

Once configured, the module will automatically:

- Add the GA4 tracking code to your site
- Track basic analytics data
- Track e-commerce events if you're using Microweber's shop features
- Send enhanced conversion data when available

No additional code is required for basic tracking. The module integrates with Microweber's event system to automatically track relevant actions.

## License

This module is licensed under the MIT License.


---

</chunk>

<chunk>

## GoogleMaps Module


## Description
The Google Maps module allows you to embed Google Maps into your application. You can configure the map settings such as address, zoom level, and dimensions.

## Features
- Embed Google Maps with customizable settings.
- Configure map address, zoom, width, and height.
- Integration with the Filament admin panel for easy configuration.

## Installation

### Run module migrations
```sh
php artisan module:migrate GoogleMaps
```

### Publish module assets
```sh
php artisan module:publish GoogleMaps
```

## Usage
To use the Google Maps module, navigate to the settings in the Filament admin panel. You can configure the map's address, zoom level, and dimensions.

## Configuration
You can configure the module settings in the `GoogleMapsModuleSettings` class. The settings include options for location details and map display properties.

## License
This module is licensed under the MIT License.

## Author
Microweber Team - [info@microweber.com](mailto:info@microweber.com)





---

</chunk>

<chunk>

## HighlightCode Module




## Publish module assets

```sh
php artisan module:publish HighlightCode
```



---

</chunk>

<chunk>

## Invoice Module




## Run module migrations

```sh
php artisan module:migrate Invoice
```



## Publish module assets

```sh
php artisan module:publish Invoice
```



---

</chunk>

<chunk>

## Layouts Module


## Publish module assets

```sh
php artisan module:publish Layouts
```


---

</chunk>

<chunk>

## Logo Module


## Publish module assets

```sh
php artisan module:publish Logo
```

## Creating Custom Templates

The Logo module supports custom templates to control how the logo is displayed. Templates are stored in `resources/views/templates/`.

### Template Structure

1. Create a new Blade template file in `resources/views/templates/` directory
2. Add the template metadata comment at the top:
```php
/*
  type: layout
  name: Your Template Name
  description: Brief description of your template
*/
```

### Available Variables

Templates have access to these variables:
- `$logoimage`: URL of the uploaded logo image
- `$text`: Logo text (shown when no image is present)
- `$text_color`: Color for the text
- `$font_family`: Font family for the text
- `$font_size`: Font size in pixels
- `$size`: Logo size in pixels

### Example Template

Here's a basic template structure:

```php
<?php
/*
  type: layout
  name: My Custom Template
  description: Custom logo layout
*/
?>

<a href="{{ site_url() }}" class="module-logo">
    @if ($logoimage)
        <img src="{{ $logoimage }}" alt="" style="max-width: {{ $size }}px;"/>
    @elseif ($text)
        <span style="color: {{ $text_color }}; font-size: {{ $font_size }}px;">
            {{ $text }}
        </span>
    @endif
</a>
```

See the `2rows.blade.php` and `default.blade.php` templates in the resources/views/templates directory for more examples.



---

</chunk>

<chunk>

## MailTemplate Module


A Laravel module for managing email templates with Filament v3 admin interface.

## Features

- Complete mail template management system
- Modern admin interface using Filament v3
- Configurable template types and variables
- Easy integration with other modules
- Rich text editor for template content
- Variable substitution support
- Attachment support
- CC email functionality

## Installation

1. Run module migrations:
```bash
php artisan module:migrate MailTemplate
```

2. Publish module assets:
```bash
php artisan module:publish MailTemplate
```

## Admin Interface

Access the mail templates management at:
- **Templates List**: Admin > Settings > Mail Templates
- **Module Settings**: Admin > Settings > Mail Templates Settings

## Usage

### Helper Functions

```php
// Get mail template service
$service = mail_template_service();

// Get template by type
$template = get_mail_template_by_type('new_order');

// Send email using template
send_mail_template('new_order', 'customer@example.com', [
    'order_id' => $order->id,
    'customer_name' => $order->customer_name
]);

// Get available variables for template type
$variables = get_mail_template_variables('new_order');

// Get all template types
$types = get_mail_template_types();
```

### Service Methods

```php
use Modules\MailTemplate\Services\MailTemplateService;

public function example(MailTemplateService $service)
{
    // Get template
    $template = $service->getTemplateByType('new_order');
    
    // Parse template with variables
    $message = $service->parseTemplate($template, [
        'order_id' => '123',
        'customer_name' => 'John Doe'
    ]);
    
    // Send email with attachments
    $service->send($template, 'customer@example.com', [
        'order_id' => '123',
        'customer_name' => 'John Doe'
    ], [
        ['path' => 'path/to/file.pdf']
    ]);
}
```

## Available Template Types

- `new_order` - New Order Notification
- `new_comment` - New Comment Notification
- `forgot_password` - Password Reset
- `new_registration` - New User Registration
- `contact_form` - Contact Form Submission
- `newsletter_subscription` - Newsletter Subscription

## Template Variables

Each template type supports specific variables:

### New Order
- `{order_id}` - Order ID
- `{order_amount}` - Order Amount
- `{customer_name}` - Customer Name
- `{order_status}` - Order Status
- `{order_items}` - Order Items

### New Comment
- `{comment_author}` - Comment Author
- `{comment_content}` - Comment Content
- `{post_title}` - Post Title

### Forgot Password
- `{reset_link}` - Password Reset Link
- `{user_name}` - User Name

### New Registration
- `{user_name}` - User Name
- `{user_email}` - User Email
- `{verification_link}` - Email Verification Link

### Contact Form
- `{name}` - Sender Name
- `{email}` - Sender Email
- `{subject}` - Message Subject
- `{message}` - Message Content

### Newsletter Subscription
- `{subscriber_email}` - Subscriber Email
- `{confirmation_link}` - Confirmation Link

## Configuration

The module configuration can be published and modified:

```bash
php artisan vendor:publish --tag=config --provider="Modules\MailTemplate\Providers\MailTemplateServiceProvider"
```

Key configuration options:
- Default email settings
- Template types
- Available variables
- Module settings

## Views

Module views can be published and customized:

```bash
php artisan vendor:publish --tag=views --provider="Modules\MailTemplate\Providers\MailTemplateServiceProvider"
```

## License

This module is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


---

</chunk>

<chunk>

## Marketplace Module





---

</chunk>

<chunk>

## Marquee Module


## Overview
The Marquee module allows you to create scrolling text on your Microweber CMS website, enhancing the visual appeal and engagement of your content. This module is built using Laravel, Filament, and Livewire for a seamless user experience.

## Installation
To install the Marquee module, run the following commands:

```sh
php artisan module:migrate Marquee
php artisan module:publish Marquee
```

## Configuration
You can configure the following settings for the marquee:

- **Marquee Text**: The text to be displayed in the marquee.
- **Font Size**: The size of the marquee text (in pixels).
- **Animation Speed**: The speed of the marquee animation.
- **Text Weight**: The weight of the marquee text (e.g., normal, bold).
- **Text Style**: The style of the marquee text (e.g., normal, italic).
- **Text Color**: The color of the marquee text (in hex format).


## Usage
To display scrolling text, include the Marquee module in your views. Customize the settings as needed to fit your design.

## License
This module is licensed under the MIT License.


---

</chunk>

<chunk>

## Media Module




## Run module migrations

```sh
php artisan module:migrate Media
```



## Publish module assets

```sh
php artisan module:publish Media
```



---

</chunk>

<chunk>

## Menu Module




## Run module migrations

```sh
php artisan module:migrate Menu
```



## Publish module assets

```sh
php artisan module:publish Menu
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/menu/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/menu/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/menu/img/icon.svg') }}
 ```

### module config values
```php
config('modules.menu.name')
```



### Module views

Extend master layout

```php
@extends('modules.menu::layouts.master')
```

Use Module view

```php
view('modules.menu::index')
```


---

</chunk>

<chunk>

## Newsletter Module




## Run module migrations

```sh
php artisan module:migrate Newsletter
```



## Publish module assets

```sh
php artisan module:publish Newsletter
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/newsletter/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/newsletter/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/newsletter/img/icon.svg') }}
 ```

### module config values
```php
config('modules.newsletter.name')
```



### Module views

Extend master layout

```php
@extends('modules.newsletter::layouts.master')
```

Use Module view

```php
view('modules.newsletter::index')
```


---

</chunk>

<chunk>

## Offer Module




## Run module migrations

```sh
php artisan module:migrate Offer
```



## Publish module assets

```sh
php artisan module:publish Offer
```




---

</chunk>

<chunk>

## OpenApi Module


This module provides functionality for generating and serving OpenAPI documentation for your API endpoints.

## Features

- Automatically generates OpenAPI documentation based on your routes and controllers.
- Provides a user-friendly interface for accessing API documentation.

## Installation

To install the OpenApi module, run the following command:

```sh
php artisan module:install OpenApi
```

## Publish module assets

```sh
php artisan module:publish OpenApi
```


## API Documentation

You can access the generated OpenAPI documentation at the following endpoint:
```
/api/documentation
```

## Testing

Run the tests using the following command:

```sh
php artisan test --filter OpenApi
```



---

</chunk>

<chunk>

## Order Module




## Run module migrations

```sh
php artisan module:migrate Order
```



## Publish module assets

```sh
php artisan module:publish Order
```



---

</chunk>

<chunk>

## Page Module




## Run module migrations

```sh
php artisan module:migrate Page
```



## Publish module assets

```sh
php artisan module:publish Page
```


 


---

</chunk>

<chunk>

## Payment Module


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


---

</chunk>

<chunk>

## Pdf Module




## Run module migrations

```sh
php artisan module:migrate Pdf
```



## Publish module assets

```sh
php artisan module:publish Pdf
```


---

</chunk>

<chunk>

## Pictures Module


## Overview

The Pictures module allows you to manage and display images in various formats and layouts within the Microweber CMS. You can create galleries, sliders, and more.

## Features

- Upload and manage images
- Multiple gallery layouts
- Responsive design
- Integration with the Filament admin panel

## Installation

1. Clone the repository or download the module files.
2. Place the module in the `Modules` directory of your Microweber installation.
3. Run the following command to publish the module assets:

```sh
php artisan module:publish Pictures
```

4. Configure the module settings in the admin panel.

## Usage

To use the Pictures module, simply include the relevant template in your views. You can customize the layout and appearance through the module settings.

## License

This module is licensed under the MIT License.


---

</chunk>

<chunk>

## Post Module


## Run module migrations

```sh
php artisan module:migrate Post
```

## Publish module assets

```sh
php artisan module:publish Post
```


---

</chunk>

<chunk>

## Product Module




## Run module migrations

```sh
php artisan module:migrate Product
```



## Publish module assets

```sh
php artisan module:publish Product
```


 


---

</chunk>

<chunk>

## Profile Module




## Run module migrations

```sh
php artisan module:migrate Profile
```



## Publish module assets

```sh
php artisan module:publish Profile
```





Using static assets
```blade
{{ asset('modules/profile/img/icon.svg') }}
 ```

### module config values
```php
config('modules.profile.name')
```



### Module views

Extend master layout

```php
@extends('modules.profile::layouts.master')
```

Use Module view

```php
view('modules.profile::example')
```


---

</chunk>

<chunk>

## RssFeed Module


This module provides functionality for generating RSS feeds for posts and products.

## Features

- Generates RSS feeds in different formats (e.g., Atom, WordPress).
- Supports multilingual content.
- Fetches content dynamically from the database.



## Usage

You can access the RSS feeds via the following endpoints:

- `/rss` - Returns the main RSS feed.
- `/rss/posts` - Returns the RSS feed for posts.
- `/rss/products` - Returns the RSS feed for products.

## Testing

Run the tests using the following command:

```sh
php artisan test --filter RssFeed
```



---

</chunk>

<chunk>

## Settings Module




## Run module migrations

```sh
php artisan module:migrate Settings
```



## Publish module assets

```sh
php artisan module:publish Settings
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/settings/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/settings/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/settings/img/icon.svg') }}
 ```

### module config values
```php
config('modules.settings.name')
```



### Module views

Extend master layout

```php
@extends('modules.settings::layouts.master')
```

Use Module view

```php
view('modules.settings::index')
```


---

</chunk>

<chunk>

## Sharer Module



## Publish module assets

```sh
php artisan module:publish Sharer
```





---

</chunk>

<chunk>

## Shipping Module




## Run module migrations

```sh
php artisan module:migrate Shipping
```



## Publish module assets

```sh
php artisan module:publish Shipping
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/shipping/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/shipping/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/shipping/img/icon.svg') }}
 ```

### module config values
```php
config('modules.shipping.name')
```



### Module views

Extend master layout

```php
@extends('modules.shipping::layouts.master')
```

Use Module view

```php
view('modules.shipping::index')
```


---

</chunk>

<chunk>

## Shop Module




## Run module migrations

```sh
php artisan module:migrate Shop
```



## Publish module assets

```sh
php artisan module:publish Shop
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/shop/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/shop/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/shop/img/icon.svg') }}
 ```

### module config values
```php
config('modules.shop.name')
```



### Module views

Extend master layout

```php
@extends('modules.shop::layouts.master')
```

Use Module view

```php
view('modules.shop::example')
```


---

</chunk>

<chunk>

## SiteStats Module




## Run module migrations

```sh
php artisan module:migrate SiteStats
```



## Publish module assets

```sh
php artisan module:publish SiteStats
```



---

</chunk>

<chunk>

## Sitemap Module


This module provides functionality for generating and managing sitemaps for your application. It allows you to create XML sitemaps for various content types, including categories, tags, products, posts, and pages.

## Features

- Generates XML sitemaps for different content types.
- Supports multilingual content.
- Automatically updates the sitemap when content changes.
- Provides a structured sitemap index.

## Usage

You can access the sitemaps via the following endpoints:

- `/sitemap.xml` - Returns the main sitemap index.
- `/sitemap.xml/categories` - Returns the sitemap for categories.
- `/sitemap.xml/tags` - Returns the sitemap for tags.
- `/sitemap.xml/products` - Returns the sitemap for products.
- `/sitemap.xml/posts` - Returns the sitemap for posts.
- `/sitemap.xml/pages` - Returns the sitemap for pages.

## Testing

Run the tests using the following command:

```sh
php artisan test --filter Sitemap
```


---

</chunk>

<chunk>

## Skills Module



## Publish module assets

```sh
php artisan module:publish Skills
```




---

</chunk>

<chunk>

## Slider Module


The Slider module allows users to create sliders for displaying images or content on their website.

## Features
- **Responsive Design**: Adjusts to different screen sizes.
- **Customizable Slides**: Add and configure slides easily.
- **Autoplay and Looping**: Options for automatic sliding.
- **Navigation and Pagination**: Built-in navigation buttons.

## Installation

### Run module migrations
```sh
php artisan module:migrate Slider
```

### Publish module assets
```sh
php artisan module:publish Slider
```

## Usage

Include the Slider module in your views and configure the slides.

## License

This module is licensed under the MIT License.


---

</chunk>

<chunk>

## SocialLinks Module



## Publish module assets

```sh
php artisan module:publish SocialLinks
```



---

</chunk>

<chunk>

## Tabs Module


## Overview
The Tabs module allows you to create and manage tabbed content on your Microweber CMS website. Built using Laravel, this module leverages Filament and Livewire to provide a user-friendly interface for organizing content into tabs, enhancing the overall user experience.

## Installation
To install the Tabs module, follow these steps:

1. Run the module migrations:
   ```sh
   php artisan module:migrate Tabs
   ```

2. Publish the module assets:
   ```sh
   php artisan module:publish Tabs
   ```

## Model Information
The main model used in this module is `Tab`, which interacts with the `tabs` database table.

### Database Table: `tabs`
| Field Name  | Type       | Description                          |
|-------------|------------|--------------------------------------|
| `id`        | Integer    | Primary key                          |
| `title`     | String     | Title of the tab                     |
| `icon`      | String     | Icon associated with the tab         |
| `content`   | Long Text  | Content displayed in the tab         |
| `position`  | Integer    | Position of the tab in the list      |
| `rel_type`  | String     | Relation type                        |
| `rel_id`    | String     | Relation ID                          |
| `settings`  | Long Text  | Additional settings for the tab      |
| `created_at`| Timestamp  | Timestamp for when the tab was created |
| `updated_at`| Timestamp  | Timestamp for when the tab was last updated |

## Features
- Create, edit, and delete tab items.
- Organize content into a tabbed layout for better user navigation.
- Customize tab settings and appearance.

## License
This module is licensed under the MIT License.


---

</chunk>

<chunk>

## Tag Module


## Overview

The Tag module provides a simple and efficient way to manage tags in your Microweber site.

It allows you to create, assign, and manage tags for various content types, enhancing the organization and retrieval of content.

## Features

- Create and manage tags and tag groups.
- Assign multiple tags to content.
- Retrieve content based on tags.
- Automatically delete unused tags.
- Support for localization.

## Installation

To install the Tag module, run the following command:

```sh
php artisan module:install Tag
```

## Run Module Migrations

After installation, run the module migrations to set up the necessary database tables:

```sh
php artisan module:migrate Tag
```

## Usage

### Adding Tags to Content

You can add tags to your content by using the `tag` method:

```php
$content = Content::find(1);
$content->tag('example-tag');
```

### Retrieving Tags

To retrieve the tags associated with a content item, use the `tagNames` method:

```php
$tags = $content->tagNames();
```

### Querying Content by Tags

You can query content based on tags using the following methods:

- `withAllTags(array $tags)`: Retrieve content that has all specified tags.
- `withAnyTag(array $tags)`: Retrieve content that has any of the specified tags.
- `withoutTags(array $tags)`: Retrieve content that does not have the specified tags.

### Deleting Tags

To remove tags from content, use the `untag` method:

```php
$content->untag('example-tag');
```

## Configuration

You can customize the Tag module's behavior by modifying the configuration file located at `Modules/Tag/config/tagging.php`.



---

</chunk>

<chunk>

## Tax Module


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


---

</chunk>

<chunk>

## Teamcard Module




## Run module migrations

```sh
php artisan module:migrate Teamcard
```



## Publish module assets

```sh
php artisan module:publish Teamcard
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/teamcard/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/teamcard/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/teamcard/img/icon.svg') }}
 ```

### module config values
```php
config('modules.teamcard.name')
```



### Module views

Extend master layout

```php
@extends('modules.teamcard::layouts.master')
```

Use Module view

```php
view('modules.teamcard::index')
```


---

</chunk>

<chunk>

## Testimonials Module


## Overview
The Testimonials module enables you to manage and display client testimonials on your Microweber CMS website effectively. This module utilizes Laravel, Filament, and Livewire for a seamless user experience.

## Features
- **Add, edit, and delete testimonials.**
- **Display testimonials in various formats.**

## Installation
To install the Testimonials module, run the following commands:

```sh
php artisan module:migrate Testimonials
php artisan module:publish Testimonials
```

## Usage
Access the Testimonials module from the admin panel to manage testimonials.

## Configuration
Configure module settings in the admin panel under the Testimonials settings section.

## Model
The `Testimonial` model represents a testimonial entry in the database.

### Database Table
The module uses the `testimonials` table with the following fields:

| Field Name       | Type      | Description                             |
|------------------|-----------|-----------------------------------------|
| `id`             | Integer   | Primary key                             |
| `name`           | String    | Name of the client                      |
| `content`        | Text      | Content of the testimonial              |
| `client_company` | String    | Company of the client                   |
| `client_role`    | String    | Role of the client                      |
| `client_website` | String    | Website of the client                   |
| `client_image`   | String    | Image of the client                     |
| `position`       | Integer   | Position of the testimonial in the list |
| `rel_type`       | String    | Relation type                           |
| `rel_id`         | String    | Relation ID                             |
| `created_at`     | Timestamp | Timestamp for when the testimonial was created |
| `updated_at`     | Timestamp | Timestamp for when the testimonial was last updated |

## Templates
The Testimonials module comes with multiple pre-built templates located in `resources/views/templates/`. These templates provide different styles and layouts for displaying testimonials.

### Available Templates
- `default.blade.php`: Default grid layout with client image, name, content, company, and role
- Multiple skin variations (skin-1 through skin-23) offering different styling options

### Creating Custom Templates
To create a custom template:

1. Create a new Blade file in `resources/views/templates/` directory
2. Add the required template metadata at the top of the file:
```php
@php
/*
type: layout
name: Your Template Name
description: Your Template Description
*/
@endphp
```

3. Structure your template using HTML and Blade syntax. Available variables:
   - `$testimonials`: Collection of testimonial items
   - `$params['id']`: Unique module instance ID

Example template structure:
```blade
<div class="your-template-class">
    @if($testimonials->isEmpty())
        <p>No testimonials found</p>
    @else
        @foreach($testimonials as $item)
            <div class="testimonial-item">
                @if(isset($item->client_image))
                    <img src="{{ $item->client_image }}" alt="{{ $item->name }}">
                @endif
                
                @if(isset($item->name))
                    <h3>{{ $item->name }}</h3>
                @endif
                
                @if(isset($item->content))
                    <div class="content">{!! $item->content !!}</div>
                @endif
                
                @if(isset($item->client_company))
                    <div class="company">{{ $item->client_company }}</div>
                @endif
                
                @if(isset($item->client_role))
                    <div class="role">{{ $item->client_role }}</div>
                @endif
                
                @if(isset($item->client_website))
                    <a href="{{ $item->client_website }}" target="_blank">{{ $item->client_website }}</a>
                @endif
            </div>
        @endforeach
    @endif
</div>
```

### Available Fields
Each testimonial item (`$item`) has the following fields:
- `name`: Client name
- `content`: Testimonial content
- `client_company`: Company name
- `client_role`: Client's role/position
- `client_website`: Client's website URL
- `client_image`: Path to client's image

## License
This module is licensed under the MIT License.


---

</chunk>

<chunk>

## TextType Module


## Description
The TextType module enables the creation of animated text on websites.

## Installation
1. Run migrations:
   ```sh
   php artisan module:migrate TextType
   ```
2. Publish assets:
   ```sh
   php artisan module:publish TextType
   ```

## Usage
Include the TextType module in your views and configure the text and settings as needed.



---

</chunk>

<chunk>

## TweetEmbed Module




## Run module migrations

```sh
php artisan module:migrate TweetEmbed
```



## Publish module assets

```sh
php artisan module:publish TweetEmbed
```



---

</chunk>

<chunk>

## Video Module




## Run module migrations

```sh
php artisan module:migrate Video
```



## Publish module assets

```sh
php artisan module:publish Video
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/video/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/video/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/video/img/icon.svg') }}
 ```

### module config values
```php
config('modules.video.name')
```



### Module views

Extend master layout

```php
@extends('modules.video::layouts.master')
```

Use Module view

```php
view('modules.video::index')
```


---

</chunk>

