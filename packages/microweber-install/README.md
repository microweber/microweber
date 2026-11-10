# Install

The installer for Microweber CMS. Provides the `microweber:install` artisan command, the web
installer controller/views, the database-schema definitions, and the installers that seed default
options, modules, templates and default content for a fresh site.

## What it provides

- **`microweber:install` artisan command** — CLI (or env-driven) installer.
- **`InstallController`** — the install flow (`index()`), template download/selection views, env writing, logging.
- **Schema definitions** — the base tables created on install (`InstallSchemas` + the `Schema\*` classes).
- **Installers** — `DefaultOptionsInstaller`, `ModulesInstaller`, `TemplateInstaller`, `WebserverInstaller`, `UpdateMissingConfigFiles`.
- **Views** — the `install::` namespace (template picker, install layout) registered by the provider.

## Installation

```bash
composer require microweber-packages/microweber-install
```

`InstallServiceProvider::boot()` only registers the `install::` view namespace. The
`microweber:install` command is registered by the Microweber application provider
(`MicroweberPackages\App\Providers\AppServiceProvider`), so it is available once the full CMS is
booted.

## Usage

### CLI install

Run with no options to perform a "lazy" install (SQLite, the `Big` template, default content):

```bash
php artisan microweber:install
```

Or pass connection and admin options explicitly:

```bash
php artisan microweber:install \
  --db-driver=mysql --db-host=127.0.0.1 --db-port=3306 \
  --db-name=microweber --db-username=root --db-password=secret --db-prefix=mw_ \
  --email=admin@example.com --username=admin --password=secret \
  --template=Big --default-content=1 --language=en --app-url=https://example.com
```

Supported options: `--db-host`, `--db-port`, `--db-name`, `--db-username`, `--db-password`,
`--db-driver`, `--db-prefix`, `--email`, `--username`, `--password`, `--default-content`,
`--template`, `--config-only`, `--language`, `--app-url`, `--app-debug`.

### Install from environment variables

When no options are given the command also reads env vars:

```bash
export DB_HOST=localhost
export DB_USER=user
export DB_PASS=pass
export DB_ENGINE=sqlite
export DB_NAME=storage/database.sqlite
export DB_PREFIX=mw_

php artisan microweber:install
```

### Programmatic install

The command delegates to `InstallController::index($input)`; you can drive it directly:

```php
use MicroweberPackages\Install\Http\Controllers\InstallController;

$installer = app(InstallController::class);
$installer->index([
    'db_driver'            => 'sqlite',
    'db_name'              => 'storage/database.sqlite',
    'admin_email'          => 'admin@example.com',
    'admin_username'       => 'admin',
    'admin_password'       => 'secret',
    'default_template'     => 'Big',
    'with_default_content' => true,
    'make_install'         => true,
]);
```

The installers it orchestrates can also be used on their own:

```php
(new MicroweberPackages\Install\DefaultOptionsInstaller())->run();
(new MicroweberPackages\Install\ModulesInstaller())->run();

$templates = new MicroweberPackages\Install\TemplateInstaller();
$templates->setSelectedTemplate('Big');
$templates->setInstallDefaultContent(true);
$templates->run();

// The ordered list of schema table definitions applied on install
$schemas = MicroweberPackages\Install\InstallSchemas::get();
```

## Key classes

- `MicroweberPackages\Install\Console\Commands\InstallCommand` — the `microweber:install` command.
- `MicroweberPackages\Install\Http\Controllers\InstallController` — install flow (`index`, `downloadTemplate`, `selectTemplateView`, `saveEnvValues`, logging).
- `MicroweberPackages\Install\InstallSchemas` — ordered list of base table schema classes.
- `MicroweberPackages\Install\Schema\*` — individual table builders (`Base`, `Tags`, `Countries`, `MailTemplates`, `JobsQueue`, `Updates`, …).
- `MicroweberPackages\Install\DefaultOptionsInstaller` — seeds default site options (language, comments, shipping, payments).
- `MicroweberPackages\Install\ModulesInstaller` — installs/activates modules.
- `MicroweberPackages\Install\TemplateInstaller` — installs the selected template + default content.
- `MicroweberPackages\Install\WebserverInstaller` — webserver (Apache) config.
- `MicroweberPackages\Install\UpdateMissingConfigFiles` — copies missing config stubs.
- `MicroweberPackages\Install\InstallServiceProvider` — registers the `install::` view namespace.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Nikolay Rachkov
- Petko Yovchevski
- Alexander Raikov

## License

MIT
