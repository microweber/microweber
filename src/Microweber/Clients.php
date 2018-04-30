<?php



class Clients extends Base
{
    protected $table = 'cart_clients';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'user_id',
        'updated_at',
        'created_at',

    ];


}