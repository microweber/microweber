# microweber-format

Reusable Laravel formatting & utility package – dates, strings, arrays, encoding, and XSS/HTML cleaning helpers. Accessed through the **Format** facade or the injectable `FormatService`.

## Features

- **Strings** – autolink, `limit`, `ago`, `human_filesize`, `string_between`, `titlelize`, `percent`, `amount_to_float`, `random_color`, lorem ipsum
- **Dates** – reformat, parse (`find_date`), system/DB format conversion, `split_dates`, supported-format lists
- **Arrays** – `array_to_ul`, `array_to_table`, `array_trim`, recursive slashes
- **Encoding** – base64 ⇄ array, `encrypt`/`decrypt`, Hashids `encode_ids`/`decode_ids`
- **HTML sanitization** – `clean_xss`, `clean_html`, `clean_scripts`, `strip_unsafe`
- **Misc** – `hex_to_rgb`, `arrayToHtmlAttributes`, `stringToTree`, `is_fqdn`, notification markup

## Installation

```bash
composer require microweber-packages/microweber-format
```

The service provider is auto-discovered and binds `FormatService` as a singleton.

## Usage

Access through the **Format** facade (preferred) or inject `FormatService`.

```php
use MicroweberPackages\Format\Facades\Format;

// Strings
Format::autolink('Visit https://example.com today');       // wraps the URL in <a>
Format::limit('Hello world this is a long string', 10);    // truncate with ellipsis
Format::ago('2024-01-01 12:00:00');                        // "3 months ago"
Format::human_filesize(1536000);                           // "1.46 MB"
Format::string_between('[a]body[/a]', '[a]', '[/a]');      // "body"
Format::titlelize('my-page_title');                        // "My Page Title"
Format::amount_to_float('1.234,56');                       // 1234.56
Format::percent(25, 200);                                  // 12.5

// Dates
Format::date('2024-03-15', 'd.m.Y');                       // "15.03.2024"
Format::find_date('Posted on 15/03/2024');                 // parsed date parts
Format::split_dates('2024-01-01', '2024-01-31', 7);        // 7 evenly spaced dates

// Arrays
Format::array_to_ul(['A', 'B', 'C']);                      // "<ul><li>A</li>...</ul>"
Format::array_to_table([['a' => 1, 'b' => 2]]);            // HTML table
Format::array_trim(['  x ', ' y']);                        // ['x', 'y']

// Encoding
$token = Format::encode_ids([1, 2, 3]);                    // Hashids string
$ids   = Format::decode_ids($token);                       // [1, 2, 3]
$b64   = Format::array_to_base64(['k' => 'v']);
$arr   = Format::base64_to_array($b64);

// HTML sanitization
Format::clean_xss($userInput);                             // strip XSS
Format::clean_html($html);                                 // strip unsafe HTML
Format::strip_unsafe($html, $allowImg = true);

// Misc
Format::hex_to_rgb('#0d6efd');                             // [13, 110, 253]
Format::arrayToHtmlAttributes(['id' => 'x', 'class' => 'y']); // 'id="x" class="y"'
Format::is_fqdn('example.com');                            // true
```

### Via dependency injection

```php
use MicroweberPackages\Format\FormatService;

public function __construct(private FormatService $format) {}

$short = $this->format->limit($title, 60);
```

## Key classes

- `MicroweberPackages\Format\FormatService` — service (singleton); logic split across the `Concerns\Formats*` / `EncodesData` / `SanitizesHtml` traits
- `MicroweberPackages\Format\Facades\Format` — facade (full `@method` map of every helper)
- `MicroweberPackages\Format\FormatServiceProvider` — registers the singleton

## Testing

```bash
composer test
```

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
