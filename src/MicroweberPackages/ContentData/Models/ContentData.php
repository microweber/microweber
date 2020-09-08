<?php


namespace MicroweberPackages\ContentData\Models;

use Illuminate\Database\Eloquent\Model;



class ContentData extends Model
{
    protected $table = 'content_data';

    public $timestamps = true;

    protected $fillable = [
        'rel_type',
        'rel_id',
        'field_value',
        'field_name',
        'content_id',
       // 'edited_by',
       // 'created_by'
    ];



}
