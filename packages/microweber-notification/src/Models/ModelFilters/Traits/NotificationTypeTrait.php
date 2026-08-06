<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Models\ModelFilters\Traits;

/**
 * Filter helpers mixed into EloquentFilter ModelFilter subclasses.
 *
 * @property \Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Model> $query
 */
trait NotificationTypeTrait
{
    /**
     * Filter notifications by type fragment (LIKE match).
     */
    public function type(string $type): mixed
    {
        return $this->query->where('type', 'LIKE', '%' . $type . '%');
    }
}
