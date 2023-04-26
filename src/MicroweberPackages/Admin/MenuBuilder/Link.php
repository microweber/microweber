<?php

namespace MicroweberPackages\Admin\MenuBuilder;

use MicroweberPackages\Admin\MenuBuilder\Traits\HasIcon;

class Link extends \Spatie\Menu\Link
{
    use HasIcon;

    public bool $active = false;
}
