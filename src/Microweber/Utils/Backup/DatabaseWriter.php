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
	
	private function _getCustomFields($itemId) {
		
		$customFields = array();
		foreach($this->content['custom_fields'] as $dataItem) {
			if ($dataItem['rel_id'] == $itemId) {
				$customFields[] = $dataItem;
			}
		}
		
		return $customFields;
	}
	
	private function _getCustomFieldValues($customFieldId) {
		
		$customFieldValues = array();
		foreach($this->content['custom_fields_values'] as $dataItem) {
			if ($dataItem['custom_field_id'] == $customFieldId) {
				$customFieldValues[] = $dataItem;
			}
		}
		
		return $customFieldValues;
	}
	
	private function _saveItem($table, $item) {
		
		$item = $this->_fixItemEncoding($item);
		
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['title'] = $item['title'];
		
		$checkItemIsExists = db_get($table, $dbSelectParams);
		
		if ($checkItemIsExists) {
			$itemId = $checkItemIsExists['id'];
			echo 'Item is allready saved.' . PHP_EOL;
		} else {
			echo 'Item is saved.' . PHP_EOL;
			$item = $this->_unsetItemFields($item);
			$itemId = db_save($table, $item);
		}
		
		$this->_saveCustomFields($item, $itemId);
	}
	
	private function _saveCustomFields($item, $itemId) {
		
		// Get custom fields from file export
		$customFields = $this->_getCustomFields($itemId);
		
		if (!empty($customFields)) {
			foreach ($customFields as $customField) {
				$this->_saveCustomField($customField, $itemId);
			}
		}
	}
	
	private function _saveCustomField($customField, $itemId) {
		
		// New rel id
		$customField['rel_id'] = $itemId;
		
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['type'] = $customField['type'];
		$dbSelectParams['name'] = $customField['name'];
		$dbSelectParams['name_key'] = $customField['name_key'];
		$dbSelectParams['rel_id'] = $customField['rel_id'];
		
		$checkCustomFieldIsExists = db_get('custom_fields', $dbSelectParams);
		
		if ($checkCustomFieldIsExists) {
			$customFieldId = $checkCustomFieldIsExists['id'];
			echo 'Custom field is allready saved.' . PHP_EOL;
		} else {
			echo 'Custom field is saved.' . PHP_EOL;
			$customFieldId = db_save('custom_fields', $customField);
		}
		
		$this->_saveCustomFieldValues($customField, $customFieldId);
		
	}
	
	private function _saveCustomFieldValues($customField, $customFieldId) {
		
		$customFieldValues = $this->_getCustomFieldValues($customFieldId);
		
		foreach($customFieldValues as $customFieldValue) {
			
			// Fix encoding
			$customFieldValue = $this->_fixItemEncoding($customFieldValue);
			
			// New field id
			$customFieldValue['custom_field_id'] = $customFieldId;
			$customFieldValue = $this->_unsetItemFields($customFieldValue);
			
			
			$dbSelectParams = array();
			$dbSelectParams['no_cache'] = true;
			$dbSelectParams['limit'] = 1;
			$dbSelectParams['single'] = true;
			$dbSelectParams['do_not_replace_site_url'] = 1;
			$dbSelectParams['custom_field_id'] = $customFieldValue['custom_field_id'];
			$dbSelectParams['value'] = $customFieldValue['value'];
			
			$checkCustomFieldValueIsExists = db_get('custom_fields_values', $dbSelectParams);
			if ($checkCustomFieldValueIsExists) {
				$customFieldValueId = $checkCustomFieldValueIsExists['id'];
				echo 'Custom field value is allready saved.' . PHP_EOL;
			} else {
				echo 'Custom field value is saved.' . PHP_EOL;
				$customFieldValueId = db_save('custom_fields_values', $customFieldValue);
			}
			
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
