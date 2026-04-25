# Marketplace

Module and template marketplace. Browse, install, update, and manage Microweber modules, templates, and extensions from the central marketplace. Also manages license keys.

> **Dusk coverage:** intentionally absent. The Marketplace listing is fed by the upstream Microweber marketplace API (opt-in, network-dependent), which a Dusk smoke cannot exercise without the network round-trip — a failure of which would be a false positive against the local checkout. The local CRUD on `MarketplaceItem` rows is exercised by the Filament-resource smokes for any item created locally; license-key persistence is covered by `Tests/` unit tests in this module. See Plan C.3 in `TODO.md`.


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
