<?php

namespace MicroweberPackages\Translation;


use Illuminate\Support\Facades\DB;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;

class TranslationImport
{
    public $replaceValues = false;
    public $logger = null;

    public function replaceValues($replace) {
        $this->replaceValues = $replace;
    }

    public function import($inputTranslations)
    {
        $validateImport = false;

        if (!empty($inputTranslations) && is_array($inputTranslations)) {
            $validateImport = true;
        }

        // Clear input translation
        $inputTranslations = $this->_clearInputTranslation($inputTranslations);

        if (empty($inputTranslations[0]['translation_key'])
            || empty($inputTranslations[0]['translation_namespace'])
            || empty($inputTranslations[0]['translation_group'])
            || empty($inputTranslations[0]['translation_locale'])
            || empty($inputTranslations[0]['translation_text'])
        ) {
            $validateImport = false;
        }

        if (!$validateImport) {
            return ['error' => 'Can\'t import this language file.'];
        }

        $insertedTexts = [];
        $updatedTexts = [];

        // Get All Database Translation Keys
        $dbTranslationKeysMap = $this->_getTranslationKeysMap();

        // Get All input Translation Keys
        $inputTranslationMap = [];
        foreach($inputTranslations as $inputTranslation) {
            $inputTranslationMap[md5($inputTranslation['translation_key'].$inputTranslation['translation_group'].$inputTranslation['translation_namespace'])] = $inputTranslation;
        }

        // Insert missing keys in database
        $missingTranslationKeys = [];
        foreach ($inputTranslationMap as $md5InputTranslationKey=>$inputTranslation) {
            if (!isset($dbTranslationKeysMap[$md5InputTranslationKey])) {
                $missingTranslationKeys[] = [
                    'translation_group'=>$inputTranslation['translation_group'],
                    'translation_namespace'=>$inputTranslation['translation_namespace'],
                    'translation_key'=>$inputTranslation['translation_key'],
                ];
            }
        }

        try {
            $insertedKeys = $this->_importTranslationKeys($missingTranslationKeys);
        } catch (Exception $e) {
            return ['error' => 'Error when trying to import translation keys.'];
        }

        // Get New Database Translation Keys Map if we are inserted new
        if (count($insertedKeys) > 0) {
            $dbTranslationKeysMap = $this->_getTranslationKeysMap();
        }

        $dbTranslationTextsMap = [];
        $getTranslationTexts = TranslationText::select(['id', 'translation_locale','translation_key_id'])->get();
        if ($getTranslationTexts != null) {
            foreach($getTranslationTexts as $translationText) {
                //
            }
        }


        \Cache::tags('translation_keys')->flush();
        \Cache::tags('translation_texts')->flush();

        $responseText = 'Importing language file success.';
        if ($insertedKeys) {
            $responseText .= ' Inserted keys ' . count($insertedKeys);
        }
        if ($insertedTexts) {
            $responseText .= ' Inserted texts ' . count($insertedTexts);
        }
        if ($updatedTexts) {
            $responseText .= ' Replaced texts ' . count($updatedTexts);
        }

        return ['success' => $responseText];

    }

    private function _getTranslationKeysMap()
    {
        $dbTranslationKeysMap = [];
        $getTranslationKeys = TranslationKey::select(['id', 'translation_key','translation_group','translation_namespace'])->get();
        if ($getTranslationKeys != null) {
            foreach($getTranslationKeys as $translationKey) {
                $dbTranslationKeysMap[md5($translationKey->translation_key.$translationKey->translation_group.$translationKey->translation_namespace)] = $translationKey->id;
            }
        }
        return $dbTranslationKeysMap;
    }

    private function _importTranslationKeys($translationKeys)
    {
        $insertedTranslationKeys = [];

        $translationKeysChunks = array_chunk($translationKeys, 15);

        foreach ($translationKeysChunks as $translationKeysChunk) {
            $insertedTranslationKeys[] = TranslationKey::insert($translationKeysChunk);
        }

        return $insertedTranslationKeys;
    }

    private function _clearInputTranslation($translations) {

        foreach($translations as &$translation) {
            $translation['translation_namespace'] = trim($translation['translation_namespace']);
            $translation['translation_group'] = trim($translation['translation_group']);
            $translation['translation_key'] = trim($translation['translation_key']);
        }

        return $translations;
    }

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }


}