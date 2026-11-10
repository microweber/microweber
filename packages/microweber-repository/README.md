# Microweber Repository

A reusable Laravel package providing the repository pattern with built-in caching, query filtering, and model observers.

## Installation

```bash
composer require microweber-packages/microweber-repository
```

Inside Microweber the `RepositoryServiceProvider` is registered automatically by the framework's package discovery (it also registers the `RepositoryEventServiceProvider` and the model observer). In a standalone Laravel app register it manually (`MicroweberPackages\Repository\Providers\RepositoryServiceProvider::class`) and publish the config with `php artisan vendor:publish` if you want to tune caching.

## Usage

### AbstractRepository

Extend `AbstractRepository` and set the `$model` property:

```php
use MicroweberPackages\Repository\Repositories\AbstractRepository;

class PostRepository extends AbstractRepository
{
    public $model = Post::class;
}
```

### CachingModelRepository

For simple cache-wrapped model queries:

```php
use MicroweberPackages\Repository\Repositories\CachingModelRepository;

class PostRepository extends CachingModelRepository
{
    protected string $modelClass = Post::class;

    public function getPublished(): array
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () {
            return Post::where('status', 'published')->get()->toArray();
        });
    }
}
```

### FilterableByParams Trait

Add filtering capabilities to any Eloquent model:

```php
use MicroweberPackages\Repository\Traits\FilterableByParams;

class Post extends Model
{
    use FilterableByParams;
}

// Then use it (scopeFilterByParams):
$results = Post::filterByParams([
    'limit' => 10,
    'order_by' => 'created_at desc',
    'status' => 'active',
])->get();
```

### AbstractRepository query API

`AbstractRepository` wraps a model with a fluent, cache-aware query layer. Beyond `find()` / `findBy()` / `all()` it offers `search()`, `orderBy()`, `paginate()`, `create()`, `update()`, `delete()`, `save()`, plus a message bag (`hasErrors()`, `getMessageBag()`) and cache control (`clearCache()`, `getCacheKey()`):

```php
$repo = new PostRepository();

$repo->search('laravel', ['limit' => 20])
     ->orderBy('created_at', 'desc')
     ->paginate(15);

$post = $repo->create(['title' => 'Hello']);
$repo->update($post, ['title' => 'Updated']);
```

## Configuration

Config file: `config/repository.php`.

| Key | Env | Default | Purpose |
|-----|-----|---------|---------|
| `cache_enabled` | `REPOSITORY_CACHE_ENABLED` | `false` | Enable repository query caching |
| `cache_skip_param` | — | `skipCache` | Request param that bypasses the cache |
| `per_page` | — | `15` | Default pagination size |
| `max_per_page` | — | `100` | Maximum pagination size |

## Key classes

- `MicroweberPackages\Repository\Repositories\AbstractRepository`
- `MicroweberPackages\Repository\Repositories\CachingModelRepository`
- `MicroweberPackages\Repository\Traits\FilterableByParams`
- `MicroweberPackages\Repository\RepositoryManager` (resolved by the `Repository` facade)
- `MicroweberPackages\Repository\Observers\RepositoryModelObserver`

## Testing

```bash
composer test
```

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
