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

    public function import($translations)
    {

        $forImportKeysAndText = [];
        $foundLangKeys = [];
        $textsToImport = [];
        $textsToInsertBulk = [];
        $textsToUpdateBulk = [];

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
                $getTranslationKeyId = null;
                $md5Text = md5($translation['translation_key']);;


                if ($foundLangKeys) {
                    if (isset($foundLangKeys[$md5Text])) {
                        $getTranslationKeyId = $foundLangKeys[$md5Text]['id'];
                        $forImportText = $foundLangKeys[$md5Text];
                        $forImportText['translation_locale'] = $translation['translation_locale'];
                        $forImportText['translation_text'] = $translation['translation_text'];
                        $forImportText['translation_namespace'] = $translation['translation_namespace'];
                        $forImportText['translation_group'] = $translation['translation_group'];
                        $forImportText['translation_key_id'] = $getTranslationKeyId;
                        $forImportKeysAndText[$md5Text] = $forImportText;
                    }
                }

                if (!isset($foundLangKeys[$md5Text])) {
                    $forImportText = [];
                    $forImportText['translation_locale'] = $translation['translation_locale'];
                    $forImportText['translation_text'] = $translation['translation_text'];
                    $forImportText['translation_namespace'] = $translation['translation_namespace'];
                    $forImportText['translation_group'] = $translation['translation_group'];
                    $forImportText['translation_key'] = $translation['translation_key'];

                    $forImportKeysAndText[$md5Text] = $forImportText;

                }

            }


            if ($forImportKeysAndText) {

                $forImportKeys = [];
                $forImportKeysDb = [];
                foreach ($forImportKeysAndText as $md5Key => $forImportKeysAndTextItem) {
                    if (!isset($forImportKeysAndTextItem['translation_key_id'])) {
                        $forImportKeys[$md5Key] = $forImportKeysAndTextItem;
                    }
                }

                if ($forImportKeys) {
                    $forImportKeysDbBulk = [];
                    foreach ($forImportKeys as $md5Key => $forImportKey) {
                        $getTranslationKey = new TranslationKey();
                        $getTranslationKey->translation_key = $forImportKey['translation_key'];
                        $getTranslationKey->translation_namespace = $forImportKey['translation_namespace'];
                        $getTranslationKey->translation_group = $forImportKey['translation_group'];

                        $forImportKeysDb[$md5Key] = $getTranslationKey;

                        $forImportKeysDbBulk[] = ['translation_key' => $forImportKey['translation_key'], 'translation_namespace' => $forImportKey['translation_namespace'], 'translation_group' => $forImportKey['translation_group']];


                    }

                    if ($forImportKeysDbBulk) {
                        $insertedKeysChunnks = [];
                        $forImportKeysDbBulk_chunked = array_chunk($forImportKeysDbBulk, 100);
                        foreach ($forImportKeysDbBulk_chunked as $k => $forImportKeysDbBulk_chunk) {
                            $this->log("Importing translation keys chunk " . $k);

                            $getTranslationKey = new TranslationKey();
                            $insertedKeys = $getTranslationKey->insert(
                                $forImportKeysDbBulk_chunk
                            );
                            $insertedKeysChunnks[] = $insertedKeys;
                        }
                    }


                }


                $allLangKeysDb = TranslationKey::select(['id', 'translation_key'])->get();
                if ($allLangKeysDb != null) {

                    $allLangKeysDb = $allLangKeysDb->toArray();
                    if ($allLangKeysDb) {
                        foreach ($allLangKeysDb as $allLangKey) {
                            if (!isset($allLangKey['translation_key']) or !$allLangKey['translation_key']) {
                                continue;
                            }
                            $translation_key_md5 = md5($allLangKey['translation_key']);

                            if (isset($forImportKeysAndText[$translation_key_md5])) {
                                $importTextData = $forImportKeysAndText[$translation_key_md5];

                                $getTranslationText = TranslationText::where('translation_key_id', $allLangKey['id'])
                                    ->where('translation_locale', $importTextData['translation_locale'])
                                    ->limit(1)->first();


                                if ($getTranslationText == null) {
                                    $importText = [];
                                    $importText['translation_key_id'] = $allLangKey['id'];
                                    $importText['translation_text'] = $importTextData['translation_text'];
                                    $importText['translation_locale'] = $importTextData['translation_locale'];
                                    $textsToInsertBulk[] = $importText;
                                } else {
                                    if ($this->replaceValues) {
                                        $replaceText = [];
                                        $replaceText['translation_key_id'] = $allLangKey['id'];
                                        $replaceText['id'] = $getTranslationText['id'];
                                        $replaceText['translation_text'] = $importTextData['translation_text'];
                                        $replaceText['translation_locale'] = $importTextData['translation_locale'];
                                        $textsToUpdateBulk[] = $replaceText;
                                    }
                                }
                            }
                        }
                    }
                    if ($textsToInsertBulk) {
                        $forImportTextDbBulk_chunked = array_chunk($textsToInsertBulk, 100);
                        foreach ($forImportTextDbBulk_chunked as $k => $forImportTextDbBulk_chunk) {
                            $this->log("Importing translation texts chunk " . $k);
                            $insertTranslationText = new TranslationText();
                            $insertTranslationText->insert($forImportTextDbBulk_chunk);

                        }
                    }

                    if ($textsToUpdateBulk) {


                        DB::beginTransaction();

                        try {
                            foreach ($textsToUpdateBulk as $k => $forImportTextDbBulk_chunk) {
                                $this->log("Updating translation texts chunk " . $k);
                                $insertTranslationText = new TranslationText();
                                $insertTranslationText->where('id', $forImportTextDbBulk_chunk['id'])->update($forImportTextDbBulk_chunk);
                            }

                            DB::commit();
                            // all good
                        } catch (\Exception $e) {
                            DB::rollback();
                            // something went wrong
                        }





                    }

                }


            }

            \Cache::tags('translation_keys')->flush();
            \Cache::tags('translation_texts')->flush();

            $msg = 'Importing language file success.';
            if ($textsToInsertBulk) {
                $msg .= ' Inserted values ' . count($textsToInsertBulk);
            }
            if ($textsToUpdateBulk) {
                $msg .= ' Replaced values ' . count($textsToUpdateBulk);
            }

            return ['success' => $msg];
        }

        return ['error' => 'Can\'t import this language file.'];
    }

    /*
        public function _importSlow($translations)
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


                    if ($foundLangKeys) {
                        $md5Text = md5($translation['translation_key']);;
                        if (isset($foundLangKeys[$md5Text])) {
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
        }*/

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }


}