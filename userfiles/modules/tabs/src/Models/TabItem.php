<?php

namespace MicroweberPackages\Modules\Tabs\Models;

use MicroweberPackages\LiveEdit\Models\ModuleItemSushi;

class TabItem extends ModuleItemSushi
{
    protected $fillable = [
        'id',
        'title',
        'icon',
        'position',
    ];
    protected array $schema = [
        'id' => 'string',
        'title' => 'string',
        'icon' => 'string',
        'position' => 'integer',
    ];
}
