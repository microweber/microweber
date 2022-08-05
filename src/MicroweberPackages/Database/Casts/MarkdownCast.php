<?php

namespace MicroweberPackages\Database\Casts;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MarkdownCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {

        return  $value;
    }

    public function set($model, $key, $value, $attributes)
    {
        $value = Markdown::convertToHtml($value)->getContent();

        return [$key => $value];
    }
}
