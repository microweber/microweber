<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace MicroweberPackages\Notification\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use MicroweberPackages\Notification\Models\ModelFilters\Traits\NotificationTypeTrait;

class NotificationFilter extends ModelFilter
{
    use NotificationTypeTrait;

}