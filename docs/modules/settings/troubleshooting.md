# Settings Module — Troubleshooting

## `save_option()` returns true but `get_option()` returns the old value

**Most common cause:** request-lifetime cache. `OptionRepository` caches reads per request — within the same request, mutations may not be visible to subsequent reads.

Workaround for a single request:

```php
save_option('foo', 'new value', 'website');

// Force cache flush
app('option_repository')->flushCache();
// Or:
\Cache::tags(['options', 'settings'])->flush();

$value = get_option('foo', 'website');  // 'new value'
```

In separate requests this is invisible — each request boots its own cache.

## Per-locale option not picked up

1. **Active locale not set?** `app()->getLocale()` should return the expected locale BEFORE the `get_option()` call.
2. **Stored under a different locale?** Inspect: `\DB::table('options')->where('option_key', $key)->where('option_group', $group)->pluck('lang')`.
3. **`lang = null` row missing?** The lookup chain falls back to the `null`-locale row if the locale-specific row is missing. Ensure a default (`lang = null`) row exists OR every locale you care about has its own row.

## SMTP test mail fails after Settings save

1. **Config cache stale** — `php artisan config:clear`. Laravel's mail config may have been cached before the Settings save.
2. **Filament form wrote to the wrong group** — verify in `options` table: `option_group = 'email'`, not `'mail'` or `'smtp'`.
3. **Password contains special characters** — verify the password wasn't mangled by Filament's form serialization (rare; check the actual stored `option_value`).
4. **Network egress blocked** — `telnet smtp.mailgun.org 587` from the server. If blocked, fix at the host/firewall level.

## Maintenance mode never lets me in

1. **Allowed IPs format** — comma-separated, no spaces: `'203.0.113.5,198.51.100.7'`, NOT `'203.0.113.5, 198.51.100.7'` (the middleware splits on `,` only).
2. **CDN / proxy in front?** `$_SERVER['REMOTE_ADDR']` may be the CDN's IP, not yours. Configure `TrustedProxies` middleware and use `$request->ip()` in the maintenance check.
3. **Browser cached the maintenance page** — hard-refresh (`Cmd/Ctrl + Shift + R`) or open a private window.

To disable maintenance via SQL when locked out:

```sql
UPDATE options SET option_value = '0' WHERE option_key = 'maintenance_mode' AND option_group = 'website';
```

## Template change doesn't take effect

`save_option('current_template', 'Big2', 'website')` should switch templates, but the public site keeps rendering the old one.

1. **Multiple `current_template` rows** — `\DB::table('options')->where('option_key', 'current_template')->get()` should return one row per locale (or just one if you don't use multilang). Multiple rows with the same scope cause unpredictable lookup.
2. **`Templates/<New>/` directory missing** — the template name must match a directory. Case-sensitive on Linux.
3. **View cache** — `php artisan view:clear`.

## "Maximum execution time" timeout on the Filament admin page

Some sections do an expensive setup (loading every available template's `style-settings.json`, etc.). If the page times out:

1. Increase PHP `max_execution_time` to 60+ seconds for admin requests.
2. Lazy-load the heavy sections: the Filament page should only fetch the data for the currently-active tab.
3. If the slowdown is the `options` table itself growing large, add an index: `CREATE INDEX idx_options_group_key ON options (option_group, option_key)`.

## REST API write returns 422 "is_system option cannot be modified"

`is_system = 1` options are install-managed and protected. To force-overwrite:

```bash
curl -X DELETE "https://yoursite.com/api/settings/important_flag?option_group=system&force=1" \
    -H "Authorization: Bearer $TOKEN"
```

Or via SQL:

```sql
UPDATE options SET is_system = 0 WHERE id = ?;
```

Drop the `is_system` guard for that row, then save normally. Re-promote to system after by setting `is_system = 1` again.

## Settings page shows blank / "no records"

1. **Cache:** `php artisan filament:cache-components && php artisan view:clear`.
2. **Module not registered:** verify `Modules/Settings/module.json` is autoloaded; `composer dump-autoload`.
3. **Panel config:** `app/Providers/Filament/AdminPanelProvider.php` should auto-discover Filament Pages under the Settings namespace. If you added a panel filter that excludes "Settings" subdirectories, the pages won't register.

## Options table growing very large

Each `save_option()` upserts — there shouldn't be duplicate rows for the same `(key, group, module, lang)`. If the table is growing unexpectedly:

```sql
SELECT option_key, option_group, module, lang, COUNT(*) c
FROM options
GROUP BY option_key, option_group, module, lang
HAVING c > 1
ORDER BY c DESC;
```

Dedupe the worst offenders manually. If duplicates keep accumulating, look for code paths that call `Option::create()` directly instead of `Option::updateOrCreate()` — that's the canonical bug pattern.

## Cache tag flushes don't propagate to queue workers

Laravel's tagged cache flushes work per-process; queue workers running long-lived might miss invalidations. Force-restart workers after a bulk options change:

```bash
php artisan queue:restart
```

## Where to file bugs

- Settings module: `Modules/Settings/`. Tests in `Modules/Settings/Tests/`.
- Data-layer bugs (cache, lookup chain, helpers): file against the **Option package** (`src/MicroweberPackages/Option/`) — that's the canonical owner of the schema, model, and helpers.
- Filament-page-specific UX bugs: against the Settings module.
