<?php

namespace Modules\Accordion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accordion extends Model
{
    protected $table = 'accordion';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'icon',
        'content',
        'position',
        'rel_id',
        'rel_type',
        'updated_at',
        'created_at',
    ];

}
