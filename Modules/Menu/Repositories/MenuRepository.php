<?php

namespace Modules\Menu\Repositories;

use MicroweberPackages\Repository\Repositories\CachingModelRepository;
use Modules\Menu\Models\Menu;

class MenuRepository extends CachingModelRepository
{

    protected string $modelClass = Menu::class;

    public static $_getAllMenus = [];

    public function clearCache(): void
    {
        self::$_getAllMenus = [];
        parent::clearCache();
    }

    public function getAllMenus()
    {
        if (!empty(self::$_getAllMenus)) {
            return self::$_getAllMenus;
        }

        $menus = $this->cached(__FUNCTION__, func_get_args(), function () {
            return Menu::queryAllOrdered();
        });

        self::$_getAllMenus = $menus;
        return $menus;
    }

    public function getMenusByParentIdAndItemType($parentId, $itemType)
    {
        $allMenus = $this->getAllMenus();
        return Menu::filterByParentIdAndItemType($allMenus, $parentId, $itemType);
    }

    public function getMenusByParentId($parentId)
    {
        $allMenus = $this->getAllMenus();
        $menus = Menu::filterByParentId($allMenus, $parentId);

        if (is_array($menus) && !empty($menus)) {
            $hookParams = [];
            $hookParams['data'] = $menus;
            $hookParams['hook_overwrite_type'] = 'multiple';

            $overwrite = app()->event_manager->response(get_class($this) . '\\' . __FUNCTION__, $hookParams);

            if (isset($overwrite['data'])) {
                $menus = $overwrite['data'];
            }
        }

        return $menus;
    }

    public function getMenus($params)
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () use ($params) {
            return Menu::getMenus($params);
        });
    }


}
