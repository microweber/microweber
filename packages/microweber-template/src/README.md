# Microweber Template Package

This package provides functionality for managing template meta tags in a Microweber application. It allows you to add,
remove, and manage scripts, styles, and custom HTML tags in the head and footer sections of your templates.

## Features

- Add and remove scripts with attributes and placement options.
- Add and remove styles with attributes and placement options.
- Add custom HTML tags to the head and footer sections.
- Retrieve all scripts, styles, and custom tags for the head and footer.

## Usage

### Using the Facade

The `TemplateMetaTags` facade provides a simple interface to interact with the template meta tags. Ensure that the
facade is properly registered in your application.

### Adding a Script

To add a script to the head or footer, use the `addScript` method:

```php
TemplateMetaTags::addScript('main-script', 'https://example.com/script.js', ['async' => true], 'head');
TemplateMetaTags::addScript('main-script', 'https://example.com/script.js', ['async' => true], 'footer');
```

### Adding a Style

To add a style to the head or footer, use the `addStyle` method:

```php
TemplateMetaTags::addStyle('main-style', 'https://example.com/style.css', ['media' => 'print'], 'head');
TemplateMetaTags::addStyle('main-footer-style', 'https://example.com/style.css', ['media' => 'print'], 'footer');
```

### Removing a Script

To remove a script from the head or footer, use the `removeScript` method:

```php
TemplateMetaTags::removeScript('main-script', 'https://example.com/script.js', 'head');
TemplateMetaTags::removeScript('main-footer-script', 'https://example.com/script.js', 'footer');
```

### Removing a Style

To remove a style from the head or footer, use the `removeStyle` method:

```php
TemplateMetaTags::removeStyle('main-style');
TemplateMetaTags::removeStyle('main-footer-style');
```

