<?php

namespace MicroweberPackages\Template\Traits;


use Knp\Menu\ItemInterface;
use MicroweberPackages\Admin\MenuBuilder\Menu;

trait HasMenus
{
    /**
     * @var array $menus An array of menu instances.
     */
    public array $menus;

    /**
     * Get the menu items for a specific menu sorted to according to their order number.
     *
     * @param string $menu The name of the menu.
     * @return array The menu items.
     */
    public function getMenu(string $menu) : array
    {
        $this->reorderMenuItems($this->menus[$menu]->menuItems);
        $item = $this->menus[$menu]->menuItems;

        /**
         * @var ItemInterface $item
         */
        return $item->getChildren();
    }

    /**
     * Get the instance of a specific menu.
     *
     * @param string $menu The name of the menu.
     * @return mixed The instance of the menu.
     */
    public function getMenuInstance($menu)
    {
        return $this->menus[$menu];
    }

    /**
     * Get the instance of a specific menu.
     *
     * @param string $menu The name of the menu.
     * @return mixed The instance of the menu.
     */
    public function getOrCreateMenuInstance($menuName)
    {
        if (!isset($this->menus[$menuName])) {
            $this->menus[$menuName] = new Menu($menuName);
        }
        return $this->menus[$menuName];

    }
    /**
     * Reorder the menu items according to their order number.
     *
     * @param ItemInterface $menu The menu items to be reordered.
     */
    public function reorderMenuItems(ItemInterface $menu): void
    {
        $menuOrderArray = [];
        $addLast = [];
        $alreadyTaken = [];

        foreach ($menu->getChildren() as $key => $menuItem) {
            if ($menuItem->hasChildren()) {
                $this->reorderMenuItems($menuItem);
            }

            $orderNumber = $menuItem->getExtra('orderNumber');

            if ($orderNumber !== null) {
                if (!isset($menuOrderArray[$orderNumber])) {
                    $menuOrderArray[$orderNumber] = $menuItem->getName();
                } else {
                    $alreadyTaken[$orderNumber] = $menuItem->getName();
                }
            } else {
                $addLast[] = $menuItem->getName();
            }
        }

        ksort($menuOrderArray);

        foreach ($alreadyTaken as $key => $value) {
            $position = array_search($key, array_keys($menuOrderArray));
            if ($position !== false) {
                $menuOrderArray = array_merge(
                    array_slice($menuOrderArray, 0, $position),
                    [$value],
                    array_slice($menuOrderArray, $position)
                );
            }
        }

        ksort($menuOrderArray);

        foreach ($addLast as $value) {
            $menuOrderArray[] = $value;
        }

        if (!empty($menuOrderArray)) {
            $menu->reorderChildren($menuOrderArray);
        }
    }

}
