<?php

namespace MicroweberPackages\Admin\MenuBuilder;

use MicroweberPackages\Admin\MenuBuilder\Traits\HasIcon;
use MicroweberPackages\Admin\MenuBuilder\Traits\HasOrder;

class Link extends \Spatie\Menu\Link
{
    use HasIcon;
    use HasOrder;

    public bool $active = false;

    public string $route = '';

    public static function route($route, $text)
    {
        $url = route($route);

        $instance = new static($url, $text);
        $instance->route = $route;

        return $instance;
    }
}
