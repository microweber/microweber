# Microweber Multisite — Docker sample

`docker-compose.multisite.yml` runs **one** Microweber codebase and **one** app
container that serves several fully independent sites, each with its own
database, cache, session and media/userfiles tree. The site is chosen on every
request from the `Host:` header by the
[`microweber-packages/multisite`](../packages/microweber-multisite/README.md)
package.

| Host | Database | Installed by |
|------|----------|--------------|
| `localhost` | `microweber` | the `app` container (first boot) |
| `site-a.localhost` | `mw_site_a` | the `site-provision` one-shot |
| `site-b.localhost` | `mw_site_b` | the `site-provision` one-shot |

## Run it

```bash
# 1) CLI/curl need the demo hosts (browsers already resolve *.localhost):
echo "127.0.0.1  site-a.localhost site-b.localhost" | sudo tee -a /etc/hosts

# 2) Build + start
docker compose -f docker-compose.multisite.yml up -d --build

# 3) Follow provisioning of the extra sites
docker compose -f docker-compose.multisite.yml logs -f site-provision
```

Then open `http://localhost`, `http://site-a.localhost`, `http://site-b.localhost`.
Admin panel for each: `/admin` — `admin@admin.com` / `admin`.

## How it works

1. **`app`** boots and, finding no install marker, runs `microweber:install`
   into the `microweber` DB (the default site). `MODULES_ACTIVATOR=database` and
   `TEMPLATES_ACTIVATOR=database` make each site store its own enabled
   modules/templates in its own DB rather than a shared JSON file.
2. **`db`** creates the extra databases on first start from
   `docker/mysql/init-multisite/01-databases.sql`.
3. **`site-provision`** (one-shot) waits for the default install, then for each
   extra domain runs:
   - `php artisan multisite:install <domain> --db-name=<db> …` — writes
     `config/<domain>/microweber.php` (the detector marker) and `.env.<domain>`
     (its DB creds, cache prefix, session cookie). `APP_KEY` is **not** written
     there — Laravel overlays `.env.<domain>` on the base `.env`, so the shared
     key is inherited.
   - `php artisan microweber:install --db-name=<db> …` — populates that site's
     database with schema, default content and an admin user.

   The installer always rewrites the base `.env`, so the script backs it up and
   restores it afterwards; the default site keeps pointing at `microweber`.

## What is isolated per site

`config/multisite.php` (`isolate.*`) controls this — by default cache, session,
userfiles and `app_url`:

| Subsystem | `localhost` | `site-a.localhost` |
|-----------|-------------|--------------------|
| Database | `.env` → `microweber` | `.env.site-a.localhost` → `mw_site_a` |
| Cache files/prefix | `storage/framework/cache` | `…/cache/sites/site-a.localhost`, prefix `mw_site_a_localhost_cache` |
| Sessions | `storage/framework/sessions` | `…/sessions/sites/site-a.localhost` |
| Userfiles / media | `storage/app/public/userfiles` | `…/userfiles/site-a.localhost` |

## Add another site

1. Create its database (add a `CREATE DATABASE` line to
   `docker/mysql/init-multisite/01-databases.sql`, or `CREATE DATABASE` by hand
   against the running `db`).
2. Add `domain:dbname` to the `SITES=` list in
   `docker/entrypoint-multisite-provision.sh`.
3. Add the host to `/etc/hosts`, then re-run the provisioner:
   `docker compose -f docker-compose.multisite.yml up -d site-provision`.

## Notes / limitations

- This sample uses the **development** image target with the source bind-mounted
  so `config/<domain>/` markers and `.env.<domain>` files persist on the host.
  For a production multisite, bake those into the image or mount them as a
  volume and switch the services to `target: production`.
- `*.localhost` resolves to `127.0.0.1` in modern browsers automatically; other
  clients need the `/etc/hosts` entry above.
- Provisioning briefly rewrites the base `.env`; it runs once at startup and is
  restored before the provisioner exits.
