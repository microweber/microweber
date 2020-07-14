<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */

namespace MicroweberPackages\Shop\InvoicesManager;

use MicroweberPackages\DatabaseManager\Crud;

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
            $this->app = app();
        }
    }

}
