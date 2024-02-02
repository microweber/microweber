<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

class AdminContentTagAdd extends BaseComponent
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

    public function addTag(Browser $browser, $tag)
    {
        $browser->scrollTo('#content-tags-search-block');
        $browser->keys('#content-tags-search-block input',$tag);
        $browser->pause(369);
        $browser->keys('#content-tags-search-block input','{enter}');
        $browser->pause(999);

    }

}
