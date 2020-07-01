<?php
namespace MicroweberPackages\ClientsManager;

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
