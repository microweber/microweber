<?php

namespace MicroweberPackages\Translation\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationKey extends Model
{
    public $timestamps = false;

    public function texts()
    {
        return $this->hasMany(TranslationText::class);
    }
}