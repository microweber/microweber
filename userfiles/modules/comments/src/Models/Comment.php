<?php
namespace MicroweberPackages\Modules\Comments\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = [
        'rel_id',
        'comment_name',
        'comment_body',
        'comment_email',
        'comment_website',
    ];

}
