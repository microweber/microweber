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

namespace MicroweberPackages\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OrderAnonymousClient extends Model
{

    use Notifiable;

    public $table = 'cart_orders';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'phone' => ''
    ];

}
