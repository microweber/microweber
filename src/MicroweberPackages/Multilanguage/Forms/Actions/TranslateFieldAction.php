<?php

namespace MicroweberPackages\Multilanguage\Forms\Actions;

use Filament\Actions\Action;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Width as MaxWidth;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use SolutionForest\FilamentTranslateField\Facades\FilamentTranslateField;

class TranslateFieldAction
{
    public static function make(string $fieldName): Action
    {
        return Action::make('translate_' . $fieldName)
            ->icon('heroicon-o-language')
            ->tooltip('Translate this field')
            ->modalHeading('Translate: ' . ucfirst(str_replace('_', ' ', $fieldName)))
            ->visible(fn() => MultilanguageHelpers::multilanguageIsEnabled())
            ->form(function () use ($fieldName) {
                if (!MultilanguageHelpers::multilanguageIsEnabled()) {
                    return [];
                }

                $supportedLanguages = get_supported_languages();
                $defaultLocale = mw()->lang_helper->default_lang();

                if (empty($supportedLanguages)) {
                    return [];
                }

                $tabs = [];

                foreach ($supportedLanguages as $language) {
                    $locale = $language['locale'];
                    $label = $language['title'] ?? $locale;

                    $label = FilamentTranslateField::getLocaleLabel($locale, $locale) . ' [' . $label . ']';

                    // Skip default locale as it's handled by the main field
                    if ($locale === $defaultLocale) {
                        continue;
                    }

                    $field = self::createFieldComponent($fieldName, $locale);

                    $tabs[] = Tabs\Tab::make($locale)
                        ->label($label)
                        // ->icon($language['icon'] ?? null)
                        ->schema([$field]);
                }

                if (empty($tabs)) {
                    return [];
                }

                return [
                    Tabs::make('translation_tabs')
                        ->tabs($tabs)
                        ->columnSpanFull()
                ];
            });
    }

    protected static function createFieldComponent(string $fieldName, string $locale)
    {

        //   $translationFieldName = 'multilanguage_translations' . '.' . $locale . '.' . $fieldName;
        // $translationFieldName = 'multilanguage_translations' . '.' . $fieldName. '.' . $locale ;
        $translationFieldName = 'multilanguage' . '.' . $fieldName . '.' . $locale;
        //  $translationFieldName = $fieldName. '.' . $locale ;

        /*   // Determine field type based on field name patterns
           if (str_contains($fieldName, 'content')
               || str_contains($fieldName, 'body')) {
               return RichEditor::make($translationFieldName)
                   ->label(false)
                   ->placeholder('Enter translation...')
                   ->columnSpanFull();
           }

           if (str_contains($fieldName, 'description')
               || str_contains($fieldName, 'meta')) {
               return Textarea::make($translationFieldName)
                   ->label(false)
                   ->placeholder('Enter translation...')
                   ->rows(3)
                   ->columnSpanFull();
           }*/

        $input = TextInput::make($translationFieldName);
        if (str_contains($fieldName, 'body')) {
            $input = RichEditor::make($translationFieldName);
        } else if (str_contains($fieldName, 'description')) {
            $input = Textarea::make($translationFieldName);
        }else if (str_contains($fieldName, 'content')) {
            $input = Textarea::make($translationFieldName);
        }


        // Default to TextInput
        return $input
            ->label(false)
            //  ->live()
            ->placeholder('Enter translation...')
            ->formatStateUsing(function (Get $get, Set $set, $state, $livewire, $component) use ($translationFieldName, $locale, $fieldName) {


                //      dd($get('./', true));

                $translations = $get('../../data.multilanguage', false);

                if (empty($translations)) {
                    $translations = $get('mountedTableActionsData.0.multilanguage', true);
                }
                if (empty($translations)) {
                    $translations = $get('mountedActionsData.0.multilanguage', true);
                }


//                $parentComponent = $component->getContainer() ->  getParentComponent()->getLivewire() ;
// dd($parentComponent,get_class($parentComponent));
//
//                /*  $component->getContainer()->getParentComponent()
//                        ->getChildComponentContainer($uuid)
//                        ->getComponent('settings')
//                        ->getChildComponentContainer()
//                        ->validate();*/
//
//                $statePath = $parentComponent->getStatePath();
//
//                dd( $get($statePath.'.title', true),$statePath.'.title');

                //   dd( $statePath,$get('mountedTableActionsData.0.title', true));


//             //   dd($parentComponent);
//               $statePath = $parentComponent->getStatePath( );
//             $translations = $get($statePath, true);
//               dd($translations,$statePath);
//dd($component->getStatePath($translationFieldName, true));
//                // Get the state path for the translation field
//                // $statePath = $livewire->getStatePath($translationFieldName, true);
//                // dd($statePath);
//
//                // This is a workaround to get the state path
//
//$statePath = $livewire->getStatePath($translationFieldName, true);
//dd($statePath);
//
//                dd( $get('../../',true));


                if (isset($translations[$fieldName][$locale]) && $translations[$fieldName][$locale]) {
                    // If translation exists, return it
                    return $translations[$fieldName][$locale];
                }


            })
            ->afterStateUpdated(function (Get $get, Set $set, $state, $record,$component) use ($translationFieldName, $locale, $fieldName) {
                $hasData = $get('../../data', false);

                $translations = $get('../../data.multilanguage', false);

                if (empty($translations)) {
                    $translations = $get('mountedTableActionsData.0.multilanguage', true);
                }
                if (empty($translations)) {
                    $translations = $get('mountedActionsData.0.multilanguage', true);
                }

//dd($component,$get('mountedTableActionsData.0', true),$get('../../data.multilanguage', false));
                //  $translations = $get('../../data.multilanguage', false);

                $translations[$fieldName][$locale] = $state;



                if (!$hasData) {
                    $set('mountedTableActionsData.0.multilanguage', $translations, isAbsolute: true);
                    $set('mountedActionsData.0.multilanguage', $translations, isAbsolute: true);
                  //  $set('mountedFormComponentActionsData.0.multilanguage', $translations, isAbsolute: true);
                } else {
                    $set('../../data.multilanguage', $translations);
                }

            })
            ->columnSpanFull();
    }
}

