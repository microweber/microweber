<?php

namespace Modules\Slider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'media',
        'link',
        'button_text',
        'settings',
        'rel_id',
        'rel_type',
        'position'
    ];

    protected $casts = [
        'settings' => 'json',
        'position' => 'integer'
    ];
}
