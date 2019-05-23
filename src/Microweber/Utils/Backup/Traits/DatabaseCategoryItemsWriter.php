<?php 
namespace Microweber\Utils\Backup\Traits;

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
	
	private function _saveCategoriesItems($item, $itemId) {
		
		if (!isset($item['id'])) {
			return;
		}
		
		// Get content data from file export
		$categoriesItems = $this->_getCategoriesItems($item['id']);
		
		if (!empty($categoriesItems)) {
			foreach ($categoriesItems as $categoryItem) {
				$this->_saveCategoryItem($categoryItem, $itemId);
			}
		}
	}
	
	private function _getCategory($parentId) {
		
		if (!isset($this->content['categories'])) {
			return;
		}
		
		foreach($this->content['categories'] as $category) {
			if ($category['id'] == $parentId) {
				
				// Fix encoding
				$category =$this->_fixItemEncoding($category);
				
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
				}
			}
		}
	}
	
	private function _saveCategoryItem($categoryItem, $itemId) {
		
		$category = $this->_getCategory($categoryItem['parent_id']);
		
		// New parent id
		$categoryItem['parent_id'] = $category['id'];
		
		// New rel id
		$categoryItem['rel_id'] = $itemId;
		
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
			$categoryItemId = $checkCategoryItemsIsExists['id'];
			echo $categoryItem['parent_id'] . ': category item is allready saved.' . PHP_EOL;
		} else {
			echo $categoryItem['parent_id'] . ': category item is saved.' . PHP_EOL;
			unset($categoryItem['id']);
			$categoryItemId = db_save('categories_items', $categoryItem);
		}
		
	}
}