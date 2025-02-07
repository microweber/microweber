<?php

namespace Modules\Faq\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{

    protected $fillable = [
        'id',
        'question',
        'answer',
        'position',
        'is_active',
        'rel_type',
        'rel_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer'
    ];

}
