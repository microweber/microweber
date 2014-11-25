<?php




use Illuminate\Database\Eloquent\Model as Eloquent;

class Notifications extends Eloquent
{

    public $table = 'notifications';

    public function __construct()
    {

    }


    public static function boot()
    {

        parent::boot();

    }

    public function rel()
    {
        return $this->morphTo();
    }







}

