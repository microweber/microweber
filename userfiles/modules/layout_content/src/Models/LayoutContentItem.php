<?php

namespace MicroweberPackages\Modules\LayoutContent\Models;

use MicroweberPackages\LiveEdit\Models\ModuleItemSushi;

class LayoutContentItem extends ModuleItemSushi
{
    protected $fillable = [
        'id',
        'image',
        'title',
        'description',
        'buttonText',
        'buttonLink',
        'position',
    ];
    protected array $schema = [
        'id' => 'string',
        'image' => 'string',
        'title' => 'string',
        'description' => 'string',
        'buttonText' => 'string',
        'buttonLink' => 'string',
        'position' => 'integer',
    ];
}
