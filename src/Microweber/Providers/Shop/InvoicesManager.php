<?php

namespace Microweber\Providers\Shop;

use Microweber\Providers\Database\Crud;

class InvoicesManager extends Crud
{
    /** @var \Microweber\Application */
    public $app;

    public $table = 'cart_invoices';

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

}
