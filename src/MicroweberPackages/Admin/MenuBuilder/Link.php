<?php

namespace MicroweberPackages\Admin\MenuBuilder;

use MicroweberPackages\Admin\MenuBuilder\Traits\HasIcon;

class Link extends \Spatie\Menu\Link
{
    use HasIcon;

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
