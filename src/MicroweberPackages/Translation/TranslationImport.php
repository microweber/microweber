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
        $dbTranslationKeysMap = [];
        $getTranslationKeys = TranslationKey::select(['id', 'translation_key','translation_group','translation_namespace'])->get();
        if ($getTranslationKeys != null) {
            foreach($getTranslationKeys as $translationKey) {
                $dbTranslationKeysMap[md5($translationKey->translation_key.$translationKey->translation_group.$translationKey->translation_namespace)] = $translationKey;
            }
        }

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

        $insertedKeys = $this->_importTranslationKeys($missingTranslationKeys);

        /*
        try {
            $insertedKeys = $this->_importTranslationKeys($missingTranslationKeys);
        } catch (Exception $e) {
            return ['error' => 'Error when trying to import translation keys.'];
        }*/


        //dd($missingTranslationKeys);

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

    private function _importTranslationKeys($translationKeys)
    {
        $insertedTranslationKeys = [];

        $translationKeysChunks = array_chunk($translationKeys, 15);

        foreach ($translationKeys as $translationKeysChunk) {
            $insertedTranslationKeys[] = TranslationKey::insert($translationKeysChunk);
        }

        return $insertedTranslationKeys;
    }

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }


}