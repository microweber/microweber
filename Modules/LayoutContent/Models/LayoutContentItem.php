<?php

namespace Modules\LayoutContent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\LayoutContent\Database\Factories\LayoutContentItemFactory;

class LayoutContentItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'image_alt_text',
        'button_text',
        'button_link',
        'rel_type',
        'rel_id',
    ];

}
