<?php



class Content extends BaseModel
{

    public $table = 'content';

//    public static function boot()
//    {
//        parent::boot();
//
//        static::observe(new BaseModelObserver);
//    }

    public function notifications()
    {
        return $this->morphMany('Notifications', 'rel');
    }

    public function comments()
    {
        return $this->morphMany('Comments', 'rel');
    }

 }

