<?php

namespace MicroweberPackages\Filament\Plugins;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Panel;
use Filament\Tables\Actions\Action;
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

//        Modal::macro('teleport', function ($teleportTo) {
//            if ($teleportTo == 'body') {
//                $this->view = 'filament-actions::components.actions.teleport-to-body';
//            }
//            return $this;
//        });

        Field::macro('mwTranslatableOption', function () use ($supportedLanguages) {

            if (empty($supportedLanguages)) {
              return $this;
            }
            if (!MultilanguageHelpers::multilanguageIsEnabled()) {
               return $this;
            }

            $fieldName = $this->getName();
            $fieldName = str_replace('options.', 'translatableOptions.', $fieldName);

            if (class_basename($this) == 'TextInput') {

                $textInput = TextInput::make($fieldName)
                    ->live()
                    ->helperText($this->getHelperText())
                    ->placeholder($this->getPlaceholder())
                    ->debounce(800)
                    ->view('filament-forms::components.text-input-option-translatable', [
                        'supportedLanguages' => $supportedLanguages,
                    ]);

                return $textInput;
            } else if (class_basename($this) == 'Textarea') {

                $textarea = Textarea::make($fieldName)
                    ->live()
                    ->helperText($this->getHelperText())
                    ->placeholder($this->getPlaceholder())
                    ->debounce(800)
                    ->view('filament-forms::components.textarea-option-translatable', [
                        'supportedLanguages' => $supportedLanguages
                    ]);

                return $textarea;
            }

            throw new \Exception('Unsupported field type: ' . class_basename($this));

            return $this;
        });

    }
}
