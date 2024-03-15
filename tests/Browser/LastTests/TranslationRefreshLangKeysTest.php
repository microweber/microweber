<?php

namespace Tests\Browser\LastTests;

use Tests\DuskTestCaseMultilanguage;

class TranslationRefreshLangKeysTest  extends DuskTestCaseMultilanguage
{
    public function testRefreshLangKeys()
    {
        $refresh = new \MicroweberPackages\Translation\TranslationRefreshLangKeys();
        $refresh->start([
            'saveIn'=>storage_path() . '/logs/en_US.json',
        ]);
    }
}
