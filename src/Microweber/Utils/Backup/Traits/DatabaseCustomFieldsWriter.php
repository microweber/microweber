<?php 
namespace Microweber\Utils\Backup\Traits;

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
			echo $customField['name'] . ': Custom field is allready saved.' . PHP_EOL;
		} else {
			echo $customField['name'] .': Custom field is saved.' . PHP_EOL;
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
}