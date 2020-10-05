<?php
namespace MicroweberPackages\Menu\Listeners;


trait ModifyMenuPageTrait
{
    public function handle($event)
    {
        $data = $event->getData();
        $page = $event->getModel();

        if (!empty($data['add_content_to_menu']) && is_array($data['add_content_to_menu'])) {
            foreach ($data['add_content_to_menu'] as $menuId) {
                mw()->content_manager->helpers->add_content_to_menu($page->id, $menuId);
            }
        }


    }
}