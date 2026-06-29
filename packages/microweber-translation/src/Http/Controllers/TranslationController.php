<?php

namespace MicroweberPackages\Translation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use MicroweberPackages\Translation\TranslationImport;
use MicroweberPackages\Translation\TranslationPackageInstallHelper;

class TranslationController
{
    public function index(Request $request)
    {
        $namespace = $request->get('namespace', '*');
        $search = $request->get('search');
        $page = $request->get('page', 1);

        $filter = [
            'translation_namespace' => $namespace,
            'page' => $page,
        ];

        if ($search) {
            $filter['search'] = $search;
        }

        if ($request->has('all')) {
            $filter['all'] = true;
        }

        return TranslationKey::getGroupedTranslations($filter);
    }

    public function save(Request $request)
    {
        $translations = $request->input('translations');

        if (is_string($translations)) {
            $translations = base64_decode($translations);
            $translations = json_decode($translations, true);
        }

        if (!is_array($translations)) {
            return ['error' => 'Invalid translations data'];
        }

        $saveTranslations = [];

        $translationItems = $translations['translations'] ?? $translations;
        foreach ($translationItems as $translationLocales) {
            foreach ($translationLocales as $translationLocale => $translation) {
                if (is_array($translation)) {
                    $translation['translation_locale'] = $translationLocale;
                    $saveTranslations[md5($translation['translation_key'] . $translation['translation_locale'] . $translation['translation_group'] . $translation['translation_namespace'])] = $translation;
                }
            }
        }

        Config::set('microweber.disable_model_cache', true);

        if (!empty($saveTranslations)) {
            foreach ($saveTranslations as $translation) {
                $getTranslationKey = TranslationKey::query()
                    ->where('translation_key', $translation['translation_key'])
                    ->where('translation_group', $translation['translation_group'])
                    ->where('translation_namespace', $translation['translation_namespace'])
                    ->first();

                if ($getTranslationKey == null) {
                    $getTranslationKey = new TranslationKey();
                    $getTranslationKey->translation_key = $translation['translation_key'];
                    $getTranslationKey->translation_namespace = $translation['translation_namespace'];
                    $getTranslationKey->translation_group = $translation['translation_group'];
                }
                $getTranslationKey->save();

                $getTranslationText = TranslationText::where('translation_key_id', $getTranslationKey->id)
                    ->where('translation_locale', $translation['translation_locale'])
                    ->get();

                if ($getTranslationText->count() > 1) {
                    foreach ($getTranslationText as $duplicatedText) {
                        $duplicatedText->delete();
                    }
                }

                $getTranslationText = TranslationText::where('translation_key_id', $getTranslationKey->id)
                    ->where('translation_locale', $translation['translation_locale'])
                    ->first();

                if ($getTranslationText == null) {
                    $getTranslationText = new TranslationText();
                    $getTranslationText->translation_key_id = $getTranslationKey->id;
                    $getTranslationText->translation_locale = $translation['translation_locale'];
                }

                $getTranslationText->translation_text = trim($translation['translation_text']);
                $getTranslationText->save();
            }
        }

        try {
            \Cache::tags('translation_keys')->flush();
        } catch (\Exception $e) {
            // Cache tags might not be supported
        }

        return ['success' => true];
    }

    public function export(Request $request)
    {
        $namespace = $request->get('namespace', '*');
        $locale = $request->get('locale', 'en_US');
        $format = $request->get('format', 'json');

        $getTranslations = [];
        $getTranslationsQuery = TranslationKey::join('translation_texts', 'translation_keys.id', '=', 'translation_texts.translation_key_id')
            ->where('translation_texts.translation_locale', $locale)
            ->where('translation_namespace', $namespace)
            ->get();

        if ($getTranslationsQuery !== null) {
            $getTranslations = $getTranslationsQuery->toArray();
        }

        $getTranslationsWithoutTexts = TranslationKey::whereNotIn('translation_keys.id', function ($query) use ($locale) {
            $query->select('translation_texts.translation_key_id')
                ->from('translation_texts')
                ->where('translation_texts.translation_locale', $locale);
        })->get();

        if ($getTranslationsWithoutTexts !== null) {
            $getTranslations = array_merge($getTranslations, $getTranslationsWithoutTexts->toArray());
        }

        if (empty($getTranslations)) {
            return response()->json([]);
        }

        $readyTranslations = [];

        foreach ($getTranslations as $translation) {
            if (!isset($translation['translation_text'])) {
                $translation['translation_text'] = '';
                $translation['translation_locale'] = '';
            }

            $readyTranslations[] = [
                'translation_group' => $translation['translation_group'],
                'translation_namespace' => $translation['translation_namespace'],
                'translation_key' => $translation['translation_key'],
                'translation_text' => $translation['translation_text'],
                'translation_locale' => $translation['translation_locale'],
            ];
        }

        if ($format === 'json') {
            return response()->json($readyTranslations);
        }

        return response()->json($readyTranslations);
    }

    public function importFromJson(Request $request)
    {
        $translations = $request->input('translations');

        if (!is_array($translations)) {
            return ['error' => 'Invalid translations format'];
        }

        $import = new TranslationImport();
        $replace_values = intval($request->input('replace_values', 0));
        $import->replaceTexts($replace_values);

        return $import->import($translations);
    }

    public function availableLanguages()
    {
        return TranslationPackageInstallHelper::getAvailableTranslations();
    }

    public function installLanguage(Request $request)
    {
        $locale = $request->input('locale');

        if (empty($locale)) {
            return ['error' => 'Locale is required'];
        }

        $result = TranslationPackageInstallHelper::installLanguage($locale);

        return $result ?? ['error' => 'Language file not found'];
    }
}