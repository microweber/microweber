<?php

namespace MicroweberPackages\Multilanguage\Models\Traits;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;
use MicroweberPackages\Option\Models\ModuleOption;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

trait HasMultilanguageTrait
{
    public $_enableMultilanguageRetrieving = true;
    public $isMultiLanguage = true;
    private array $_addMultilanguage = [];

    public function withoutMultilanguageRetrieving()
    {

        $this->_enableMultilanguageRetrieving = false;
        return $this;
    }

    public function isEnabledMultilanguageRetriving()
    {
        return $this->_enableMultilanguageRetrieving;
    }

    /** @var array $fillable */

    public function initializeHasMultilanguageTrait()
    {
        $this->fillable[] = 'multilanguage';
    }

    private static $__getDefaultLocale = false;

    protected function __getDefaultLocale()
    {
        if (self::$__getDefaultLocale) {
            return self::$__getDefaultLocale;
        }

        self::$__getDefaultLocale = mw()->lang_helper->default_lang();

        return self::$__getDefaultLocale;
    }

    private static $__getLocale = false;

    protected function __getLocale()
    {
        if (self::$__getLocale) {
            return self::$__getLocale;
        }
        self::$__getLocale = mw()->lang_helper->current_lang();
        return self::$__getLocale;
    }

    public static function bootHasMultilanguageTrait()
    {

        static::saving(function ($model) {

            if (!MultilanguageHelpers::multilanguageIsEnabled()) {
                if (isset($model->attributes['lang'])) {
                    unset($model->attributes['lang']);
                }
                if (isset($model->attributes['multilanguage'])) {
                    unset($model->attributes['multilanguage']);
                }
            }
            if (MultilanguageHelpers::multilanguageIsEnabled()) {

                $defaultLocale = mw()->lang_helper->default_lang();
                $modelClass = get_class($model);
                $skipModelClasses = [
                    ModuleOption::class,
                ];
                $isModuleOptions = false;
                if (in_array($modelClass, $skipModelClasses)) {
                    $isModuleOptions = true;

                }




                if ($isModuleOptions and !isset($model->attributes['multilanguage']) and isset($model->attributes['lang'])) {
                // When receive a save_option
                    // legacy save_option will not have multilanguage attribute, it will have lang attribute instead
                    if (isset($model->attributes['lang']) && isset($model->attributes['module']) and $model->attributes['lang'] != $defaultLocale) {
                        $translatableModuleOptions = self::getTranslatableModuleOptions();
                        if (isset($translatableModuleOptions[$model->attributes['module']])) {
                            $translatableModuleOptionKeys = $translatableModuleOptions[$model->attributes['module']];
                            if (in_array($model->attributes['option_key'], $translatableModuleOptionKeys)) {
                                $model->_addMultilanguage['option_value'][$model->attributes['lang']] = $model->attributes['option_value'];
                                unset($model->attributes['option_value']);
                            }
                        }

                    }


                    if (isset($model->attributes['lang'])) {
                        unset($model->attributes['lang']);
                    }
                    if (isset($model->attributes['multilanguage'])) {
                        unset($model->attributes['multilanguage']);
                    }

                }

                if (isset($model->attributes['lang'])) {
                    unset($model->attributes['lang']);
                }
                if (isset($model->attributes['multilanguage_translatons'])) {
                    unset($model->attributes['multilanguage_translatons']);
                }


                 /**
                 * When you add multilanguage fields
                 *
                 * EXAMPLE:
                 * multilanguage[title][en_US]    "Apple+iTunes+KSA+SAR+50"
                 * multilanguage[title][ar]    "Apple Ar"
                 */
                if (isset($model->attributes['multilanguage']) and empty($model->attributes['multilanguage'])) {
                    unset($model->attributes['multilanguage']);
                }
                if (isset($model->attributes['multilanguage']) and !empty($model->attributes['multilanguage'])) {

                    $model->_addMultilanguage = $model->attributes['multilanguage'];
                    unset($model->attributes['multilanguage']);

                }

                // Backup the original model fields
                if (isset($model->translatable)) {
                    foreach ($model->translatable as $translatableField) {
                        if (!isset($model->attributes[$translatableField])) {
                            $orig = $model->getOriginal($translatableField);
                            if ($orig) {
                                $model->$translatableField = $orig;
                            }
                        }
                    }
                    // Append to model if we want to save changes for original model
                    if (!empty($model->_addMultilanguage)) {

                        foreach ($model->_addMultilanguage as $field => $multilanguage) {
                            if (isset($multilanguage[$defaultLocale])) {
                                $model->$field = $multilanguage[$defaultLocale];
                            }
                        }
                    }
                }
            }
        });

        static::retrieved(function ($model) {


            if ($model->isEnabledMultilanguageRetriving()) {
                if (MultilanguageHelpers::multilanguageIsEnabled()) {
                    $mlobs = new MultilanguageObserver();
                    $mlobs->retrieved($model);
                }
            }
        });

        static::saved(function ($model) {
            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                $model->_saveMultilanguageTranslation();
            }
        });

