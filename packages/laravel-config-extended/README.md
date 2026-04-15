# Laravel Config Extended

Extended Laravel configuration management. Allows saving and persisting configuration changes at runtime, beyond the default read-only config behavior.

## Installation

This package is included as part of Microweber and is auto-loaded via Composer.

## Usage

```php
// Save a config value persistently
config_save('app.name', 'My Site');
```
