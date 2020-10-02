<?php
/**
 * Created by PhpStorm.
 * Page: Bojidar
 * Date: 8/19/2020
 * Time: 2:53 PM
 */

namespace MicroweberPackages\Page\Observers;

use MicroweberPackages\Page\Models\Page;

class PageObserver
{
    /**
     * Handle the Page "saving" event.
     *
     * @param  \MicroweberPackages\Page\Page  $page
     * @return void
     */
    public function saving(Page $page)
    {
        $page->content_type = 'page';
        
        cache_delete('content');
    }
}