# Bootstrap Components for Microweber CMS

## Publish module assets

```sh
php artisan module:publish Components
```

# Bootstrap Components

This package provides a set of reusable Bootstrap components for Laravel applications using Blade components with the
`x-` prefix.

## Installation

1. Install the package via composer:

```bash
composer require microweber-modules/components
```

2. The components will be automatically registered via the service provider.

## Available Components

### Alert

Display alert messages with different styles.

```blade
<x-alert type="success" dismissible>
    Your changes have been saved successfully!
</x-alert>
```

**Props:**

- `type` (string): success, danger, warning, info, primary, secondary, light, dark
- `dismissible` (boolean): Adds a close button to dismiss the alert

### Button

Create Bootstrap styled buttons.

```blade
<x-button type="primary" size="lg">
    Click Me
</x-button>
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
<x-card>
    <x-slot name="header">Card Title</x-slot>
    
    Card content goes here
    
    <x-slot name="footer">Card Footer</x-slot>
</x-card>
```

**Props:**

- `headerClass` (string): Additional classes for header
- `bodyClass` (string): Additional classes for body
- `footerClass` (string): Additional classes for footer

### Modal

Create Bootstrap modals.

```blade
<x-modal id="exampleModal" title="Modal Title">
    <x-slot name="body">
        Modal content goes here
    </x-slot>
    
    <x-slot name="footer">
        <x-button type="secondary" data-bs-dismiss="modal">Close</x-button>
        <x-button type="primary">Save changes</x-button>
    </x-slot>
</x-modal>
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
<x-navbar brand="My App" brandUrl="/">
    <x-nav-item href="/" active>Home</x-nav-item>
    <x-nav-item href="/about">About</x-nav-item>
    <x-nav-item href="/contact">Contact</x-nav-item>
</x-navbar>
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
<x-input 
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
<x-select 
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
<x-checkbox 
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
<x-pagination 
    :items="$posts"
/>
```

**Props:**

- `items` (mixed): The paginator instance
- `size` (string): sm, lg

### Progress Bar

Display progress bars.

```blade
<x-progress-bar 
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
<x-tabs>
    <x-tab-pane title="Home" active>
        Home content
    </x-tab-pane>
    <x-tab-pane title="Profile">
        Profile content
    </x-tab-pane>
</x-tabs>
```

**Props:**

- `vertical` (boolean): Creates vertical tabs
- `pills` (boolean): Uses pill style

## Styling

All components use Bootstrap 5 classes and can be customized using standard Bootstrap utilities:

```blade
<x-button 
    type="primary" 
    class="shadow rounded-pill"
>
    Custom Styled Button
</x-button>
```

## RTL Support

All components automatically support RTL when the page direction is set to RTL:

```html

<html dir="rtl">
```

## Example Usage in Blade Templates

```html

<x-container>

    <x-hero editable="true" align="center">

        <x-slot name="image">{{asset('templates/bootstrap/img/heros/illustration-2.png')}}</x-slot>

        <x-slot name="title">
            <h1>Welcome to Microweber</h1>
        </x-slot>
        <x-slot name="content">
            <p>
                Microweber is a drag and drop website builder and a powerful next-generation CMS. It's easy to use, and
                it's a great tool for building websites, online shops, blogs, and more. It's based on the Laravel PHP
                framework and the Bootstrap front-end framework.
                
                
                
            </p>
        </x-slot>
        <x-slot name="actions">
            <a href="#" class="btn btn-primary">
                Get Started
            </a>
            <a href="#" class="btn btn-secondary">
                Learn More
            </a>
        </x-slot>
    </x-hero>

</x-container>


<x-container>

    <x-simple-text align="right">
        <x-slot name="title">
            <h1>Welcome to Microweber</h1>
        </x-slot>
        <x-slot name="content">
            <p>
                Microweber is a drag and drop website builder and a powerful next-generation CMS. It's easy to use, and
                it's a great tool for building websites, online shops, blogs, and more. It's based on the Laravel PHP
                framework and the Bootstrap front-end framework.
            </p>
        </x-slot>
    </x-simple-text>

    <x-row>

        <x-col size="4">
            <x-card>

                <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>

                <x-slot name="title">
                    Microweber Card
                </x-slot>

                <x-slot name="content">
                    <p>
                        Some quick example text to build on the card title and make up the bulk of the card's content.
                    </p>
                </x-slot>

                <x-slot name="footer">
                    <a href="#" class="btn btn-primary">
                        Go somewhere
                    </a>
                </x-slot>

            </x-card>
        </x-col>

        <x-col size="4">
            <x-card theme="success">

                <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>

                <x-slot name="title">
                    CloudVision Cart
                </x-slot>

                <x-slot name="content">
                    <p>
                        Some quick example text to build on the card title and make up the bulk of the card's content.
                    </p>
                </x-slot>

                <x-slot name="footer">
                    <a href="#" class="btn btn-primary">
                        Go somewhere
                    </a>
                </x-slot>

            </x-card>
        </x-col>

        <x-col size="4">
            <x-card theme="danger">

                <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>

                <x-slot name="title">
                    Card
                </x-slot>

                <x-slot name="content">
                    <p>
                        Some quick example text to build on the card title and make up the bulk of the card's content.
                    </p>
                </x-slot>

                <x-slot name="footer">
                    <a href="#" class="btn btn-primary">
                        Go somewhere
                    </a>
                </x-slot>

            </x-card>
        </x-col>
    </x-row>
</x-container>

```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
