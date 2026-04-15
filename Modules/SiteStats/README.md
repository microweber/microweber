# SiteStats

Built-in site analytics and statistics. Tracks page views, sessions, browsers, referrers, geographic data, and e-commerce conversion events without external dependencies.

## Key Features

- Page view and session tracking via lightweight JS ping
- Browser and user-agent detection
- Referrer tracking (domains and paths)
- GeoIP visitor location data
- UTM parameter tracking
- E-commerce event tracking (add to cart, checkout, payment, purchase)
- User registration and login event tracking
- ECharts-based dashboard widget
- Dedicated full statistics admin page
- Can be disabled via admin settings

## Configuration

| Option | Group | Description |
|---|---|---|
| `stats_disabled` | `site_stats` | Set to `1` to disable tracking |

## Key Classes

| Class | Purpose |
|---|---|
| `Repositories\SiteStatsRepository` | Query layer for stats data |
| `Models\Log` | Page view log entries |
| `Models\Sessions` | Active sessions |
| `Models\Browsers` | Browser/user-agent records |
| `Models\Referrers` / `ReferrersDomains` / `ReferrersPaths` | Referrer data |
| `Models\Geoip` | Geographic location data |
| `Models\StatsEvent` | Custom analytics events |
| `Models\StatsUrl` | URL tracking |
| `Models\ContentViewCounter` | Per-content view counts |

## Events

- `PingStatsEvent` -- fired on each page view ping
- `DispatchLocalTracking` -- local tracking dispatch

Listens to (via `UtmTrackingEventsServiceProvider`):
- `Login`, `Registered` -- user auth events
- `AddToCartEvent`, `RemoveFromCartEvent` -- cart events
- `BeginCheckoutEvent`, `AddPaymentInfoEvent`, `AddShippingInfoEvent` -- checkout events
- `OrderWasPaid` -- purchase completion

## Database Tables

- `stats_visits_log` -- page view log
- `stats_users_online` -- active visitors
- `stats_browser_agents` -- browser data
- `stats_referrers` / `stats_referrers_domains` / `stats_referrers_paths` -- referrer data
- `stats_urls` -- tracked URLs
- `stats_sessions` -- session data
- `stats_geoip` -- geographic data
- `stats_events` -- custom events
- `stats_pageviews` -- aggregated page views

## Admin Panel (Filament)

- **SiteStatsEchartsWidget** -- dashboard chart widget (registered on main Dashboard)
- **SiteStatsPage** -- full statistics page with detailed analytics

## API

Routes in `routes/api.php` (ping endpoint for JS tracker).
