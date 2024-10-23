# Audio Module

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
