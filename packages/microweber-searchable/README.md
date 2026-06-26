# Microweber Searchable

A standalone Laravel package that adds searchable field definitions to Eloquent models. Works on all SQL engines (MySQL, PostgreSQL, SQLite, SQL Server).

## Installation

```bash
composer require microweber-packages/searchable
```

## Usage

Add the `HasSearchableTrait` to your Eloquent model and define which fields are searchable:

```php
use MicroweberPackages\Searchable\HasSearchableTrait;

class User extends Model
{
    use HasSearchableTrait;

    protected $searchable = [
        'email',
        'username',
        'first_name',
        'last_name',
    ];

    // Optional: define separate fields for keyword search
    protected $searchableByKeyword = [
        'first_name',
        'last_name',
    ];
}
```

### Searching

```php
// Search using LIKE across keyword-searchable fields
User::search('john')->get();

// Search specific fields
User::search('john', ['first_name', 'last_name'])->get();

// Exact match on a searchable field
User::searchExact('email', 'john@example.com')->first();

// Check if a field is searchable
$user = new User();
$user->isSearchableField('email'); // true
$user->isSearchableField('password'); // false
```

### API

| Method | Description |
|--------|-------------|
| `getSearchable()` | Returns the list of searchable fields |
| `getSearchableByKeyword()` | Returns keyword-search fields (falls back to `$searchable`) |
| `scopeSearch($query, $keyword, $fields)` | LIKE search scope |
| `scopeSearchExact($query, $field, $value)` | Exact match scope |
| `isSearchableField($field)` | Check if a field is in the searchable list |

## License

MIT