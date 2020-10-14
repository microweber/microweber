<?php
namespace MicroweberPackages\Post\Models;

use MicroweberPackages\Content\Content;
use MicroweberPackages\Content\Scopes\PostScope;

class Post extends Content
{
    protected $table = 'content';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'url',
        'parent',
        'description',
        'position',
        'content',
        'content_body',
        'is_active',
        'is_home',
        'is_shop',
        'is_deleted',
        'status'
    ];

    public $translatable = ['title','url','description','content','content_body'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new PostScope());
    }
}
