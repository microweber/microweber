# Deps Laravel Repository

Vendored fork of **[torann/laravel-repository](https://github.com/Torann/laravel-repository)** — a base repository implementation for Laravel with scopes (search, order-by), caching and pagination — patched for Laravel 13 compatibility. This package `replace`s `torann/laravel-repository` in Composer so the upstream library resolves to this fork across the Microweber monorepo.

> This is a vendored dependency. It keeps the upstream `Torann\LaravelRepository\*` namespace and API unchanged — there is **no Microweber-specific public API**. Microweber consumes it by implementing its repository contract.

Part of the [Microweber](https://microweber.com) CMS.

## Installation

```bash
composer require microweber-packages/microweber-deps-laravel-repository
```

`"replace": { "torann/laravel-repository": "*" }` — once installed, anything depending on `torann/laravel-repository` uses this fork.

## What it provides

- **`Torann\LaravelRepository\Repository`** — abstract base repository with `find()`, `findOrFail()`, `findBy()`, `findAllBy()`, `findWhere()`, `all()`, `count()`, `pluck()`, `paginate()`, `simplePaginate()`, `create()`, `update()`, `delete()`, `newQuery()`, `addJoin()`, scope/cache plumbing.
- **Contracts** — `Torann\LaravelRepository\Contracts\Repository`, `Contracts\Scope`.
- **Scopes** — `Scopes\Search`, `Scopes\OrderBy`, `Scopes\Scope`.
- **Concerns** — `Searching`, `Ordering`, `Scopes`, `Cacheable`, `Messages`.
- **Config** — `config/repositories.php` (per-page defaults, cache toggle, core scopes).
- **Provider** — `Torann\LaravelRepository\RepositoryServiceProvider` (merges/publishes the config). Not auto-discovered here; Microweber registers its own repository provider.

## Usage

Extend the base repository and point it at a model:

```php
use Torann\LaravelRepository\Repository;

class PostRepository extends Repository
{
    protected $model = \App\Models\Post::class;
}

$repo = new PostRepository();
$post = $repo->find(1);
$list = $repo->paginate(20);
```

### How Microweber uses it

`MicroweberPackages\Repository\Repositories\AbstractRepository` implements
`Torann\LaravelRepository\Contracts\Repository` (from this fork) as the base for all
Microweber content/product/etc. repositories.

## Configuration

`config/repositories.php`:

| Key | Default | Purpose |
| --- | --- | --- |
| `per_page` | `50` | Default pagination page size |
| `max_per_page` | `100` | Upper bound on page size |
| `cache_enabled` | `true` | Enable repository result caching |
| `cache_skip_param` | `'skipCache'` | Query-string param that bypasses cache (e.g. `?skipCache=true`) |
| `scopes` | `search`, `order_by` | Core scope classes used for searching/ordering |

## Key classes

- Base repository: `Torann\LaravelRepository\Repository`
- Contract: `Torann\LaravelRepository\Contracts\Repository`
- Scopes: `Torann\LaravelRepository\Scopes\Search`, `Scopes\OrderBy`
- Provider: `Torann\LaravelRepository\RepositoryServiceProvider`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

BSD-2-Clause
