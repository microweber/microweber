<?php
namespace MicroweberPackages\ContentManager;

use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use Taggable;

    public $table = 'content';

    public function notifications()
    {
        return $this->morphMany('Notifications', 'rel');
    }

    public function comments()
    {
        return $this->morphMany('Comments', 'rel');
    }

    public function data_fields()
    {
        return $this->morphMany('ContentData', 'rel');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}