<?php

namespace MicroweberPackages\Translation\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationText extends Model
{
    public $table = 'translation_texts';
    protected $fillable = [
        'id',
        'translation_key_id',
        'translation_text',
        'translation_locale',
    ];

    public function translationKey()
    {
        return $this->belongsTo(TranslationKey::class, 'translation_key_id');
    }
}