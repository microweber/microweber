# Multisite (microweber-packages/multisite)

Per-domain multisite isolation for Laravel — and Microweber on top of it. One
checkout of the codebase serves **N independent sites**, each with its own
**database, cache, session and media/userfiles tree**, selected by the incoming
`Host:` header.

It is a standalone Laravel package (Spatie package-tools) and can be used in a
clean Laravel app as a proof of concept. It depends on
[`microweber-packages/env-writer`](../microweber-env-writer) to write each
domain's `.env.<domain>` credentials.

## How a site is resolved

A **site** is a domain that has a marker file:

```
config/
├── domain-a.com/microweber.php   ← marker (presence is the signal)
├── domain-b.com/microweber.php
└── app.php                       ← normal shared config
```

On each request `MultisiteManager::detectSiteFromHost()` takes the host,
strips `www.` and `:port`, lowercases it, rejects path traversal, and returns
the domain **iff** `config/<domain>/microweber.php` exists. No match → the app
runs as the default single site (zero behaviour change).

This is the same contract Laravel's environment detector already uses, so the
domain also becomes the Laravel environment and loads `.env.<domain>` (hence
the per-site **database**).

## What gets isolated

| Subsystem | Default site | `domain-a.com` |
|-----------|--------------|----------------|
| Database | `.env` | `.env.domain-a.com` (own `DB_DATABASE`) |
| Cache prefix | `config('cache.prefix')` | `mw_domain_a_com_cache` |
| Cache files | `storage/framework/cache` | `storage/framework/cache/sites/domain-a.com` |
| Session cookie | `laravel_session` | `mw_domain_a_com_session` |
| Session files | `storage/framework/sessions` | `storage/framework/sessions/sites/domain-a.com` |
| Userfiles / media | `storage/app/public/userfiles` | `storage/app/public/userfiles/domain-a.com` |

Each subsystem is toggleable in `config/multisite.php` (`isolate.*`).

## Two integration seams

1. **Autoload-time constant** (`src/helpers.php`, registered via composer
   `autoload.files`). Runs before any service provider and before Microweber's
   `bootstrap.php`, defining `MW_USERFILES_FOLDER_NAME = "userfiles/<site>"` so
   every `userfiles_path()` / `media_base_path()` call is already per-site. In a
   clean Laravel app this define is simply inert.

2. **Service provider** (`MultisiteServiceProvider`) applies the cache/session
   (and optional public-disk) config overrides during `register()`, before
   those managers are first resolved.

## Clean-Laravel PoC

```bash
composer require microweber-packages/multisite
```

Enable media isolation without Microweber by turning on the public disk in
`config/multisite.php`:

```php
'isolate' => ['cache' => true, 'session' => true, 'userfiles' => false, 'public_disk' => true],
```

Create `config/<domain>/microweber.php` (any file returning `[]`), point the
domain at the app, and the `Storage::disk('public')`, cache and session for that
host are transparently namespaced.

## Commands

```bash
php artisan multisite:list                 # show configured sites + their isolated paths
php artisan multisite:install domain-a.com \
    --db-name=mw_domain_a --db-username=root --db-password=secret
```

`multisite:install` creates the `config/<domain>/microweber.php` marker and
writes `.env.<domain>` (via env-writer) with the DB credentials, cache prefix
and session cookie. Then create the DB and point DNS at the server.

## API

```php
use MicroweberPackages\Multisite\Facades\Multisite;

Multisite::current();            // 'domain-a.com' | null (default site)
Multisite::isActive();           // bool
Multisite::sites();              // ['domain-a.com', 'domain-b.com']
Multisite::userfilesFolder();    // 'userfiles/domain-a.com'
Multisite::cachePrefix();        // 'mw_domain_a_com_cache'
```

## Tests

```bash
cd packages/microweber-multisite && composer install && composer test
# or from the project root:
php vendor/bin/phpunit packages/microweber-multisite/tests
```