        static::deleted(function ($model) {

            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                $mlobs = new MultilanguageObserver();
                $mlobs->deleted($model);
            }
        });
    }

    public function translations()
    {
        return $this->hasMany(MultilanguageTranslations::class, 'rel_id')
            ->where('rel_type', $this->getMorphClass());
    }

    public function getTranslationsFormated()
    {
        $formated = [];

        $locale = mw()->lang_helper->default_lang();
        if (!empty($this->translatable)) {
            foreach ($this->translatable as $fieldName) {
                $formated[$locale][$fieldName] = $this->getOriginal($fieldName);
            }
        }

        if (!empty($this->translations)) {
            foreach ($this->translations as $translation) {
                $formated[$translation->locale][$translation->field_name] = $translation->field_value;
            }
        }

        return $formated;
    }

    public function _saveMultilanguageTranslation()
    {
        if (empty($this->_addMultilanguage)) {
            return;
        }

        $model = $this;
        $defaultLocale = $this->__getDefaultLocale();
        $localeFieldNames = array_keys($this->_addMultilanguage);

        if (!empty($localeFieldNames)) {
            // clean up saved values for default locale, as they are retreived from the model
            MultilanguageTranslations::whereIn('field_name', $localeFieldNames)
                ->where('rel_type', $model->getTable())
                ->where('rel_id', $model->id)
                ->where('locale', $defaultLocale)
                ->delete();
        }

        DB::transaction(function () use ($model, $defaultLocale) {
            foreach ($this->_addMultilanguage as $fieldName => $field) {
                foreach ($field as $fieldLocale => $fieldValue) {

                    if ($fieldLocale == $defaultLocale) {
                        // skip saving default locale , its saved in the model
                        continue;
                    }

                    $findTranslate = MultilanguageTranslations::where('field_name', $fieldName)
                        ->where('rel_type', $model->getTable())
                        ->where('rel_id', $model->id)
                        ->where('locale', $fieldLocale)
                        ->first();

                    if ($fieldValue == null and $findTranslate != null) {
                        $findTranslate->delete();
                        $model->refresh();
                        continue;
                    }

                    if ($fieldValue == null) {
                        continue;
                    }
                    if ($findTranslate == null) {
                        $findTranslate = new MultilanguageTranslations();
                        $findTranslate->field_name = $fieldName;
                        $findTranslate->rel_type = $model->getTable();
                        $findTranslate->rel_id = $model->id;
                        $findTranslate->locale = $fieldLocale;
                    }

                    $findTranslate->field_value = $fieldValue;
                    $findTranslate->save();

                    $model->refresh();

                }
            }
        });
    }

    public static function getTranslatableModuleOptions()
    {

        return MultilanguageHelpers::getTranslatableModuleOptions();
    }


    // SPATIE
    public $translationLocale = null;
    public function setLocale(string $locale): self
    {
        $this->translationLocale = $locale;

        return $this;
    }

    public function isTranslatableAttribute(string $key): bool
    {
        return in_array($key, $this->getTranslatableAttributes());
    }

    public function getAttributeValue($key): mixed
    {
        if (! MultilanguageHelpers::multilanguageIsEnabled()) {
            return parent::getAttributeValue($key);
        }
        if (! $this->isTranslatableAttribute($key)) {
            return parent::getAttributeValue($key);
        }

        return $this->getTranslation($key, $this->getLocale(), $this->useFallbackLocale());
    }

    public function useFallbackLocale(): bool
    {
        if (property_exists($this, 'useFallbackLocale')) {
            return $this->useFallbackLocale;
        }

        return true;
    }

    public function getLocale(): string
    {
        return $this->translationLocale ?: config('app.locale');
    }


    public function getTranslatableAttributes(): array
    {
        return is_array($this->translatable)
            ? $this->translatable
            : [];
    }

    public function getTranslation(string $key, string $locale, bool $useFallbackLocale = true): mixed
    {
      //  $translation = $this->getOriginal($key);
        $translation = parent::getAttributeValue($key);
        //$translation = $this->getOriginal($key);
//@todo possible bug on multilanguage with $this->getOriginal($key);, chaged to parent::getAttributeValue($key);



        $getTranslation = MultilanguageTranslations::where('rel_id', $this->id)
            ->where('rel_type', $this->getTable())
            ->where('field_name', $key)
            ->where('locale', $locale)
            ->first();

        if ($getTranslation && $getTranslation->field_value) {
            $translation = $getTranslation->field_value;
        }

        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $translation);
        }

        if($this->hasAttributeMutator($key)) {
            return $this->mutateAttributeMarkedAttribute($key, $translation);
        }

        return $translation;
    }

    public function getTranslations(string $key = null, array $allowedLocales = null): array
    {
        if ($key == null) {
            return [
                'title' => [
                    'en' => 'testValue_en',
                    'de' => 'testValue_de',
                    'bg' => 'testValue_bg',
                ],
            ];
        }
        return [
            'en' => 'testValue_en',
            'bg' => 'testValue_bg',
            'fr' => 'testValue_fr',
        ];
    }

    public function setTranslation(string $key, string $locale, $value): self
    {

        $this->attributes['multilanguage'][$key][$locale] = $value;

        return $this;
    }

    /**
     * @throws AttributeIsNotTranslatable
     */
    public function setTranslations(string $key, array $translations): self
    {
        $this->guardAgainstNonTranslatableAttribute($key);

        if (! empty($translations)) {
            foreach ($translations as $locale => $translation) {
                $this->setTranslation($key, $locale, $translation);
            }
        } else {
         //   $this->attributes[$key] = $this->asJson([]);
        }

        return $this;
    }

    /**
     * @throws AttributeIsNotTranslatable
     */
    protected function guardAgainstNonTranslatableAttribute(string $key): void
    {
        if (! $this->isTranslatableAttribute($key)) {
            throw AttributeIsNotTranslatable::make($key, $this);
        }
    }

}
