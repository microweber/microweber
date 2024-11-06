<?php

namespace Modules\Tabs\Models;


use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    protected $fillable = [
        'id',
        'title',
        'icon',
        'position',
        'rel_id',
        'rel_type',
        'settings',
    ];

}
