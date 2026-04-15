# GoogleAnalytics

Google Analytics integration. Injects the GA4 measurement tag and supports enhanced conversions for e-commerce event tracking.

## Key Features

- Google Analytics 4 (GA4) measurement ID injection
- Enhanced conversion tracking with conversion ID/label
- E-commerce event dispatch integration (via SiteStats ping events)
- Debug mode for development
- Admin settings page for configuration

## Configuration

Defined in `config/config.php` and stored via `get_option()`:

| Option | Env Variable | Default | Description |
|---|---|---|---|
| `enabled` | `GOOGLE_ANALYTICS_ENABLED` | `false` | Enable GA tracking |
| `measurement_id` | `GOOGLE_ANALYTICS_MEASUREMENT_ID` | `G-XXXXXXXX` | GA4 measurement ID |
| `api_secret` | `GOOGLE_ANALYTICS_API_SECRET` | `null` | API secret for server-side events |
| `enhanced_conversions.enabled` | `GOOGLE_ANALYTICS_ENHANCED_CONVERSIONS` | `false` | Enable enhanced conversions |
| `enhanced_conversions.conversion_id` | `GOOGLE_ANALYTICS_CONVERSION_ID` | `null` | Google Ads conversion ID |
| `enhanced_conversions.conversion_label` | `GOOGLE_ANALYTICS_CONVERSION_LABEL` | `null` | Conversion label |
| `debug` | `GOOGLE_ANALYTICS_DEBUG` | `false` | Enable debug mode |

The `google-measurement-enabled` option (group `website`) controls whether e-commerce event JS dispatch is active.

## Admin Panel (Filament)

- **AdminGoogleAnalyticsSettingsPage** -- configure measurement ID, API secret, and enhanced conversions

## Usage

Configure the measurement ID in the admin panel. The tracking script is automatically injected into all frontend pages when enabled.
