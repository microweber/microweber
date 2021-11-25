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

    public function addLanguage(Browser $browser, $language) {

        $browser->visit(route('admin.multilanguage.index'));
        $browser->waitForText('Add new language');
        $browser->select('.js-dropdown-text-language', $language);
        $browser->pause(3000);
        $browser->click('.js-add-language');
        $browser->pause(2000);
        $browser->waitForText($language);

    }

    public function fillTitle(Browser $browser, $title, $locale)
    {
        $browser->pause(2000);
        $browser->select('#ml-input-title-change', $locale);
        $browser->pause(4000);
        $browser->script("$('.js-input-group-title .form-control:visible').val('".$title."')");
    }

    public function fillDescription(Browser $browser, $description, $locale)
    {
        $browser->script('$(".js-ml-btn-tab-content[lang=\''.$locale.'\']").click();');
        $browser->pause(5000);
        $browser->script("$('#ml-tab-content-content .tab-pane:visible').find('.mw-editor-area').html('$description')");
        $browser->pause(5000);

    }

}
