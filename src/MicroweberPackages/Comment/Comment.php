<?php

namespace MicroweberPackages\Comment;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table = 'comments';

    protected $fillable = [
        'rel_type',
        'rel_id',
        'comment_name',
        'comment_email',
        'comment_website',
        'comment_body',
    ];
}
