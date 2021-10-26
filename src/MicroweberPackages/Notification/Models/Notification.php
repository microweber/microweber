<?php
namespace MicroweberPackages\Notification\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Notification\Models\ModelFilters\NotificationFilter;

class Notification extends Model
{
    use Filterable;

    protected $casts = [
        'data' => 'json',
        'id' => 'string'
    ];

    public $cacheTagsToClear = ['repositories'];

    public function modelFilter()
    {
        return $this->provideFilter(NotificationFilter::class);
    }
}
