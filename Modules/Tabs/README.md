# Tabs Module for Microweber CMS

## Overview
The Tabs module allows you to create and manage tabbed content on your Microweber CMS website. Built using Laravel, this module leverages Filament and Livewire to provide a user-friendly interface for organizing content into tabs, enhancing the overall user experience.

## Installation
To install the Tabs module, follow these steps:

1. Run the module migrations:
   ```sh
   php artisan module:migrate Tabs
   ```

2. Publish the module assets:
   ```sh
   php artisan module:publish Tabs
   ```

## Models Information
The main model used in this module is `Tab`, which interacts with the `tabs` database table.

### Database Table: `tabs`
| Field Name  | Type       | Description                          |
|-------------|------------|--------------------------------------|
| `id`        | Integer    | Primary key                          |
| `title`     | String     | Title of the tab                     |
| `icon`      | String     | Icon associated with the tab         |
| `content`   | Long Text  | Content displayed in the tab         |
| `position`  | Integer    | Position of the tab in the list      |
| `rel_type`  | String     | Relation type                        |
| `rel_id`    | String     | Relation ID                          |
| `settings`  | Long Text  | Additional settings for the tab      |
| `created_at`| Timestamp  | Timestamp for when the tab was created |
| `updated_at`| Timestamp  | Timestamp for when the tab was last updated |

## Features
- Create, edit, and delete tab items.
- Organize content into a tabbed layout for better user navigation.
- Customize tab settings and appearance.

## License
This module is licensed under the MIT License.
