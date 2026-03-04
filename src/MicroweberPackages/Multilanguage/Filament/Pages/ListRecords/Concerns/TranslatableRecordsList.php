<?php

namespace MicroweberPackages\Multilanguage\Filament\Pages\ListRecords\Concerns;

use Filament\Resources\Concerns\HasActiveLocaleSwitcher;
// use Filament\SpatieLaravelTranslatablePlugin;
use MicroweberPackages\Multilanguage\Filament\Resources\Concerns\TranslatableHasActiveLocaleSwitcher;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

trait TranslatableRecordsList
{
    use TranslatableHasActiveLocaleSwitcher;

    public function mountTranslatable(): void
    {

        $this->activeLocale = static::getResource()::getDefaultTranslatableLocale();
    }

    public function getTranslatableLocales(): array
    {


        $isMultilanguageActive = MultilanguageHelpers::multilanguageIsEnabled();
        if ($isMultilanguageActive) {
            return static::getResource()::getTranslatableLocales();
        } else {
            return [0 => 'en_US'];
        }

    }

    public function getActiveTableLocale(): ?string
    {

        return $this->activeLocale;
    }
}
