<?php

namespace MicroweberPackages\Multilanguage\Filament\Resources\Concerns;

use Exception;
use Filament\Resources\Concerns\Translatable;
use Spatie\Translatable\HasTranslations;

trait TranslatableResource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        $filament = app('filament');
        if ($filament->hasPlugin('spatie-laravel-translatable')) {
            return filament('spatie-laravel-translatable')->getDefaultLocales();
        }

        return [0 => 'en'];
    }
}
