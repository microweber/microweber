<?php

namespace MicroweberPackages\Translation;

class TranslationRefreshLangKeys
{
    public function start($params)
    {
        $langEnJsonPath = __DIR__ . '/resources/lang/en_US.json';

        $langFromJson = file_get_contents($langEnJsonPath);
        $langFromJson = json_decode($langFromJson, true);

        $langFromDb = [];
        $getTranslations = \MicroweberPackages\Translation\Models\TranslationKey::getGroupedTranslations([
            'all' => true,
        ]);
        foreach ($getTranslations['results'] as $langKey => $langTranslations) {
            $enTranslationValue = $langKey;
            if (isset($langTranslations['en_US'])) {
                $enTranslationValue = $langTranslations['en_US'];
            }
            $langFromDb[$langKey] = $enTranslationValue;
        }

        $langDiff = 0;
        foreach ($langFromDb as $key => $value) {
            if (!isset($langFromJson[$key])) {
                $langFromJson[$key] = $value;
                $langDiff++;
            }
        }

        if ($langDiff > 0) {
            $langFromJson = json_encode($langFromJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            if (isset($params['saveIn'])) {
                $langEnJsonPath = $params['saveIn'];
            }
            file_put_contents($langEnJsonPath, $langFromJson);
        }
    }
}
