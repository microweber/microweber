<?php

namespace MicroweberPackages\Admin\MenuBuilder\Traits;

trait HasOrder
{
    public $order = 0;

    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

}
