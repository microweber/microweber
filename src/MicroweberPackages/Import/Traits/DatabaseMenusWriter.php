<?php
namespace MicroweberPackages\Import\Traits;

use MicroweberPackages\Import\DatabaseSave;
use function db_get;

trait DatabaseMenusWriter
{

	private function _getMenus()
	{
		if (! isset($this->content['menus'])) {
			return;
		}

		$menus = array();
		foreach ($this->content['menus'] as $menu) {
			$menus[] = $menu;
		}

		return $menus;
	}

	private function _getMenu($id)
	{
		$menus = $this->_getMenus();
		if (empty($menus)) {
			return;
		}

		foreach ($menus as $menu) {
			if ($menu['id'] == $id) {
				return $menu;
			}
		}
	}

	private function _getMenuDatabase($menu)
	{
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['item_type'] = $menu['item_type'];

		if (! empty($menu['title'])) {
			$dbSelectParams['title'] = $menu['title'];
		}

		return db_get('menus', $dbSelectParams);
	}

	private function _getMenuItemDatabase($menuItem)
	{
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['item_type'] = $menuItem['item_type'];
		$dbSelectParams['content_id'] = $menuItem['content_id'];
		$dbSelectParams['parent_id'] = $menuItem['parent_id'];

		if (! empty($menuItem['title'])) {
			$dbSelectParams['title'] = $menuItem['title'];
		}

		return db_get('menus', $dbSelectParams);
	}

	private function _saveMenuItem($menu)
	{
		// Save new menu
		$saveNewMenu = $menu;

		// Get content for menu
		$content = $this->_getContentById($menu['content_id']);
		if (! empty($content)) {
			$contentDatabase = $this->_getContentDatabase($content);
			if (! empty($contentDatabase)) {
				$saveNewMenu['content_id'] = $contentDatabase['id'];
			}
		}

		// Get parent for menu
		$parentMenu = $this->_getMenu($menu['parent_id']);
		if (! empty($parentMenu)) {
			$parentMenuDatabase = $this->_getMenuDatabase($parentMenu);
			if (! empty($parentMenuDatabase)) {
				$saveNewMenu['parent_id'] = $parentMenuDatabase['id'];
			}
		}

		// Save menu item
		if (! empty($saveNewMenu)) {
			unset($saveNewMenu['id']);
			$menuItemDatabase = $this->_getMenuItemDatabase($saveNewMenu);
			if (empty($menuItemDatabase)) {
				return DatabaseSave::save('menus', $saveNewMenu);
			}
            return $menuItemDatabase['id'];
		}
	}

	/**
	 * Get all menus from backup file, find parent ids and replace it with new parent ids
	 */
	private function _fixMenuParents()
	{
		$menus = $this->_getMenus();

		if (empty($menus)) {
			return;
		}

		foreach ($menus as $menu) {

			$getNewMenu = $this->_getMenuDatabase($menu);

			// Only for main menus
			if (! empty($getNewMenu) && empty($menu['content_id']) && $menu['parent_id'] > 0) {

				// Find parent
				$parentMenu = $this->_getMenu($menu['parent_id']);
				if (! empty($parentMenu)) {

					$getNewParentMenu = $this->_getMenuDatabase($parentMenu);

					if (! empty($getNewParentMenu)) {
						$getNewMenu['parent_id'] = $getNewParentMenu['id'];

						DatabaseSave::save('menus', $getNewMenu);
					}
				}
			}
		}
	}
}
