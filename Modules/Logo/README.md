# Logo Module for Microweber CMS

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

