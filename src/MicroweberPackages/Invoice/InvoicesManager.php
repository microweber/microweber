<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Invoice;

use MicroweberPackages\Database\Crud;

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
