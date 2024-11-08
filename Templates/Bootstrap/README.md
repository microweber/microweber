# Bootstrap Components

This package provides a set of reusable Bootstrap components for Laravel applications using Blade components with the `x-` prefix.

## Installation

1. Install the package via composer:
```bash
composer require microweber-templates/bootstrap
```

2. The components will be automatically registered via the service provider.

## Available Components

### Alert
Display alert messages with different styles.

```blade
<x-bootstrap::alert type="success" dismissible>
    Your changes have been saved successfully!
</x-bootstrap::alert>
```

**Props:**
- `type` (string): success, danger, warning, info, primary, secondary, light, dark
- `dismissible` (boolean): Adds a close button to dismiss the alert

### Button
Create Bootstrap styled buttons.

```blade
<x-bootstrap::button type="primary" size="lg">
    Click Me
</x-bootstrap::button>
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
<x-bootstrap::card>
    <x-slot name="header">Card Title</x-slot>
    
    Card content goes here
    
    <x-slot name="footer">Card Footer</x-slot>
</x-bootstrap::card>
```

**Props:**
- `headerClass` (string): Additional classes for header
- `bodyClass` (string): Additional classes for body
- `footerClass` (string): Additional classes for footer

### Modal
Create Bootstrap modals.

```blade
<x-bootstrap::modal id="exampleModal" title="Modal Title">
    <x-slot name="body">
        Modal content goes here
    </x-slot>
    
    <x-slot name="footer">
        <x-bootstrap::button type="secondary" data-bs-dismiss="modal">Close</x-bootstrap::button>
        <x-bootstrap::button type="primary">Save changes</x-bootstrap::button>
    </x-slot>
</x-bootstrap::modal>
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
<x-bootstrap::navbar brand="My App" brandUrl="/">
    <x-bootstrap::nav-item href="/" active>Home</x-bootstrap::nav-item>
    <x-bootstrap::nav-item href="/about">About</x-bootstrap::nav-item>
    <x-bootstrap::nav-item href="/contact">Contact</x-bootstrap::nav-item>
</x-bootstrap::navbar>
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
<x-bootstrap::input 
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
<x-bootstrap::select 
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
<x-bootstrap::checkbox 
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
<x-bootstrap::pagination 
    :items="$posts"
/>
```

**Props:**
- `items` (mixed): The paginator instance
- `size` (string): sm, lg

### Progress Bar
Display progress bars.

```blade
<x-bootstrap::progress-bar 
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
<x-bootstrap::tabs>
    <x-bootstrap::tab-pane title="Home" active>
        Home content
    </x-bootstrap::tab-pane>
    <x-bootstrap::tab-pane title="Profile">
        Profile content
    </x-bootstrap::tab-pane>
</x-bootstrap::tabs>
```

**Props:**
- `vertical` (boolean): Creates vertical tabs
- `pills` (boolean): Uses pill style

## Styling

All components use Bootstrap 5 classes and can be customized using standard Bootstrap utilities:

```blade
<x-bootstrap::button 
    type="primary" 
    class="shadow rounded-pill"
>
    Custom Styled Button
</x-bootstrap::button>
```

## JavaScript Components

Some components (like modals, tooltips, etc.) require Bootstrap's JavaScript. Make sure you have included Bootstrap's JS file and initialized the components:

```html
@vite(['resources/js/app.js'])
```

## RTL Support

All components automatically support RTL when the page direction is set to RTL:

```html
<html dir="rtl">
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
