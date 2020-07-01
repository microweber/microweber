<?php 
namespace Microweber\Utils\Backup\Traits;

use Microweber\Utils\Backup\DatabaseSave;

trait DatabaseCategoryItemsWriter {
	
	private function _getCategoriesItems($itemId) {
		
		if (!isset($this->content['categories_items'])) {
			return;
		}
		
		$contentData = array();
		foreach($this->content['categories_items'] as $dataItem) {
			if ($dataItem['rel_id'] == $itemId) {
				$contentData[] = $dataItem;
			}
		}
		
		return $contentData;
	}
	
	private function _saveCategoriesItems($savedItem) {
		
		if (!isset($savedItem['item']['id'])) {
			return;
		}
		
		// Get content data from file export
		$categoriesItems = $this->_getCategoriesItems($savedItem['item']['id']);
		
		if (!empty($categoriesItems)) {
			foreach ($categoriesItems as $categoryItem) {
				$this->_saveCategoryItem($categoryItem, $savedItem['itemIdDatabase']);
			}
		}
	}
	
	private function _getCategory($parentId) {
		
		if (!isset($this->content['categories'])) {
			echo 'Categories not found.';
			return;
		}
		
		foreach($this->content['categories'] as $category) {
			
			if ($category['id'] == $parentId) {
				
				$dbSelectParams = array();
				$dbSelectParams['no_cache'] = true;
				$dbSelectParams['limit'] = 1;
				$dbSelectParams['single'] = true;
				$dbSelectParams['do_not_replace_site_url'] = 1;
				$dbSelectParams['data_type'] = $category['data_type'];
				$dbSelectParams['title'] = $category['title'];
				$dbSelectParams['created_at'] = $category['created_at'];
				$dbSelectParams['rel_type'] = $category['rel_type'];
				
				$checkCategoryIsExists = db_get('categories', $dbSelectParams);
				
				if ($checkCategoryIsExists) {
					return $checkCategoryIsExists;
				} else {
					
					// Save category data if not exists
					$category['save_to_table'] = 'categories';
					$this->_saveItemDatabase($category);
					
					//echo $category['title'] . ': Category not found.' . PHP_EOL;
					
					return $this->_getCategory($parentId);
				}
			}
		}
		
	}
	
	private function _saveCategoryItem($categoryItem, $itemIdDatabase) {
		
		$category = $this->_getCategory($categoryItem['parent_id']);
		
		// New parent id
		$categoryItem['parent_id'] = $category['id'];
		if (empty($categoryItem['parent_id'])) {
			// Dont save item
			return;
		}
		
		// New rel id
		$categoryItem['rel_id'] = $itemIdDatabase;
		
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['parent_id'] = $categoryItem['parent_id'];
		$dbSelectParams['rel_id'] = $categoryItem['rel_id'];
		$dbSelectParams['rel_type'] = $categoryItem['rel_type'];
		
		$checkCategoryItemsIsExists = db_get('categories_items', $dbSelectParams);
		if ($checkCategoryItemsIsExists) {
			$categoryItemIdDatabase = $checkCategoryItemsIsExists['id'];
			//echo $categoryItem['parent_id'] . ': category item is allready saved.' . PHP_EOL;
		} else {
			//echo $categoryItem['parent_id'] . ': category item is saved.' . PHP_EOL;
			unset($categoryItem['id']);
			$categoryItemIdDatabase = DatabaseSave::save('categories_items', $categoryItem);
		}
		
	}
}