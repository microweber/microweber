<?php

namespace MicroweberPackages\Comment;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Models\ModelFilters\ContentFilter;
use MicroweberPackages\Database\Casts\MarkdownCast;

class Comment extends Model
{
    use Filterable;

    public $table = 'comments';

    protected $fillable = [
        'rel_type',
        'rel_id',
        'comment_name',
        'comment_email',
        'comment_website',
        'comment_body',
    ];

//    protected $casts = [
//        'comment_body'=>MarkdownCast::class
//    ];

    public function modelFilter()
    {
        return $this->provideFilter(ContentFilter::class);
    }

}