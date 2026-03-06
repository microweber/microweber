<?php

namespace Tests\Browser\LastTests;

use PHPUnit\Framework\Attributes\Test;

use Tests\DuskTestCaseMultilanguage;

class TranslationRefreshLangKeysTest  extends DuskTestCaseMultilanguage
{
    #[Test]
    public function it_refresh_lang_keys(): void {
        $refresh = new \MicroweberPackages\Translation\TranslationRefreshLangKeys();
        $refresh->start([
            'saveIn'=>storage_path() . '/logs/en_US.json',
        ]);
    }
}
