<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/23/14
 * Time: 11:56 PM
 */


use Illuminate\Database\Eloquent\Model as Eloquent;

class Module extends Eloquent
{

    public $table = 'modules';


    public function __construct(){

    }

    // called once when Post is first used
    public static function boot()
    {
        // there is some logic in this method, so don't forget this!
        parent::boot();

    }

    public function notifications()
    {
        return $this->morphMany('Notifications', 'rel');
    }


    public function init_db(){
        $table_name = $this->table;

    }


}

