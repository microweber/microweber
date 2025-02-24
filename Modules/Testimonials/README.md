# Testimonials Module for Microweber CMS

## Overview
The Testimonials module enables you to manage and display client testimonials on your Microweber CMS website effectively. This module utilizes Laravel, Filament, and Livewire for a seamless user experience.

## Features
- **Add, edit, and delete testimonials.**
- **Display testimonials in various formats.**

## Installation
To install the Testimonials module, run the following commands:

```sh
php artisan module:migrate Testimonials
php artisan module:publish Testimonials
```

## Usage
Access the Testimonials module from the admin panel to manage testimonials.

## Configuration
Configure module settings in the admin panel under the Testimonials settings section.

## Models
The `Testimonial` model represents a testimonial entry in the database.

### Database Table
The module uses the `testimonials` table with the following fields:

| Field Name       | Type      | Description                             |
|------------------|-----------|-----------------------------------------|
| `id`             | Integer   | Primary key                             |
| `name`           | String    | Name of the client                      |
| `content`        | Text      | Content of the testimonial              |
| `client_company` | String    | Company of the client                   |
| `client_role`    | String    | Role of the client                      |
| `client_website` | String    | Website of the client                   |
| `client_image`   | String    | Image of the client                     |
| `position`       | Integer   | Position of the testimonial in the list |
| `rel_type`       | String    | Relation type                           |
| `rel_id`         | String    | Relation ID                             |
| `created_at`     | Timestamp | Timestamp for when the testimonial was created |
| `updated_at`     | Timestamp | Timestamp for when the testimonial was last updated |

## Templates
The Testimonials module comes with multiple pre-built templates located in `resources/views/templates/`. These templates provide different styles and layouts for displaying testimonials.

### Available Templates
- `default.blade.php`: Default grid layout with client image, name, content, company, and role
- Multiple skin variations (skin-1 through skin-23) offering different styling options

### Creating Custom Templates
To create a custom template:

1. Create a new Blade file in `resources/views/templates/` directory
2. Add the required template metadata at the top of the file:
```php
@php
/*
type: layout
name: Your Template Name
description: Your Template Description
*/
@endphp
```

3. Structure your template using HTML and Blade syntax. Available variables:
   - `$testimonials`: Collection of testimonial items
   - `$params['id']`: Unique module instance ID

Example template structure:
```blade
<div class="your-template-class">
    @if($testimonials->isEmpty())
        <p>No testimonials found</p>
    @else
        @foreach($testimonials as $item)
            <div class="testimonial-item">
                @if(isset($item->client_image))
                    <img src="{{ $item->client_image }}" alt="{{ $item->name }}">
                @endif
                
                @if(isset($item->name))
                    <h3>{{ $item->name }}</h3>
                @endif
                
                @if(isset($item->content))
                    <div class="content">{!! $item->content !!}</div>
                @endif
                
                @if(isset($item->client_company))
                    <div class="company">{{ $item->client_company }}</div>
                @endif
                
                @if(isset($item->client_role))
                    <div class="role">{{ $item->client_role }}</div>
                @endif
                
                @if(isset($item->client_website))
                    <a href="{{ $item->client_website }}" target="_blank">{{ $item->client_website }}</a>
                @endif
            </div>
        @endforeach
    @endif
</div>
```

### Available Fields
Each testimonial item (`$item`) has the following fields:
- `name`: Client name
- `content`: Testimonial content
- `client_company`: Company name
- `client_role`: Client's role/position
- `client_website`: Client's website URL
- `client_image`: Path to client's image

## License
This module is licensed under the MIT License.
