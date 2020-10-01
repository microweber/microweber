<?php

namespace MicroweberPackages\Page\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\Page\Page;
use MicroweberPackages\Page\Events\PageIsCreating;
use MicroweberPackages\Page\Events\PageIsUpdating;
use MicroweberPackages\Page\Events\PageWasCreated;
use MicroweberPackages\Page\Events\PageWasDeleted;
use MicroweberPackages\Page\Events\PageWasUpdated;

class PageRepository extends BaseRepository
{

    public function create($request)
    {
        event($event = new PageIsCreating($request));

        $page = Page::create($request);

        event(new PageWasCreated($page, $request));


        return $page->id;
    }

    public function update($page, $request)
    {
        event($event = new PageIsUpdating($page, $request));

        $page->update($request);

        event(new PageWasUpdated($page, $request));

        return $page->id;
    }


    public function destroy($page)
    {
        event(new PageWasDeleted($page));

        return $page->delete();
    }

}
