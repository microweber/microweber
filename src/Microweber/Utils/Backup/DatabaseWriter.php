<?php
namespace Microweber\Utils\Backup;

class DatabaseWriter
{

	public $content;

	public function setContent($content)
	{
		$this->content = $content;
	}

	private function _getRelationship($item)
	{
		$relationship = array();
		foreach ($this->content[$item['rel_type']] as $dataItem) {
			if ($dataItem['id'] == $item['rel_id']) {
				$relationship = $dataItem;
			}
		}
		return $relationship;
	}
	
	private function _fixItemEncoding($item) {
		
		// Fix encoding
		array_walk_recursive($item, function (&$element) {
			if (is_string($element)) {
				$element = utf8_decode($element);
				$element = str_replace('Â ', ' ', $element);
				$element = str_replace("Â ", ' ', $element);
			}
		});
		
		return $item;
	}
	
	private function _unsetItemFields($item) {
		$unsetFields = array('id', 'rel_id', 'order_id', 'position');
		foreach($unsetFields as $field) {
			unset($item[$field]);
		}
		return $item;
	}
	
	private function _getCustomFields($item) {
		
		$customFields = array();
		foreach($this->content['custom_fields'] as $dataItem) {
			if ($dataItem['rel_id'] == $item['id']) {
				$customFields[] = $dataItem;
			}
		}
		
		return $customFields;
	}
	
	private function _saveItem($table, $item) {
		
		$item = $this->_fixItemEncoding($item);
		
		$customFields = $this->_getCustomFields($item);
		
		$dbSelectParams = array();
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['title'] = $item['title'];
		
		$checkItemIsExists = db_get($table, $dbSelectParams);
		
		if ($checkItemIsExists) {
			echo 'Item is allready saved.' . PHP_EOL;
		} else {
			echo 'Item is saved.' . PHP_EOL;
			$item = $this->_unsetItemFields($item);
			$save = db_save($table, $item);
		}
		
		if (!empty($customFields)) {
			foreach ($customFields as $customField) {
				$this->_saveCustomField($item, $customField);
			}
		}
		
	}
	
	private function _saveCustomField($item, $customField) {
		
		// New rel id
		$customField['rel_id'] = $item['id'];
		
		$dbSelectParams = array();
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['type'] = $customField['type'];
		$dbSelectParams['name'] = $customField['name'];
		$dbSelectParams['name_key'] = $customField['name_key'];
		
		$checkCustomFieldIsExists = db_get('custom_fields', $dbSelectParams);
		
		if ($checkCustomFieldIsExists) {
			echo 'Custom field is allready saved.' . PHP_EOL;
		} else {
			echo 'Custom field is saved.' . PHP_EOL;
			$save = db_save('custom_fields', $customField);
		}
		
	}
	
	private function _saveItemWithRelationship($table, $item) {
		
		$relationship = $this->_getRelationship($item);

		$item = $this->_fixItemEncoding($item);
		$item = $this->_unsetItemFields($item);
		
		$item['allow_html'] = true;
		$item['allow_scripts'] = true;
		$item['is_deleted'] = false;
		
		$save = db_save($table, $item);
		
	}
	
	public function runWriter()
	{
		$importTables = array('content');
		
		// All db tables
		foreach ($importTables as $table) {
			foreach ($this->content[$table] as $item) {
				
				if (isset($item['rel_type']) && isset($item['rel_id'])) {				
					$this->_saveItemWithRelationship($table, $item);
					continue;
				}
				
				$this->_saveItem($table, $item);
			}
		}
	}
}
