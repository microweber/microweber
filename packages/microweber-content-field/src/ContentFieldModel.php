<?php

namespace MicroweberPackages\ContentField;

use Illuminate\Database\Eloquent\Model;

class ContentFieldModel extends Model
{
    protected $table = 'content_fields';

    /** @var list<string> */
    protected $fillable = [
        'rel_type',
        'rel_id',
        'field',
        'value',
        'created_by',
        'edited_by',
    ];
}
