<?php

namespace MicroweberPackages\Multilanguage\Models\Traits;


trait HasMultilanguageTrait
{

    private $_addCustomFields = [];

    public function initializeHasMultilanguageTrait()
    {
        $this->fillable[] = 'multilanguage';
    }

    public static function bootHasMultilanguageTrait()
    {
        static::saving(function ($model) {

        });

        static::creating(function ($model) {

        });
    }
}
