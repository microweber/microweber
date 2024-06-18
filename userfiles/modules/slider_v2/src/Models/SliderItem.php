<?php

namespace MicroweberPackages\Modules\SliderV2\Models;

use MicroweberPackages\LiveEdit\Models\ModuleItemSushi;

class SliderItem extends ModuleItemSushi
{
    protected $fillable = [
        'id',
        "url",
        "showButton",
        "buttonText",
        "title",
        "description",
        "image",
        "titleColor",
        "descriptionColor",
        "buttonColor",
        "buttonTextColor",
        "titleFontSize",
        "descriptionFontSize",
        "buttonFontSize",
        "titleFontFamily",
        "descriptionFontFamily",
        "imageBackgroundColor",
        "imageBackgroundOpacity",
        'position',
    ];
    protected array $schema = [
        'id' => 'string',

        "url" => 'string',
        "showButton" => 'string',
        "buttonText" => 'string',
        "title" => 'string',
        "description" => 'string',
        "image" => 'string',
        "titleColor" => 'string',
        "descriptionColor" => 'string',
        "buttonColor" => 'string',
        "buttonTextColor" => 'string',
        "titleFontSize" => 'string',
        "descriptionFontSize" => 'string',
        "buttonFontSize" => 'string',
        "titleFontFamily" => 'string',
        "descriptionFontFamily" => 'string',
        "imageBackgroundColor" => 'string',
        "imageBackgroundOpacity" => 'string',

        'position' => 'integer',
    ];
}
