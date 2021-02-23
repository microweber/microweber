<?php

namespace MicroweberPackages\Translation;


use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;

class TranslationImport
{
    public $logger = null;

    public function import($translations)
    {

        $foundLangKeys = [];
        $textsToImport = [];
        $allLangKeysDb = TranslationKey::select(['id', 'translation_key'])->get();
        if ($allLangKeysDb != null) {
            $allLangKeysDb = $allLangKeysDb->toArray();
            if ($allLangKeysDb) {
                foreach ($allLangKeysDb as $allLangKey) {
                    if (!isset($allLangKey['translation_key']) or !$allLangKey['translation_key']) {
                        continue;
                    }
                    $allLangKey['translation_key_md5'] = md5($allLangKey['translation_key']);
                    $foundLangKeys[$allLangKey['translation_key_md5']] = $allLangKey;
                }
            }
        }


        if (is_array($translations)) {
            foreach ($translations as $translation) {

                if (!isset($translation['translation_text'])) {
                    continue;
                }
                if (!isset($translation['translation_locale'])) {
                    continue;
                }
                $translationText = trim($translation['translation_text']);
                if (empty($translationText)) {
                    continue;
                }
                $getTranslationKey = null;
                $getTranslationKeyId = null;


//                $getTranslationKey = TranslationKey::where(\DB::raw('md5(translation_key)'), md5($translation['translation_key']))
//                    ->select('id')
//                    ->where('translation_namespace', $translation['translation_namespace'])
//                    ->where('translation_group', $translation['translation_group'])
//                    ->limit(1)
//                    ->first();


                if($foundLangKeys){
                    $md5Text =  md5($translation['translation_key']);;
                    if(isset($foundLangKeys[$md5Text])){
                        $getTranslationKey = $foundLangKeys[$md5Text];
                        $getTranslationKeyId = $foundLangKeys[$md5Text]['id'];
                    }
                }


                if ($getTranslationKey == null) {
                    $getTranslationKey = new TranslationKey();
                    $getTranslationKey->translation_key = $translation['translation_key'];
                    $getTranslationKey->translation_namespace = $translation['translation_namespace'];
                    $getTranslationKey->translation_group = $translation['translation_group'];
                    $getTranslationKey->save();
                    $getTranslationKeyId = $getTranslationKey->id;
                    $this->log("Imported translation key " . $getTranslationKeyId);

                }

                if ($getTranslationKeyId) {
                    // Get translation text
                    $getTranslationText = TranslationText::where('translation_key_id', $getTranslationKeyId)
                        ->where('translation_locale', $translation['translation_locale'])
                        ->limit(1)
                        ->first();

                    // Save new translation text
                    if ($getTranslationText == null) {
                        $this->log("Importing translation text for key " . $getTranslationKeyId);
                        $getTranslationText = new TranslationText();
                        $getTranslationText->translation_key_id = $getTranslationKeyId;
                        $getTranslationText->translation_locale = $translation['translation_locale'];
                        $getTranslationText->translation_text = $translationText;
                        $getTranslationText->save();

                    }
                }


            }



            \Cache::tags('translation_keys')->flush();

            return ['success' => 'Importing language file success.'];
        }

        return ['error' => 'Can\'t import this language file.'];
    }

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }


}