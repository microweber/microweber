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

namespace MicroweberPackages\Shop\ClientsManager;

class Clients extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'cart_clients';
    protected $timestamps = [ "created_at", "updated_at" ];
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'user_id',
        'updated_at',
        'created_at',
    ];


}
