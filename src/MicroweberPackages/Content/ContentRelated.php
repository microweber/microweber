<?php

namespace MicroweberPackages\Content;


use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\MaxPositionTrait;


class ContentRelated extends Model
{
    use MaxPositionTrait;

    protected $table = 'content_related';

    protected $fillable = [
        'content_id',
        'related_content_id',
        'position'
    ];

}