<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

class AdminCategoryMultilanguage extends BaseComponent
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


    public function fillTitle(Browser $browser, $title, $locale)
    {
        $browser->within(new AdminMultilanguageFields, function ($browser) use ($title, $locale) {
            $browser->scrollTo('[name="title"]');

            $browser->fillInput('title', $title, $locale);
        });
    }

    public function fillUrl(Browser $browser, $url, $locale)
    {
        $browser->within(new AdminMultilanguageFields, function ($browser) use ($url, $locale) {
            $browser->scrollTo('[name="url"]');
            $browser->fillInput('url', $url, $locale);
        });
    }

    public function fillDescription(Browser $browser, $content, $locale)
    {
        $browser->within(new AdminMultilanguageFields, function ($browser) use ($content, $locale) {
            $browser->scrollTo('[name="description"]');
            $browser->fillTextarea('description', $content, $locale);
        });
    }

}
