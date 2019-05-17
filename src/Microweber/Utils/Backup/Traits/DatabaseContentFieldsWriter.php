<?php 
namespace Microweber\Utils\Backup\Traits;

trait DatabaseContentFieldsWriter {
	
	private function _getContentFields($itemId) {
		
		$contentFields = array();
		foreach($this->content['content_fields'] as $dataItem) {
			if ($dataItem['rel_id'] == $itemId) {
				$contentFields[] = $dataItem;
			}
		}
		
		return $contentFields;
	}
	
}