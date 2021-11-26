<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AdminContentMultilanguage extends BaseComponent
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
        // $browser->assertVisible($this->selector());
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

    public function addLanguage(Browser $browser, $locale) {

        if (is_lang_supported($locale)) {
            return true;
        }

        $browser->visit(route('admin.multilanguage.index'));
        $browser->waitForText('Add new language');
        $browser->select('.js-dropdown-text-language', $locale);
        $browser->pause(3000);
        $browser->click('.js-add-language');
        $browser->pause(2000);
        $browser->waitForText($locale);

    }

    public function fillTitle(Browser $browser, $title, $locale)
    {
        $browser->pause(2000);
        $browser->select('#ml-input-title-change', $locale);
        $browser->pause(4000);
        $browser->script("$('.js-input-group-title .form-control:visible').val('".$title."')");
    }

    public function fillContent(Browser $browser, $description, $locale)
    {
        $browser->script('$(".js-ml-btn-tab-content[lang=\''.$locale.'\']").click();');
        $browser->pause(4000);

        $randClass = 'js-rand-ml-'.time().rand(1111,9999);
        $browser->script("$('#ml-tab-content-content .tab-pane:visible .mw-editor-area').addClass('$randClass')");
        $browser->keys('.' . $randClass, $description);

        $browser->pause(4000);

    }

    public function fillContentBody(Browser $browser, $description, $locale)
    {
        $browser->script('$(".js-ml-btn-tab-content_body[lang=\''.$locale.'\']").click();');
        $browser->pause(4000);

        $randClass = 'js-rand-ml-'.time().rand(1111,9999);
        $browser->script("$('#ml-tab-content-content_body .tab-pane:visible .mw-editor-area').addClass('$randClass')");
        $browser->keys('.' . $randClass, $description);

        $browser->pause(4000);

    }

}
