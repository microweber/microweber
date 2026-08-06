<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Notification\Models\ModelFilters\NotificationFilter;

/**
 * Laravel database notification row.
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property string $notifiable_id
 * @property array<string, mixed>|null $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder<static> filter(array<string, mixed> $input = [], mixed $filter = null)
 */
class Notification extends Model
{
    use Filterable;

    /**
     * @var list<string>
     */
    public array $cacheTagsToClear = ['repositories'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'json',
            'id' => 'string',
            'read_at' => 'datetime',
        ];
    }

    public function modelFilter(): ?string
    {
        /** @var string $filter */
        $filter = $this->provideFilter(NotificationFilter::class);

        return $filter;
    }
}
