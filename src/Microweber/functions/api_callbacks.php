<?php


api_hook('content/menu_item_save', function ($data) {
    return mw()->content_manager->menu_item_save($data);
});

api_hook('content/menu_items_reorder', function ($data) {
    return mw()->content_manager->menu_items_reorder($data);
});
