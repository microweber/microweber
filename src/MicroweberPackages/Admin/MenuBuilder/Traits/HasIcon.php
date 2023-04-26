<?php

namespace MicroweberPackages\Admin\MenuBuilder\Traits;

trait HasIcon
{
    public $icon = null;

    public function icon($icon)
    {
        $this->icon = $icon;
        return $this;
    }
}
