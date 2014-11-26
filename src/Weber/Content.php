<?php

/**
 * @property $comments
 */

class Content extends BaseModel
{

    public $table = 'content';


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

    public function comments()
    {
        return $this->morphMany('Comments', 'rel');
    }


    public function init_db()
    {
        $table_name = $this->table;
    }


}

