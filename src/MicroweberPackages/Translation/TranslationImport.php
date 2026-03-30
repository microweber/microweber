<?php

namespace MicroweberPackages\Translation;

use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;

class TranslationImport
{
    public $replaceTexts = false;
    public $logger = null;

    public function replaceTexts($replace)
    {
        $this->replaceTexts = $replace;
    }

    public function import($inputTranslations)
    {
        $validateImport = false;

        if (!empty($inputTranslations) && is_array($inputTranslations)) {
            $validateImport = true;
        }

        // Check if required structure exists (but allow empty translation_text and translation_locale)
        if (empty($inputTranslations) || !is_array($inputTranslations) || empty($inputTranslations[0])) {
            $validateImport = false;
        } else {
            $firstItem = $inputTranslations[0];

            // Check required fields exist (translation_text and translation_locale can be empty)
            if (!isset($firstItem['translation_key']) ||
                !isset($firstItem['translation_namespace']) ||
                !isset($firstItem['translation_group']) ||
                !isset($firstItem['translation_locale']) ||
                !isset($firstItem['translation_text']) ||
                empty($firstItem['translation_key']) ||
                empty($firstItem['translation_namespace']) ||
                empty($firstItem['translation_group'])) {
                $validateImport = false;
            }
        }

        if (!$validateImport) {
            return ['error' => 'Can\'t import this language file.'];
        }

        // Clear input translation
        $inputTranslations = $this->_prepareInputTranslation($inputTranslations);

        // Get All input Translation Keys
        $inputTranslationMap = [];
        foreach ($inputTranslations as $inputTranslation) {
            $inputTranslationMap[$this->_hashFields($inputTranslation)] = $inputTranslation;
        }

        // Get All Database Translation Keys
        $dbTranslationKeysMap = $this->_getTranslationKeysMap();

        // Insert missing keys in database
        $missingTranslationKeys = [];
        foreach ($inputTranslationMap as $md5InputTranslationKey => $inputTranslation) {
            if (!isset($dbTranslationKeysMap[$md5InputTranslationKey])) {
                $missingTranslationKeys[] = [
                    'translation_group' => $inputTranslation['translation_group'],
                    'translation_namespace' => $inputTranslation['translation_namespace'],
                    'translation_key' => $inputTranslation['translation_key'],
                ];
            }
        }

        try {
            $insertedKeys = $this->_importTranslationKeys($missingTranslationKeys);

        } catch (\Exception $e) {
            return ['error' => 'Error when trying to import translation keys.'];
        }

        // Get New Database Translation Keys Map if we are inserted new
        if (count($insertedKeys) > 0) {
            $dbTranslationKeysMap = $this->_getTranslationKeysMap();
        }

        $dbTranslationMap = [];
        $dbTranslationTexts = TranslationText::select(['translation_texts.*', 'translation_texts.id AS translation_text_id'])
            ->join('translation_keys', 'translation_texts.translation_key_id', '=', 'translation_keys.id')->get();
        if ($dbTranslationTexts != null) {
            foreach ($dbTranslationTexts as $dbTranslationText) {
                $dbTranslationTextMd5 = $this->_hashFields($dbTranslationText, ['translation_locale', 'translation_key_id']);
                $dbTranslationMap[$dbTranslationTextMd5] = $dbTranslationText;
            }
        }

        $foundedTranslationTexts = [];
        $missingTranslationTexts = [];
        foreach ($inputTranslations as $inputTranslation) {

            $inputTranslationMd5 = $this->_hashFields($inputTranslation);
            if (isset($dbTranslationKeysMap[$inputTranslationMd5])) {
                $inputTranslation['translation_key_id'] = $dbTranslationKeysMap[$inputTranslationMd5];
            } else {
                $inputTranslation['translation_key_id'] = $this->_getTranslationKeyId($inputTranslation);

            }


            $inputTranslationTextMd5 = $this->_hashFields($inputTranslation, ['translation_locale', 'translation_key_id']);

            if (isset($dbTranslationMap[$inputTranslationTextMd5])) {
                $inputTranslation['translation_text_id'] = $dbTranslationMap[$inputTranslationTextMd5]->translation_text_id;
                $foundedTranslationTexts[] = [
                    'translation_key_id' => $inputTranslation['translation_key_id'],
                    'translation_text_id' => $inputTranslation['translation_text_id'],
                    'translation_text' => $inputTranslation['translation_text'],
                    'translation_locale' => $inputTranslation['translation_locale'],
                ];;
            } else {
                $missingTranslationTexts[] = [
                    'translation_key_id' => $inputTranslation['translation_key_id'],
                    'translation_text' => $inputTranslation['translation_text'],
                    'translation_locale' => $inputTranslation['translation_locale'],
                ];
            }
        }

        $insertedTexts = [];
        if (!empty($missingTranslationTexts)) {
            try {
                $insertedTexts = $this->_importTranslationTexts($missingTranslationTexts);
            } catch (\Exception $e) {
                return ['error' => 'Error when trying to import translation texts.'];
            }
        }

        $updatedTexts = [];

        // Overwrite existing texts
        if ($this->replaceTexts) {
            try {
                $updatedTexts = $this->_updateTranslationTexts($foundedTranslationTexts);
            } catch (\Exception $e) {
                return ['error' => 'Error when trying to replace translation texts.'];
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

    private function _hashFields($array, $fields = ['translation_key', 'translation_group', 'translation_namespace'])
    {
        $hashString = '';
        $arrayData = is_array($array) ? $array : $array->toArray();

        foreach ($fields as $field) {
            if (!array_key_exists($field, $arrayData)) {
                throw new \Exception('The field missing in array.');
            }
            $hashString .= (string)($arrayData[$field] ?? '');
        }

        return $hashString;
    }

    private function _getTranslationKeyId($translation)

    {
        $allKeys = [];
        $allKeysGet = TranslationKey::all();
        if ($allKeysGet) {
            $allKeys = $allKeysGet->toArray();
        }


        if ($allKeys) {
            foreach ($allKeys as $allKey) {
                if ($allKey['translation_key'] == $translation['translation_key']) {
                    return $allKey['id'];
                }
            }
        }
        $insert = [];
        $insert['translation_key'] = $translation['translation_key'];
        $insert['translation_namespace'] = $translation['translation_namespace'];
        $insert['translation_group'] = $translation['translation_group'];
        return TranslationKey::insertGetId($insert);

    }
    private function _getTranslationKeysMap()
    {
        $dbTranslationKeysMap = [];
        $getTranslationKeys = TranslationKey::select(['id', 'translation_key', 'translation_group', 'translation_namespace'])->get();
        if ($getTranslationKeys != null) {
            foreach ($getTranslationKeys as $translationKey) {
                $dbTranslationKeysMap[$this->_hashFields($translationKey)] = $translationKey->id;
            }
        }
        return $dbTranslationKeysMap;
    }

    private function _updateTranslationTexts($translationTexts)
    {
        $updatedTranslationTexts = [];

        foreach ($translationTexts as $translationText) {

            $findTranslationText = TranslationText::where('id', $translationText['translation_text_id'])->first();
            if ($findTranslationText !== null) {

                $findTranslationText->translation_text = $translationText['translation_text'];
                $findTranslationText->save();

                $updatedTranslationTexts[] = $findTranslationText->id;
            }
        }

        return $updatedTranslationTexts;
    }

    private function _importTranslationTexts($translationTexts)
    {
        $insertedTranslationTexts = [];

        $translationTextsChunks = array_chunk($translationTexts, 15);

        foreach ($translationTextsChunks as $translationTextsChunk) {
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

    private function _prepareInputTranslation($translations)
    {

        /**
         * Clear inputs and make unique keys
         */

        $readyTranslations = [];
        foreach ($translations as $translation) {

            $translation['translation_namespace'] = trim($translation['translation_namespace']);
            $translation['translation_group'] = trim($translation['translation_group']);
            $translation['translation_key'] = trim($translation['translation_key']);
            $translation['translation_text'] = isset($translation['translation_text']) ? trim($translation['translation_text']) : '';
            $translation['translation_locale'] = isset($translation['translation_locale']) ? trim($translation['translation_locale']) : '';

            // Skip items with empty required fields
            if (empty($translation['translation_key']) ||
                empty($translation['translation_namespace']) ||
                empty($translation['translation_group'])) {
                continue;
            }

            $readyTranslations[$this->_hashFields($translation)] = $translation;
        }

        $readyTranslations = array_values(array_filter($readyTranslations));

        return $readyTranslations;
    }

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }


}
