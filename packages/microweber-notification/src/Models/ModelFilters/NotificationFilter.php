<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use MicroweberPackages\Notification\Models\ModelFilters\Traits\NotificationTypeTrait;

class NotificationFilter extends ModelFilter
{
    use NotificationTypeTrait;
}
