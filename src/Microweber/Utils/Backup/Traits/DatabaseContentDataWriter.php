<?php 
namespace Microweber\Utils\Backup\Traits;

trait DatabaseContentDataWriter {
	
	private function _getContentData($itemId) {
		
		if (!isset($this->content['content_data'])) {
			return;
		}
		
		$contentData = array();
		foreach($this->content['content_data'] as $dataItem) {
			if ($dataItem['rel_id'] == $itemId) {
				$contentData[] = $dataItem;
			}
		}
		
		return $contentData;
	}

	private function _saveContentData($itemId) {
		
		// Get content data from file export
		$contentData = $this->_getContentData($itemId);
		
		if (!empty($contentData)) {
			foreach ($contentData as $singleContentData) {
				$this->_saveSingleContentData($singleContentData, $itemId);
			}
		}
	}
	
	private function _saveSingleContentData($singleContentData, $itemId) {
		
		// New rel id
		$singleContentData['rel_id'] = $itemId;
		
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['type'] = $singleContentData['field_name'];
		$dbSelectParams['name'] = $singleContentData['field_value'];
		$dbSelectParams['rel_id'] = $singleContentData['rel_id'];
		
		$checkContentDataIsExists = db_get('content_data', $dbSelectParams);
		
		if ($checkContentDataIsExists) {
			$contentDataId = $checkContentDataIsExists['id'];
			echo $singleContentData['field_name'] . ': Content data is allready saved.' . PHP_EOL;
		} else {
			echo $singleContentData['field_name'] . ': Content data is saved.' . PHP_EOL;
			$singleContentData = $this->_unsetItemFields($singleContentData);
			$contentDataId = db_save('content_data', $singleContentData);
		}
		
		/* 
		var_dump($contentDataId);
		var_dump($singleContentData);
		die(); */
	}
}