# Updater Module for Microweber

This module provides a standalone updater for Microweber. It allows you to update your Microweber installation from the admin panel or by using a standalone updater file.

## Features

- Check for updates from the admin panel
- Update Microweber to the latest version
- Create a standalone updater file that can be used to update Microweber without the admin panel
- Dashboard notifications for available updates

## Installation

```bash
php artisan module:install Updater
```

## Usage

### Admin Panel

1. Go to the admin panel
2. Navigate to System > Updater
3. Check for updates
4. Click "Update Now" to update to the latest version

### Standalone Updater

1. Go to the admin panel
2. Navigate to System > Updater
3. Click "Copy Standalone Updater"
4. The standalone updater will be copied to the public directory
5. Access the standalone updater at `https://your-site.com/standalone-updater.php`

## Requirements

- PHP 8.0 or higher
- ZipArchive PHP extension
- Curl PHP extension
- JSON PHP extension
- Writable directories: src, userfiles, storage, vendor
- Minimum 1GB of free disk space

## License

This module is open-sourced software licensed under the MIT license.
