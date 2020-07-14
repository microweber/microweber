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

namespace MicroweberPackages\Client;

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
