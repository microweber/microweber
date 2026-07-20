<?php

namespace MicroweberPackages\ContentField;

use Illuminate\Database\Eloquent\Model;

class ContentFieldDraftModel extends Model
{
    protected $table = 'content_fields_drafts';

    /** @var list<string> */
    protected $fillable = [
        'rel_type',
        'rel_id',
        'field',
        'value',
        'session_id',
        'is_temp',
        'url',
        'created_by',
        'edited_by',
    ];
}
