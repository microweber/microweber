<?php 
namespace Microweber\Utils\Backup\Traits;

use Microweber\Utils\Backup\DatabaseSave;

trait DatabaseCustomFieldsWriter {
	
	private function _getCustomFields($itemId) {
		
		if (!isset($this->content['custom_fields'])) {
			return;
		}
		
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
	
	private function _saveCustomFields($savedItem) {
		
		// Get custom fields from file export
		$customFields = $this->_getCustomFields($savedItem['itemIdDatabase']);
		
		if (!empty($customFields)) {
			foreach ($customFields as $customField) {
				$this->_saveCustomField($customField, $savedItem['itemIdDatabase']);
			}
		}
	}
	
	private function _saveCustomField($customField, $itemIdDatabase) {
		
		// New rel id
		$customField['rel_id'] = $itemIdDatabase;
		
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
			$customFieldIdDatabase = $checkCustomFieldIsExists['id'];
			//echo $customField['name'] . ': Custom field is allready saved.' . PHP_EOL;
		} else {
			//echo $customField['name'] .': Custom field is saved.' . PHP_EOL;
			$customFieldIdDatabase = DatabaseSave::save('custom_fields', $customField);
		}
		
		$this->_saveCustomFieldValues($customField, $customFieldIdDatabase);
		
	}
	
	private function _saveCustomFieldValues($customField, $customFieldIdDatabase) {
		
		$customFieldValues = $this->_getCustomFieldValues($customFieldIdDatabase);
		
		foreach($customFieldValues as $customFieldValue) {
			
			// New field id
			$customFieldValue['custom_field_id'] = $customFieldIdDatabase;
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
				$customFieldValueIdDatabase = $checkCustomFieldValueIsExists['id'];
				//echo 'Custom field value is allready saved.' . PHP_EOL;
			} else {
				//echo 'Custom field value is saved.' . PHP_EOL;
				$customFieldValueIdDatabase = DatabaseSave::save('custom_fields_values', $customFieldValue);
			}
			
		}
	}
}