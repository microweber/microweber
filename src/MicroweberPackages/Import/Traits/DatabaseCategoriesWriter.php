<?php
namespace MicroweberPackages\Import\Traits;

use MicroweberPackages\Import\DatabaseSave;
use function db_get;

trait DatabaseCategoriesWriter
{

	private function _getCategories()
	{
		if (! isset($this->content['categories'])) {

			return;
		}
		$categories = array();
		foreach ($this->content['categories'] as $category) {
			$categories[] = $category;
		}

		return $categories;
	}

	private function _getCateogry($id)
	{
		foreach ($this->_getCategories() as $category) {
			if ($category['id'] == $id) {
				return $category;
			}
		}
	}

	private function _getCategoryDatabase($category)
	{
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['title'] = $category['title'];
		$dbSelectParams['created_at'] = $category['created_at'];

		return db_get('categories', $dbSelectParams);
	}

	/**
	 * Get all categories from backup file, find parent ids and replace it with new parent ids
	 */
	private function _fixCategoryParents()
	{
		$categories = $this->_getCategories();
		if (empty($categories)) {
			return;
		}
        $idToReturn = false;
		foreach ($categories as $category) {

			$getNewCategory = $this->_getCategoryDatabase($category);

			if (! empty($getNewCategory) && $category['parent_id'] > 0) {

				// Find parent
				$parentCategory = $this->_getCateogry($category['parent_id']);
				if (! empty($parentCategory)) {

					$getNewParentCategory = $this->_getCategoryDatabase($parentCategory);

					if (! empty($getNewParentCategory)) {
						$getNewCategory['parent_id'] = $getNewParentCategory['id'];
						DatabaseSave::save('categories', $getNewCategory);
					}
				}
			}
		}
	}
}
