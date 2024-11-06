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

## Model
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

## License
This module is licensed under the MIT License.
