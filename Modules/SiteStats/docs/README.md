# `SiteStats` module

> **Slug:** `site-stats`
> **Tier:** 1
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

### `stats_users_online` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `created_by` | `integer` | nullable |
  | `view_count` | `integer` | nullable, has-default |
  | `referrer` | `string` | nullable |
  | `last_page` | `string` | nullable |
  | `visit_date` | `date` | nullable |
  | `visit_time` | `time` | nullable |
  | `session_id` | `string` | nullable |
  | `user_ip` | `string` | nullable |
  | `user_id` | `string` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_visits_log` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `url_id` | `integer` | nullable |
  | `referrer_id` | `integer` | nullable |
  | `view_count` | `integer` | nullable, has-default |
  | `session_id_key` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_browser_agents` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `browser_agent` | `text` | nullable |
  | `browser_agent_hash` | `string` | nullable |
  | `platform` | `string` | nullable |
  | `platform_version` | `string` | nullable |
  | `browser` | `string` | nullable |
  | `browser_version` | `string` | nullable |
  | `device` | `string` | nullable |
  | `is_desktop` | `integer` | nullable |
  | `is_mobile` | `integer` | nullable |
  | `is_phone` | `integer` | nullable |
  | `is_tablet` | `integer` | nullable |
  | `robot_name` | `text` | nullable |
  | `is_robot` | `string` | nullable |
  | `language` | `string` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_referrers` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `referrer` | `text` | nullable |
  | `referrer_hash` | `string` | nullable |
  | `referrer_domain_id` | `integer` | nullable |
  | `referrer_path_id` | `integer` | nullable |
  | `is_internal` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_referrers_domains` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `referrer_domain` | `text` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_referrers_paths` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `referrer_domain_id` | `integer` | nullable |
  | `referrer_path` | `string` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_urls` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `url` | `string` | nullable |
  | `content_id` | `integer` | nullable |
  | `category_id` | `integer` | nullable |
  | `url_hash` | `string` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_sessions` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `session_id` | `string` | nullable |
  | `session_hostname` | `string` | nullable |
  | `user_ip` | `string` | nullable |
  | `user_id` | `integer` | nullable |
  | `browser_id` | `integer` | nullable |
  | `referrer_id` | `integer` | nullable |
  | `referrer_domain_id` | `integer` | nullable |
  | `referrer_path_id` | `integer` | nullable |
  | `geoip_id` | `integer` | nullable |
  | `language` | `string` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_geoip` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `country_code` | `string` | nullable |
  | `country_name` | `string` | nullable |
  | `region` | `string` | nullable |
  | `city` | `string` | nullable |
  | `latitude` | `string` | nullable |
  | `longitude` | `string` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_events` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `event_category` | `string` | nullable |
  | `event_action` | `string` | nullable |
  | `event_label` | `string` | nullable |
  | `event_value` | `integer` | nullable |
  | `utm_source` | `string` | nullable |
  | `utm_medium` | `string` | nullable |
  | `utm_campaign` | `string` | nullable |
  | `utm_term` | `string` | nullable |
  | `utm_content` | `string` | nullable |
  | `utm_visitor_id` | `string` | nullable |
  | `event_data` | `text` | nullable |
  | `event_timestamp` | `dateTime` | nullable |
  | `session_id` | `string` | nullable |
  | `user_id` | `string` | nullable |
  | `is_sent` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

### `stats_pageviews` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `view_count` | `integer` | nullable, has-default |
  | `page_id` | `integer` | nullable |
  | `main_page_id` | `integer` | nullable |
  | `parent_page_id` | `integer` | nullable |
  | `category_id` | `integer` | nullable |
  | `session_id` | `string` | nullable |
  | `user_ip` | `string` | nullable |
  | `user_id` | `string` | nullable |
  | `referrer` | `string` | nullable |
  | `last_page` | `string` | nullable |
  | `visit_date` | `date` | nullable |
  | `visit_time` | `time` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\SiteStats\Models\Base`

Source: `Models/Base.php`. 

### `Modules\SiteStats\Models\Browsers`

Source: `Models/Browsers.php`. Table: `stats_browser_agents`. 

**Fillable:** `browser_agent_hash`, `browser_agent`, `updated_at`, `platform`, `platform_version`, `browser`, `browser_version`, `device`, `is_desktop`, `is_mobile`, `is_phone`, `is_tablet`, `is_robot`, `robot_name`, `language`

