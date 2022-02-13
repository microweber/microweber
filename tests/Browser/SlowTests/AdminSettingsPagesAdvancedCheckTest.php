<?php

namespace Tests\Browser\SlowTests;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\OpenGraph\Graph;
use Arcanedev\SeoHelper\Entities\Twitter\Card;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;
use function admin_url;
use function save_option;

class AdminSettingsPagesAdvancedCheckTest extends DuskTestCase
{
    public function testHeadTagsTextAreaValue()
    {

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


        $seoTags[] =  $openGraph->render();



        $analytics = new Analytics;

        $analytics->setGoogle('UA-12345678-9');

        $seoTags[] = $analytics->render();

        $seoTag = implode("\n", $seoTags);



        save_option(array(
            'option_group' => 'website',
            'option_key' => 'website_head',
            'option_value' => $seoTag
        ));




        $footerTags = new Webmasters([
            'google'    => 'google-site-verification-code',
            'bing'      => 'bing-site-verification-code',
            'alexa'     => 'alexa-site-verification-code',
            'pinterest' => 'pinterest-site-verification-code',
            'yandex'    => 'yandex-site-verification-code'
        ]);

        $footerTags =  $footerTags->render();


        save_option(array(
            'option_group' => 'website',
            'module' => 'settings/group/custom_head_tags',
            'option_key' => 'website_footer',
            'option_value' => $footerTags
        ));

        $robotsTxt = 'User-Agent: *
        Allow: /';

        save_option(array(
            'option_group' => 'website',
            'module' => 'settings/group/custom_head_tags',
            'option_key' => 'robots_txt',
            'option_value' => $robotsTxt
        ));



        $this->browse(function (Browser $browser)  use ($seoTag,$footerTags,$robotsTxt) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();

            });



            $browser->visit(admin_url().'view:settings?group=advanced');
            $browser->pause(5000);
            $browser->waitForText('Settings',30);

            $html = $browser->script("return $('[name=website_head]').first().val()");
            $seoTag = preg_replace("/\r\n|\r|\n/", '<br/>', $seoTag);
            $html[0] = preg_replace("/\r\n|\r|\n/", '<br/>', $html[0]);
            $this->assertEquals(nl2br($seoTag), nl2br($html[0]));


            $html = $browser->script("return $('[name=website_footer]').first().val()");
            $footerTags = preg_replace("/\r\n|\r|\n/", '<br/>', $footerTags);
            $html[0] = preg_replace("/\r\n|\r|\n/", '<br/>', $html[0]);
            $this->assertEquals(nl2br($footerTags), nl2br($html[0]));


            $html = $browser->script("return $('[name=robots_txt]').first().val()");
            $robotsTxt = preg_replace("/\r\n|\r|\n/", '<br/>', $robotsTxt);
            $html[0] = preg_replace("/\r\n|\r|\n/", '<br/>', $html[0]);
            $this->assertEquals(nl2br($robotsTxt), nl2br($html[0]));






        });
    }

}
