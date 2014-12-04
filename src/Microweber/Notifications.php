<?php


use Illuminate\Database\Eloquent\Model as Eloquent;

class Notifications extends Eloquent
{

    public $table = 'notifications';



    public static function boot()
    {

        parent::boot();


    }
}

