<?php

namespace MicroweberPackages\Modules\Video\tests\Browser;

use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use MicroweberPackages\Form\Models\FormData;
use MicroweberPackages\Form\Models\FormDataValue;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class VideoTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmit()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {

            $browser->visit($siteUrl);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });



        });
    }

}
