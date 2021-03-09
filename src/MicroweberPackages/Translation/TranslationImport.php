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

        // Get All input Translation Keys
        $inputTranslationMap = [];
        foreach($inputTranslations as $inputTranslation) {
            $inputTranslationMap[$this->_hashFields($inputTranslation)] = $inputTranslation;
        }

        // Get All Database Translation Keys
        $dbTranslationKeysMap = $this->_getTranslationKeysMap();

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

        // Add translation ids to translation texts
        $translationTextsMapWithId = [];
        foreach($inputTranslations as &$inputTranslation) {
            $inputTranslationMd5 = $this->_hashFields($inputTranslation);
            $inputTranslation['translation_key_id'] = $dbTranslationKeysMap[$inputTranslationMd5];
            $translationTextsMapWithId[$this->_hashFields($inputTranslation, ['translation_key','translation_group', 'translation_namespace','translation_key_id'])] = true;
        }

        dd($translationTextsMapWithId);

        $updatedTexts = [];
        $foundedTranslationTexts = [];
        $missingTranslationTexts = [];
        $dbTranslationTexts = TranslationText::join('translation_keys', 'translation_texts.translation_key_id', '=', 'translation_keys.id')->get();
        if ($dbTranslationTexts != null) {
            foreach($dbTranslationTexts as $dbTranslationText) {
                $dbTranslationTextMd5 = $this->_hashFields($dbTranslationText, ['translation_key','translation_group', 'translation_namespace','translation_key_id']);
                if (isset($translationTextsMapWithId[$dbTranslationTextMd5])) {
                    $foundedTranslationTexts[] = $dbTranslationText;
                }
            }
        }

        $insertedTexts = [];
        if (!empty($missingTranslationTexts)) {
            try {
                $insertedTexts = $this->_importTranslationTexts($missingTranslationTexts);
            } catch (Exception $e) {
                return ['error' => 'Error when trying to import translation texts.'];
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

    private function _hashFields($array, $fields = ['translation_key','translation_group', 'translation_namespace'])
    {
        $hashString = '';

        foreach($fields as $field) {
            if (isset($array[$field])) {
                $hashString .= $array[$field];
            } else {
                throw new Exception('The field missing in array.');
            }
        }

        return md5($hashString);
    }

    private function _getTranslationKeysMap()
    {
        $dbTranslationKeysMap = [];
        $getTranslationKeys = TranslationKey::select(['id', 'translation_key','translation_group','translation_namespace'])->get();
        if ($getTranslationKeys != null) {
            foreach($getTranslationKeys as $translationKey) {
                $dbTranslationKeysMap[$this->_hashFields($translationKey)] = $translationKey->id;
            }
        }
        return $dbTranslationKeysMap;
    }

    private function _importTranslationTexts($translationTexts)
    {
        $insertedTranslationTexts = [];

        $translationTextsChunks = array_chunk($translationTexts, 15);

        foreach ($translationTexts as $translationTextsChunk) {
            $insertedTranslationTexts[] = TranslationText::insert($translationTextsChunk);
        }

        return $insertedTranslationTexts;
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