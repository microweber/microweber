<?php

namespace Modules\Multilanguage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sushi\Sushi;

class Language extends Model
{
    use Sushi;
    use HasFactory;

    protected $fillable = [
        'locale',
        'language',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function getRows()
    {
        return [];
    }

    public static function getDefaultLanguage()
    {
        return self::where('is_default', true)->first();
    }

    public static function getActiveLanguages()
    {
        return self::where('is_active', true)->get();
    }
}
