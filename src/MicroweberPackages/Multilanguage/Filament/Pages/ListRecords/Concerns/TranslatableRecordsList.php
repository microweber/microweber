<?php

namespace MicroweberPackages\Multilanguage\Filament\Pages\ListRecords\Concerns;

use Filament\Resources\Concerns\HasActiveLocaleSwitcher;
use Filament\SpatieLaravelTranslatablePlugin;
use MicroweberPackages\Multilanguage\Filament\Resources\Concerns\TranslatableHasActiveLocaleSwitcher;

trait TranslatableRecordsList
{
    use TranslatableHasActiveLocaleSwitcher;

    public function mountTranslatable(): void
    {

        $this->activeLocale = static::getResource()::getDefaultTranslatableLocale();
    }

    public function getTranslatableLocales(): array
    {

        return static::getResource()::getTranslatableLocales();
    }

    public function getActiveTableLocale(): ?string
    {

        return $this->activeLocale;
    }
}
