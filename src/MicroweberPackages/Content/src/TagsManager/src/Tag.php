<?php
namespace MicroweberPackages\Content\TagsManager;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\ContentManager\Content;

class Tag extends Model
{
    public $table = 'tags';

    protected $guarded = array();

    public function content()
    {
        return $this->belongsToMany(Content::class);
    }
}