# `HostingApi` module

> **Slug:** `hosting-api`
> **Tier:** 4
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

This module owns no migrations of its own.

## Models

### `Modules\HostingApi\Models\ApiKey`

Source: `Models/ApiKey.php`. Table: `hosting_api_keys`. 

**Fillable:** `name`, `api_key`, `api_secret`, `is_active`, `whitelisted_ips`, `last_used_at`

**Casts:**

  - `is_active` → `boolean`
  - `last_used_at` → `datetime`

### `Modules\HostingApi\Models\Domain`

Source: `Models/Domain.php`. Table: `hosting_domains`. 

**Fillable:** `domain`, `ip`, `hosting_subscription_id`, `server_application_type`, `server_application_settings`, `status`, `is_main`, `document_root`

**Casts:**

  - `is_main` → `boolean`
  - `server_application_settings` → `array`

### `Modules\HostingApi\Models\HostingPlan`

Source: `Models/HostingPlan.php`. Table: `hosting_plans`. 

**Fillable:** `name`, `description`, `disk_space`, `bandwidth`, `databases`, `ftp_accounts`, `email_accounts`, `subdomains`, `parked_domains`, `addon_domains`, `ssl_certificates`, `daily_backups`, `free_domain`, `default_server_application_type`, `default_database_server_type`, `default_remote_database_server_id`, `default_server_application_settings`, `additional_services`, `features`, `limitations`

**Casts:**

  - `daily_backups` → `boolean`
  - `free_domain` → `boolean`
  - `default_server_application_settings` → `array`
  - `additional_services` → `array`
  - `features` → `array`
  - `limitations` → `array`

### `Modules\HostingApi\Models\HostingSubscription`

Source: `Models/HostingSubscription.php`. Table: `hosting_subscriptions`. 

**Fillable:** `customer_id`, `hosting_plan_id`, `domain`, `system_username`, `system_password`, `status`, `description`, `setup_date`, `expiry_date`

**Casts:**

  - `setup_date` → `datetime`
  - `expiry_date` → `datetime`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
