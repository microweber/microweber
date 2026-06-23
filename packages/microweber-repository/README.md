# Microweber Repository

A reusable Laravel package providing the repository pattern with built-in caching, query filtering, and model observers.

## Installation

```bash
composer require microweber-packages/repository
```

The service provider is auto-discovered by Laravel.

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

// Then use it:
$results = Post::filterByParams([
    'limit' => 10,
    'order_by' => 'created_at desc',
    'status' => 'active',
])->get();
```

## Testing

```bash
composer test
```

## License

MIT