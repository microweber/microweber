<?php
namespace MicroweberPackages\Content\MediaManager;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use \Conner\Tagging\Taggable;

    public $table = 'media';

    public static function boot()
    {
        parent::boot();
    }

    public function tags()
    {
        return $this->belongsToMany('Tag');
    }
}