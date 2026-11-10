# Microweber Utils

A small collection of legacy utility classes used by the Microweber CMS, plus some vendored third-party libraries and data files.

This package has **no service provider, facade, or global helpers** — it is just
a PSR-4 autoloaded namespace (`MicroweberPackages\Utils\`). The classes are
plain objects you instantiate directly. Most of them call Microweber CMS global
helpers (`get_pages()`, `get_option()`, `get_mail_provider_settings()`, …), so
they are intended for use **inside a running Microweber install**, not as a
standalone library.

## Installation

```bash
composer require microweber-packages/microweber-utils
```

No provider to register. Autoloading makes the classes available immediately.

## Usage

### Content export (`Misc\ContentExport`)

Exports selected content types to a JSON file under `storage/export_content/`.

```php
use MicroweberPackages\Utils\Misc\ContentExport;

$export = new ContentExport([
    'export_pages'      => 1,
    'export_posts'      => 1,
    'export_products'   => 1,
    'export_categories' => 1,
    'export_orders'     => 1,
    // export_comments, export_clients, export_coupons ...
]);

$result = $export->start();
// ['success' => 'N items are exported', 'filename' => 'content_export_YYYY-MM-DD-his.json']
// or ['error' => '...']

$export->download($result['filename']); // streams the file as an attachment
```

### Sitemap writer (`Misc\Sitemap`)

Builds a sitemap XML string and writes it to a file. Note the class uses a
legacy PHP4-style `Sitemap()` method (not a real constructor), so set the
target file on the property directly:

```php
use MicroweberPackages\Utils\Misc\Sitemap;

$sitemap = new Sitemap();
$sitemap->file = storage_path('sitemap.xml');

$sitemap->addPage('https://example.com/',       'daily',  1.0);
$sitemap->addPage('https://example.com/about',  'weekly', 0.8, '2024-01-01');

$path = $sitemap->create(); // writes the XML file, returns its path
```

### Mail subscriber (`Mail\MailSubscriber`)

Subscribes a contact to the configured third-party mailing providers
(FlexMail / MailerLite), driven by Microweber mail-provider options.

```php
use MicroweberPackages\Utils\Mail\MailSubscriber;

$subscriber = new MailSubscriber();
$subscriber->setListTitle('Newsletter');
$subscriber->setEmail('john@example.com');
$subscriber->setFirstName('John');
$subscriber->setLastName('Doe');
$subscriber->setSubscribeFrom('newsletter');          // option group to check
$subscriber->setSubscribeSource('module');
$subscriber->addCustomField(['key' => 'plan', 'value' => 'pro']);

$log = $subscriber->subscribe();  // ['providers' => [ ['name' => 'Mailer Lite', 'success' => true, ...] ]]
```

## Bundled third-party libraries & data

Under `src/ThirdPartyLibs/` the package also ships vendored legacy libraries and
reference data that other CMS code may load by path:

- `PclZip.php` — pure-PHP ZIP library.
- `PHPImageMagician/ImageLib.php`, `PHPImageMagician/PhpPsdReader.php` — image manipulation (an up-to-date copy lives in the `microweber-thumbnailer` package, which this package requires).
- `currencies.csv`, `countries.sql` — seed/reference data.

## Key classes

- `MicroweberPackages\Utils\Misc\ContentExport` — JSON export of pages/posts/products/orders/etc.
- `MicroweberPackages\Utils\Misc\Sitemap` — sitemap XML builder/writer.
- `MicroweberPackages\Utils\Mail\MailSubscriber` — FlexMail / MailerLite subscription.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Petko Yovchevski

## License

MIT
