# SocialLinks

Social media profile links module. Displays configurable icons linking to your social media accounts on any page.

## Key Features

- Configurable social media profile URLs
- Icon-based display for common platforms
- Admin settings page for URL management
- Drop-in module tag for any page or layout

## Key Classes

| Class | Purpose |
|---|---|
| `Microweber\SocialLinksModule` | Microweber module registration |
| `Filament\SocialLinksModuleSettings` | Admin configuration page |

## Admin Panel (Filament)

- **SocialLinksModuleSettings** -- configure social media URLs (Facebook, Twitter, Instagram, LinkedIn, YouTube, etc.)

## Usage

```html
<module type="social_links" />
```

Configure your social media URLs in the admin panel under the SocialLinks module settings. The module renders linked icons for each configured platform.
