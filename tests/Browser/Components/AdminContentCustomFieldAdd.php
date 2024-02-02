<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

class AdminContentCustomFieldAdd extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#mw-admin-container';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }

    public function addCustomField(Browser $browser, $key, $value) {

       // $browser->script("$('html, body').animate({ scrollTop: $('.js-custom-fields-card-tab').offset().top -80 }, 0);");
        $browser->script("document.querySelector('.js-custom-fields-card-tab').scrollIntoView({block: 'center', inline: 'nearest',behavior :'auto'});");
        $browser->pause(1000);

        if(!$browser->driver->findElement(WebDriverBy::cssSelector('#custom-fields-settings'))->isDisplayed()) {
             $browser->script("document.querySelector('.js-custom-fields-card-tab').scrollIntoView({block: 'center', inline: 'nearest',behavior :'auto'});");


            $browser->click('.js-custom-fields-card-tab');

            $browser->pause(3000);
        }




        // add custom field price
        $browser->waitForText('Add new field');
        $browser->script("document.querySelector('.js-add-custom-field').scrollIntoView({block: 'center', inline: 'nearest',behavior :'auto'});");
        $browser->pause(300);

        $browser->click('.js-add-custom-field');
        $browser->pause(3000);
//        $browser->waitForText('Fields');
        $browser->switchFrameDefault();
        $iframeElement = $browser->driver->findElement(WebDriverBy::id('mw-livewire-component-iframe-content-window'));

        if($iframeElement){
            $browser->switchFrame($iframeElement);
        }


        $browser->pause(1000);

         $cfKey = '.js-add-custom-field-'.$key;
        $browser->script("document.querySelector('button".$cfKey."').click()");
        if($iframeElement){
            $browser->switchFrameDefault();
        }
        $browser->pause(2000);
        if($browser->driver->findElement(WebDriverBy::cssSelector('.mw-dialog-container'))->isDisplayed()) {
            $browser->waitUntilMissing('.mw-dialog-container.has-mw-spinner', 30);
        }
        $browser->waitFor('#mw-livewire-component-iframe-content-window');
       // $browser->switchFrameDefault();
         $iframeElement = $browser->driver->findElement(WebDriverBy::id('mw-livewire-component-iframe-content-window'));
        $browser->switchFrame($iframeElement);
      //  if($iframeElement){
         //   $browser->switchFrame($iframeElement);
      //  }

      //  $browser->script("document.querySelector('body').scrollIntoView({block: 'center', inline: 'nearest',behavior :'auto'});");
     //   $browser->script("document.querySelector('body').css('background-color','red');");
        //$browser->script("document.querySelector('body').style.backgroundColor = 'red';");
        $browser->pause(5000);
       // $browser->waitFor('.mw-modal-header',30);
 //    $browser->waitFor('.btn-close',30);
  //      $browser->pause(500000);
      $browser->click('#js-save-custom-field');
        $browser->pause(100);
     //   $browser->script("document.querySelector('#js-save-custom-field').click()");
   //     $browser->script("document.querySelector('.btn-close').click()");
   //     $browser->click('.btn-close');
        $browser->script("document.querySelector('#js-save-custom-field').style.backgroundColor = 'red';");
        $browser->script("document.querySelector('#js-save-custom-field').click()");
        $browser->pause(100);
        $browser->waitUntilMissing('#js-save-custom-field-loading', 30);

        $browser->pause(5000);
        $browser->script("document.querySelector('.btn-close').click()");
      //  $browser->pause(40000);
       // if($browser->driver->findElement(WebDriverBy::cssSelector('.mw-modal-header .btn-close'))->isDisplayed()) {

         //   $browser->script("document.querySelector('.mw-modal-header .btn-close').click()");
     //   }



        //        $browser->whenAvailable($cfKey, function ($b) use ($cfKey) { // Wait
//            $b->script("document.querySelector('".$cfKey."').scrollIntoView({block: 'center', inline: 'nearest',behavior :'auto'});");
//            $b->pause(300);
//
//            $b->click($cfKey);
//        });

     //   $browser->waitFor($cfKey,100);





        if($iframeElement){
            $browser->switchFrameDefault();
            $browser->waitForText($value,30);

        }

    }
}
