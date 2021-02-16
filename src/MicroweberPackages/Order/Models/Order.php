<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Order\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use MicroweberPackages\Content\Models\ModelFilters\ContentFilter;

class Order extends Model
{

    use Notifiable;
    use Filterable;

    public $table = 'cart_orders';

    public function modelFilter()
    {
        return $this->provideFilter(ContentFilter::class);
    }

}
