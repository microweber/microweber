<?php



class Categories extends BaseModel
{

    public $table = 'categories';
    // called once when Post is first used
    public static function boot()
    {
        parent::boot();
    }


    public function notifications()
    {
        return $this->morphMany('Notifications', 'rel_type');
    }
 }

