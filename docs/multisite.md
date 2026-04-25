# Multisite

Microweber boots a separate environment per domain when a directory
named after that domain exists under `config/`. Each domain gets its
own database credentials, cache prefix, and session — so a single
checkout of the codebase can serve N independent sites.

> **Status:** salvaged 2026-04-25 from
> `microweber-docs/integration/multisite.md`. Verified against
> `src/MicroweberPackages/App/Providers/AppServiceProvider.php` —
> the per-domain detection logic referenced here still ships in the
> current codebase.

---

## How detection works

`AppServiceProvider::detectEnvironment()` registers a callback on
Laravel's `Application::detectEnvironment()` hook. On every request
the callback resolves the host (`request()->getHost()`) and checks:

```php
if (is_dir(config_path($domain)) && is_file(config_path($domain) . '/microweber.php')) {
    return strtolower($domain);
}
```

If the directory + file pair exists, Laravel's environment becomes
the domain name (e.g. `domain-a.com`), which in turn changes the
`.env` file Laravel loads (`.env.domain-a.com` if present), the
config-cache key, and any environment-aware bindings. If the pair
does **not** exist, the app falls back to the default environment
(usually `production` or `local`).

---

## Setup

1. Create a directory named after each domain under `config/`.
2. Place an empty `microweber.php` file in each so the detector
   recognises the domain.

```text
config/
├── domain-a.com/
│   └── microweber.php
├── domain-b.com/
│   └── microweber.php
└── microweber.php          # shared default; not domain-specific
```

3. Visit each domain in the browser. The first visit triggers the
   install flow scoped to that domain; the installer writes the
   per-domain DB credentials into `config/<domain>/microweber.php`.
4. Repeat for every domain.

Both files can start empty — the install flow populates them.

---

## DNS

Point each domain's `A` record at the same server IP. The Laravel app
discriminates on the `Host:` header, so DNS is the only piece that
needs to differ:

```text
DomainA.com.   IN  A   123.45.67.8
*.DomainA.com. IN  A   123.45.67.8

DomainB.com.   IN  A   123.45.67.8
*.DomainB.com. IN  A   123.45.67.8
```

The wildcard records are optional — include them only if you actually
want subdomains to resolve to the same site.

---

## Scripted install (one shot per domain)

The CLI installer (`docs/installation.md`) honours the same
`config/<domain>/microweber.php` lookup when invoked under a
domain-scoped environment. To bootstrap a domain non-interactively:

```bash
APP_ENV=domain-a.com php artisan microweber:install \
  --email=admin@domain-a.com \
  --username=admin \
  --password=admin \
  --db-name=domain_a \
  --db-driver=mysql \
  --db-host=127.0.0.1 \
  --db-username=root \
  --db-password=secret \
  --db-prefix=mw_ \
  --template=Bootstrap
```

Repeat with the next domain's `APP_ENV` to provision a second site.
Useful in Dockerfile `RUN` lines and CI bootstrap scripts.

---

## Caveats

- **Shared codebase.** All domains share the same `Modules/`,
  `vendor/`, and `public/` files. Per-domain customisation lives in
  `userfiles/<domain>/` (templates, uploads) and
  `config/<domain>/microweber.php` (DB + service credentials).
- **No admin UI.** There's no in-admin "add a domain" button — adding
  a site is a filesystem + DNS operation.
- **Session isolation.** Sessions are scoped per-environment, so a
  user logged into Domain A is not logged into Domain B even though
  the cookies carry the same name. This is by design.
