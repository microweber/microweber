<?php

namespace MicroweberPackages\Modules\Faq\Models;

use MicroweberPackages\LiveEdit\Models\ModuleItemSushi;

class FaqItem extends ModuleItemSushi
{
    protected $fillable = [
        'id',
        'question',
        'answer',
        'position',
    ];

}
