<?php

namespace MicroweberPackages\Searchable;

/**
 * HasSearchableTrait - Adds searchable field definitions to Eloquent models.
 *
 * Models using this trait should define a `$searchable` property (array of column names)
 * that are allowed for filtering/searching. Optionally, a `$searchableByKeyword` property
 * can be defined for keyword-specific search fields.
 *
 * Works on all SQL engines (MySQL, PostgreSQL, SQLite, SQL Server).
 *
 * Usage:
 *   use HasSearchableTrait;
 *
 *   protected $searchable = ['title', 'email', 'name'];
 *   protected $searchableByKeyword = ['title', 'description'];  // optional
 */
trait HasSearchableTrait
{
    /**
     * Get the list of searchable fields for this model.
     *
     * @return array
     */
    public function getSearchable(): array
    {
        return $this->searchable ?? [];
    }

    /**
     * Get the list of fields searchable by keyword.
     * Falls back to the general searchable fields if not explicitly defined.
     *
     * @return array
     */
    public function getSearchableByKeyword(): array
    {
        if (isset($this->searchableByKeyword) && !empty($this->searchableByKeyword)) {
            return $this->searchableByKeyword;
        }

        return $this->getSearchable();
    }

    /**
     * Scope to search across the model's searchable fields using LIKE.
     * Works on all SQL engines.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @param array|null $fields Override which fields to search (defaults to searchableByKeyword)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $keyword, ?array $fields = null)
    {
        $searchFields = $fields ?? $this->getSearchableByKeyword();
        $table = $this->getTable();

        if (empty($searchFields) || trim($keyword) === '') {
            return $query;
        }

        return $query->where(function ($q) use ($searchFields, $keyword, $table) {
            foreach ($searchFields as $field) {
                $q->orWhere($table . '.' . $field, 'LIKE', '%' . $keyword . '%');
            }
        });
    }

    /**
     * Scope to search using exact match on searchable fields.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $field
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchExact($query, string $field, $value)
    {
        $searchable = $this->getSearchable();

        if (!in_array($field, $searchable)) {
            return $query;
        }

        return $query->where($this->getTable() . '.' . $field, '=', $value);
    }

    /**
     * Check if a field is searchable.
     *
     * @param string $field
     * @return bool
     */
    public function isSearchableField(string $field): bool
    {
        return in_array($field, $this->getSearchable());
    }
}