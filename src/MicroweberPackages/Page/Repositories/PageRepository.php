<?php

namespace MicroweberPackages\Page\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Page\Events\PageIsCreating;
use MicroweberPackages\Page\Events\PageIsUpdating;
use MicroweberPackages\Page\Events\PageWasCreated;
use MicroweberPackages\Page\Events\PageWasDeleted;
use MicroweberPackages\Page\Events\PageWasUpdated;
use MicroweberPackages\Page\Models\Page;

class PageRepository extends BaseRepository
{

    public function create($request)
    {
        event($event = new PageIsCreating($request));

        $page = Page::create($request);

        event(new PageWasCreated($request, $page));


        return $page->id;
    }

    public function update($request, $page)
    {
        event($event = new PageIsUpdating($request, $page));

        $page->update($request);

        event(new PageWasUpdated($request, $page));

        return $page->id;
    }


    public function destroy($page)
    {
        event(new PageWasDeleted($page));

        return $page->delete();
    }

}
