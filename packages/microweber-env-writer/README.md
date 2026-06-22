# Env Writer for Laravel

A standalone `.env` file writer for Laravel. Reads, updates and appends key-value pairs while preserving comments and preventing duplicate keys.

## Installation

```bash
composer require microweber-packages/env-writer
```

The service provider is auto-discovered by Laravel.

## Usage

### Via Dependency Injection

```php
use MicroweberPackages\EnvWriter\EnvWriter;

public function install(EnvWriter $envWriter)
{
    $envWriter->save([
        'APP_KEY' => 'base64:your-key',
        'DB_HOST' => 'localhost',
        'APP_DEBUG' => true,
    ], app()->environmentFilePath());
}
```

### Via Facade

```php
use MicroweberPackages\EnvWriter\Facades\EnvWriter;

EnvWriter::save(['APP_KEY' => 'new-key'], base_path('.env'));
```

### Direct Instantiation (no Laravel required)

```php
$writer = new \MicroweberPackages\EnvWriter\EnvWriter();
$writer->save(['KEY' => 'value'], '/path/to/.env');
$values = $writer->read('/path/to/.env');
```

## Features

- Updates existing keys in place
- Appends new keys at the end
- Removes duplicate keys automatically
- Collapses consecutive blank lines
- Preserves comments
- Properly quotes values with spaces, hashes, or quotes
- Handles booleans, integers, nulls, and strings
- Read/parse `.env` files into associative arrays
- No Laravel dependency for core functionality