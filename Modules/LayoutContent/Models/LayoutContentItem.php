<?php

namespace Modules\LayoutContent\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\LayoutContent\Database\Factories\LayoutContentItemFactory;

class LayoutContentItem extends Model
{
    use FiresModelLifecycleEvents;

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
