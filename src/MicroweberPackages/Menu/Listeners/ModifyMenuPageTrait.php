<?php
namespace MicroweberPackages\Menu\Listeners;


trait ModifyMenuPageTrait
{
    public function handle($event)
    {
        $request = $event->getRequest();
        $page = $event->getModel();

        if (!empty($request['add_content_to_menu']) && is_array($request['add_content_to_menu'])) {
            foreach ($request['add_content_to_menu'] as $menuId) {
                mw()->content_manager->helpers->add_content_to_menu($page->id, $menuId);
            }
        }


    }
}