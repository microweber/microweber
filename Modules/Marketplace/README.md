# Marketplace

Module and template marketplace. Browse, install, update, and manage Microweber modules, templates, and extensions from the central marketplace. Also manages license keys.

## Key Features

- Browse available modules and templates from the Microweber marketplace
- Install and update extensions directly from the admin panel
- License key management
- Marketplace item catalog with metadata

## Key Classes

| Class | Purpose |
|---|---|
| `Models\MarketplaceItem` | Marketplace listing model |
| `Filament\Admin\MarketplaceResource` | Admin CRUD for marketplace items |
| `Filament\Admin\ListLicenses` | License key management (Livewire) |

## Admin Panel (Filament)

- **MarketplaceResource** -- browse and manage marketplace items
- **ListLicenses** -- view and manage license keys

## Usage

Access the marketplace through the admin panel. The module integrates with the WhiteLabel module for license management.
