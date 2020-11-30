<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Notification\Models\ModelFilters\Traits;

trait NotificationTypeTrait
{
    public function type($type)
    {
        return $this->query->where('type', 'LIKE', "%$type%");
    }


}