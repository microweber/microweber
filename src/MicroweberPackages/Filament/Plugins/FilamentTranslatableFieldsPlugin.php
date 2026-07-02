<?php

namespace MicroweberPackages\Filament\Plugins;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Panel;
use Filament\Actions\Action;
use MicroweberPackages\Multilanguage\FormElements\Text;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class FilamentTranslatableFieldsPlugin implements Plugin
{
    protected array|Closure $supportedLanguages = [];

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'outerweb-filament-translatable-fields';
    }

    public function supportedLanguages(array|Closure $supportedLanguages): static
    {
        $this->supportedLanguages = $supportedLanguages;

        return $this;
    }

    public function getSupportedLanguages(): array
    {
        $locales = is_callable($this->supportedLanguages) ? call_user_func($this->supportedLanguages) : $this->supportedLanguages;

        return $locales;
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        $supportedLanguages = $this->getSupportedLanguages();
//        $supportedLocales = ['en_US', 'bg_BG'];
//        Field::macro('translatable', function (bool $translatable = true, ?array $customLocales = null, ?array $localeSpecificRules = null) use ($supportedLocales) {
//            if (! $translatable) {
//                return $this;
//            }
//
//            /**
//             * @var Field $field
//             * @var Field $this
//             */
//            $field = $this->getClone();
//
//            $tabs = collect($customLocales ?? $supportedLocales)
//                ->map(function ($label, $key) use ($field, $localeSpecificRules) {
//                    $locale = is_string($key) ? $key : $label;
//
//                    $clone = $field
//                        ->getClone()
//                        ->name("{$field->getName()}.{$locale}")
//                        ->label($field->getLabel())
//                        ->statePath("{$field->getStatePath(false)}.{$locale}");
//
//                    if ($localeSpecificRules && isset($localeSpecificRules[$locale])) {
//                        $clone->rules($localeSpecificRules[$locale]);
//                    }
//
//                    return Tabs\Tab::make($locale)
//                        ->label(is_string($key) ? $label : strtoupper($locale))
//                        ->schema([$clone]);
//                })
//                ->toArray();
//
//            $tabsField = Tabs::make('translations')
//                ->tabs($tabs);
//
//            return $tabsField;
//        });


//        Modal::macro('teleport', function ($teleportTo) {
//            if ($teleportTo == 'body') {
//                $this->view = 'filament-actions::components.actions.teleport-to-body';
//            }
//            return $this;
//        });



    }
}
