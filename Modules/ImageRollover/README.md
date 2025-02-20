# ImageRollover Module

The ImageRollover module allows you to create interactive image rollovers where one image changes to another when hovered over.

## Features
- Set default and rollover images
- Customizable image size
- Optional link with title
- Smooth transition effects
- Responsive design

## Installation

### Run module migrations
```sh
php artisan module:migrate ImageRollover
```

### Publish module assets
```sh
php artisan module:publish ImageRollover
```

## Usage

### In Templates
```php
<module type="image_rollover" />
```

### Module Options
- `default_image`: The initial image to display
- `rollover_image`: The image to show on hover
- `size`: Image size in pixels or 'auto'
- `text`: Optional link text
- `href-url`: Optional URL for the link

### Example
```php
<module type="image_rollover" 
    default_image="path/to/default.jpg"
    rollover_image="path/to/hover.jpg"
    size="200"
    text="Click here"
    href-url="https://example.com"
/>
