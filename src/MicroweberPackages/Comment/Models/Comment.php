<?php

namespace MicroweberPackages\Comment\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Models\ModelFilters\ContentFilter;
use MicroweberPackages\Core\Models\HasSearchableTrait;

class Comment extends Model
{
    use Filterable;
    use HasSearchableTrait;

    public $table = 'comments';

    protected $fillable = [
        'rel_type',
        'rel_id',
        'comment_name',
        'comment_email',
        'comment_website',
        'comment_body',
    ];

    protected $searchable = [
        'comment_name',
        'comment_email',
        'comment_website',
        'comment_body',
    ];

    public function modelFilter()
    {
        return $this->provideFilter(ContentFilter::class);
    }

}
