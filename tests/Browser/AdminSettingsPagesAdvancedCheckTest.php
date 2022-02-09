<?php

namespace Tests\Browser;

use Arcanedev\SeoHelper\Entities\OpenGraph\Graph;
use Arcanedev\SeoHelper\Entities\Twitter\Card;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;
use Arcanedev\SeoHelper\Entities\Analytics;

class AdminSettingsPagesAdvancedCheckTest extends DuskTestCase
{
    public function testHeadTagsTextAreaValue()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $seoTags = [];

            $card = new Card();

            $card->setType('gallery');
            $card->setSite('@Arcanedev');
            $card->addMeta('creator', '@Arcanedev');
            $card->setTitle('Your awesome title');
            $card->setDescription('Your awesome description');
            $card->addMeta('url', 'http://my.awesome-website.com');
            $card->addImage('http://my.awesome-website.com/img/cool-image.jpg');

            $seoTags[] = $card->render();


            $openGraph = new Graph();

            $openGraph->setType('website');
            $openGraph->setTitle('Your awesome title');
            $openGraph->setDescription('Your awesome description');
            $openGraph->setSiteName('Your site name');
            $openGraph->setUrl('http://my.awesome-website.com');
            $openGraph->setImage('http://my.awesome-website.com/img/cool-image.jpg');
// Of course you can chain all these methods

            $seoTags[] =  $openGraph->render();



            $analytics = new Analytics;

            $analytics->setGoogle('UA-12345678-9');

            $seoTags[] = $analytics->render();

            $seoTag = implode("\n", $seoTags);



            save_option(array(
                'option_group' => 'website',
                'module' => 'settings/group/custom_head_tags',
                'option_key' => 'website_head',
                'option_value' => $seoTag
            ));

            $browser->visit(admin_url().'view:shop/action:options#option_group=advanced');

            $browser->pause(3000);

            $html = $browser->script("return $('[name=website_head]').eq(0).first().val()");


            $seoTag = preg_replace("/\r\n|\r|\n/", '<br/>', $seoTag);
            $html[0] = preg_replace("/\r\n|\r|\n/", '<br/>', $html[0]);
            $this->assertEquals(nl2br($seoTag), nl2br($html[0]));


        });
    }
}
