<?php
namespace Microweber\Utils\Backup\Traits;

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
		foreach ($this->_getMenus() as $menu) {
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
		$dbSelectParams['title'] = $menu['title'];
		$dbSelectParams['item_type'] = $menu['item_type'];
		$dbSelectParams['created_at'] = $menu['created_at'];

		return db_get('menus', $dbSelectParams);
	}

	/**
	 * Get all menus from backup file, find parent ids and replace it with new parent ids
	 */
	private function _fixMenuParents()
	{
		foreach ($this->_getMenus() as $menu) {

			$getNewMenu = $this->_getMenuDatabase($menu);

			if (! empty($getNewMenu) && $menu['parent_id'] > 0) {

				// Find parent
				$parentMenu = $this->_getMenu($menu['parent_id']);
				if (! empty($parentMenu)) {

					$getNewParentMenu = $this->_getMenuDatabase($parentMenu);

					if (! empty($getNewParentMenu)) {
						$getNewMenu['parent_id'] = $getNewParentMenu['id'];
						db_save('menus', $getNewMenu);
					}
				}
			}
		}
	}
}
	