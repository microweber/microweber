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

    protected $table = 'modules';

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

    // called for every new instance of Post
//    public function __construct(PostRepository $posts)
//    {
//        $this->posts = $posts;
//    }
}