### `Modules\SiteStats\Models\Comments`

Source: `Models/Comments.php`. Table: `comments`. 

### `Modules\SiteStats\Models\ContentViewCounter`

Source: `Models/ContentViewCounter.php`. 

### `Modules\SiteStats\Models\Geoip`

Source: `Models/Geoip.php`. Table: `stats_geoip`. 

**Fillable:** `country_code`, `country_name`, `region`, `city`, `latitude`, `language`, `longitude`

### `Modules\SiteStats\Models\Log`

Source: `Models/Log.php`. Table: `stats_visits_log`. 

**Fillable:** `session_id_key`, `url_id`, `referrer_id`, `content_id`, `category_id`, `updated_at`, `view_count`

### `Modules\SiteStats\Models\Orders`

Source: `Models/Orders.php`. 

### `Modules\SiteStats\Models\Referrers`

Source: `Models/Referrers.php`. Table: `stats_referrers`. 

**Fillable:** `referrer_hash`, `referrer`, `referrer_domain_id`, `referrer_path_id`, `is_internal`, `updated_at`

### `Modules\SiteStats\Models\ReferrersDomains`

Source: `Models/ReferrersDomains.php`. Table: `stats_referrers_domains`. 

**Fillable:** `id`, `referrer_domain`, `updated_at`

### `Modules\SiteStats\Models\ReferrersPaths`

Source: `Models/ReferrersPaths.php`. Table: `stats_referrers_paths`. 

**Fillable:** `referrer_path`, `referrer_domain_id`, `updated_at`

### `Modules\SiteStats\Models\Sessions`

Source: `Models/Sessions.php`. Table: `stats_sessions`. 

**Fillable:** `session_id`, `referrer_id`, `referrer_domain_id`, `referrer_path_id`, `user_ip`, `user_id`, `geoip_id`, `browser_id`, `language`, `session_hostname`, `updated_at`

### `Modules\SiteStats\Models\StatsEvent`

Source: `Models/StatsEvent.php`. Table: `stats_events`. 

**Fillable:** `event_category`, `event_action`, `event_label`, `event_value`, `user_id`, `session_id`, `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`, `utm_visitor_id`, `event_data`, `event_timestamp`

### `Modules\SiteStats\Models\StatsUrl`

Source: `Models/StatsUrl.php`. Table: `stats_urls`. 

**Fillable:** `content_id`, `category_id`, `url_hash`, `url`, `updated_at`

### `Modules\SiteStats\Models\UserAttribute`

Source: `Models/UserAttribute.php`. Table: `attributes`. 

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `POST` | `api/pingstats` | `StatsController::pingStats` |

## Controllers

### `Modules\SiteStats\Http\Controllers\StatsController`

Source: `Http/Controllers/StatsController.php`.

  - `pingStats(Request $request)`

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

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\SiteStats\Filament\Pages\SiteStatsPage` | Dashboard | Site Statistics |
  | `Modules\SiteStats\Filament\SiteStatsDashboard` | — | — |
  | `Modules\SiteStats\Filament\SiteStatsDashboardChart` | — | — |
  | `Modules\SiteStats\Filament\SiteStatsDataTrait` | — | — |
  | `Modules\SiteStats\Filament\SiteStatsEchartsWidget` | — | — |
  | `Modules\SiteStats\Filament\Widgets\BrowsersWidget` | — | — |
  | `Modules\SiteStats\Filament\Widgets\LanguagesWidget` | — | — |
  | `Modules\SiteStats\Filament\Widgets\LocationsWidget` | — | — |
  | `Modules\SiteStats\Filament\Widgets\RecentVisitorsWidget` | — | — |
  | `Modules\SiteStats\Filament\Widgets\ReferrersWidget` | — | — |
  | `Modules\SiteStats\Filament\Widgets\StatsOverviewCards` | — | — |
  | `Modules\SiteStats\Filament\Widgets\TopPagesWidget` | — | — |
  | `Modules\SiteStats\Filament\Widgets\VisitorsChartWidget` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/SiteStats/Tests`

### `Tests/Filament/SiteStatsPageTest.php`

  - `it_page_class_exists`

## Service providers

  - `Modules\SiteStats\Providers\SiteStatsEventsLocalTrackingServiceProvider`
  - `Modules\SiteStats\Providers\SiteStatsServiceProvider`
  - `Modules\SiteStats\Providers\UtmTrackingEventsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
