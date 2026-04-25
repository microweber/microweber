# `SiteStats` module

> **Slug:** `site-stats`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

Migrations under `Modules/SiteStats/database/migrations/`:

  - `database/migrations/2023_10_01_000001_create_stats_users_online_table.php`
  - `database/migrations/2023_10_01_000002_create_stats_visits_log_table.php`
  - `database/migrations/2023_10_01_000003_create_stats_browser_agents_table.php`
  - `database/migrations/2023_10_01_000004_create_stats_referrers_table.php`
  - `database/migrations/2023_10_01_000005_create_stats_referrers_domains_table.php`
  - `database/migrations/2023_10_01_000006_create_stats_referrers_paths_table.php`
  - `database/migrations/2023_10_01_000007_create_stats_urls_table.php`
  - `database/migrations/2023_10_01_000008_create_stats_sessions_table.php`
  - `database/migrations/2023_10_01_000009_create_stats_geoip_table.php`
  - `database/migrations/2023_10_01_000010_create_stats_events_table.php`
  - `database/migrations/2023_10_01_000011_create_stats_pageviews_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\SiteStats\Models\Base` | `Models/Base.php` |
| `Modules\SiteStats\Models\Browsers` | `Models/Browsers.php` |
| `Modules\SiteStats\Models\Comments` | `Models/Comments.php` |
| `Modules\SiteStats\Models\ContentViewCounter` | `Models/ContentViewCounter.php` |
| `Modules\SiteStats\Models\Geoip` | `Models/Geoip.php` |
| `Modules\SiteStats\Models\Log` | `Models/Log.php` |
| `Modules\SiteStats\Models\Orders` | `Models/Orders.php` |
| `Modules\SiteStats\Models\Referrers` | `Models/Referrers.php` |
| `Modules\SiteStats\Models\ReferrersDomains` | `Models/ReferrersDomains.php` |
| `Modules\SiteStats\Models\ReferrersPaths` | `Models/ReferrersPaths.php` |
| `Modules\SiteStats\Models\Sessions` | `Models/Sessions.php` |
| `Modules\SiteStats\Models\StatsEvent` | `Models/StatsEvent.php` |
| `Modules\SiteStats\Models\StatsUrl` | `Models/StatsUrl.php` |
| `Modules\SiteStats\Models\UserAttribute` | `Models/UserAttribute.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\SiteStats\Http\Controllers\StatsController`

## Events

  - `Modules\SiteStats\Events\DispatchLocalTracking`
  - `Modules\SiteStats\Events\PingStatsEvent`
  - `Modules\SiteStats\Listeners\AddPaymentInfoListener`
  - `Modules\SiteStats\Listeners\AddShippingInfoListener`
  - `Modules\SiteStats\Listeners\AddToCartListener`
  - `Modules\SiteStats\Listeners\BeginCheckoutListener`
  - `Modules\SiteStats\Listeners\OrderWasCreatedListener`
  - `Modules\SiteStats\Listeners\OrderWasPaidListener`
  - `Modules\SiteStats\Listeners\RemoveFromCartListener`
  - `Modules\SiteStats\Listeners\UserWasLoggedListener`
  - `Modules\SiteStats\Listeners\UserWasRegisteredListener`
  - `Modules\SiteStats\Listeners\UserWasRegisteredLocalListener`

## Filament admin

  - `Modules\SiteStats\Filament\Pages\SiteStatsPage`
  - `Modules\SiteStats\Filament\SiteStatsDashboard`
  - `Modules\SiteStats\Filament\SiteStatsDashboardChart`
  - `Modules\SiteStats\Filament\SiteStatsDataTrait`
  - `Modules\SiteStats\Filament\SiteStatsEchartsWidget`
  - `Modules\SiteStats\Filament\Widgets\BrowsersWidget`
  - `Modules\SiteStats\Filament\Widgets\LanguagesWidget`
  - `Modules\SiteStats\Filament\Widgets\LocationsWidget`
  - `Modules\SiteStats\Filament\Widgets\RecentVisitorsWidget`
  - `Modules\SiteStats\Filament\Widgets\ReferrersWidget`
  - `Modules\SiteStats\Filament\Widgets\StatsOverviewCards`
  - `Modules\SiteStats\Filament\Widgets\TopPagesWidget`
  - `Modules\SiteStats\Filament\Widgets\VisitorsChartWidget`

## Tests

Run: `php vendor/bin/phpunit Modules/SiteStats/Tests`

Test files:

  - `Tests/Filament/SiteStatsPageTest.php`
  - `Tests/Unit/StatsControllerTest.php`

## Service providers

  - `Modules\SiteStats\Providers\SiteStatsEventsLocalTrackingServiceProvider`
  - `Modules\SiteStats\Providers\SiteStatsServiceProvider`
  - `Modules\SiteStats\Providers\UtmTrackingEventsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
