<?php
namespace MicroweberPackages\Notification\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Notification\Models\ModelFilters\NotificationFilter;

class Notification extends Model
{
    protected $casts = [
        'data' => 'array'
    ];

    use Filterable;


    public function modelFilter()
    {
        return $this->provideFilter(NotificationFilter::class);
    }
}