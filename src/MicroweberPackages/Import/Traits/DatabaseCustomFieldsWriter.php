<?php
namespace MicroweberPackages\Import\Traits;

use MicroweberPackages\Backup\Traits\unknown;
use MicroweberPackages\Import\DatabaseSave;
use function db_get;

trait DatabaseCustomFieldsWriter {

	/**
	 * Save custom field to database.
	 * @param array $item
	 */
	private function _saveCustomField($item) {

		// Save new custom field
		$saveNewCustomField = $item;

		// Get content for custom field
		if ($item['rel_type'] == 'content') {
			$content = $this->_getContentById($item['rel_id']);
			if (!empty($content)) {
				$contentDatabase = $this->_getContentDatabase($content);
				if (! empty($contentDatabase)) {
					$saveNewCustomField['rel_id'] = $contentDatabase['id'];
				}
			}
		}

		// Save menu item
		if (!empty($saveNewCustomField)) {
			unset($saveNewCustomField['id']);
			$itemDatabase = $this->_getCustomFieldDatabase($saveNewCustomField);
			if (empty($itemDatabase)) {
				$customFieldIdDatabase = DatabaseSave::save('custom_fields', $saveNewCustomField);
			} else {
				$customFieldIdDatabase = $itemDatabase['id'];
			}
		}

		$this->_saveCustomFieldValues($item, $customFieldIdDatabase);

		return $customFieldIdDatabase;
	}


	private function _saveCustomFieldValues($item, $customFieldIdDatabase) {

		$customFieldValues = $this->_getCustomFieldValues($item['id']);

		if (empty($customFieldValues)) {
			return;
		}

		foreach($customFieldValues as $customFieldValue) {

			// New field id
			$customFieldValue['custom_field_id'] = $customFieldIdDatabase;
			$customFieldValue = $this->_unsetItemFields($customFieldValue);

			$itemDatabase = $this->_getCustomFieldValueDatabase($customFieldValue);
			if (empty($itemDatabase)) {
				$customFieldValueIdDatabase = DatabaseSave::save('custom_fields_values', $customFieldValue);
			} else {
				$customFieldValueIdDatabase = $itemDatabase['id'];
			}

		}
	}

	/**
	 * Get local import custom field values
	 * @param int $customFieldId
	 * @return array[]
	 */
	private function _getCustomFieldValues($customFieldId) {

		$customFieldValues = array();
		foreach($this->content['custom_fields_values'] as $dataItem) {
			if ($dataItem['custom_field_id'] == $customFieldId) {
				$customFieldValues[] = $dataItem;
			}
		}

		return $customFieldValues;
	}

	private function _getCustomFieldValueDatabase($item) {

		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['custom_field_id'] = $item['custom_field_id'];
		$dbSelectParams['value'] = $item['value'];

		return db_get('custom_fields_values', $dbSelectParams);
	}

	/**
	 * Get custom field form database by item details.
	 * @param array $item
	 * @return mixed|void|boolean|array|unknown|number|mixed[]|void[]
	 */
	private function _getCustomFieldDatabase($item) {

		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['type'] = $item['type'];
		$dbSelectParams['name'] = $item['name'];
		$dbSelectParams['name_key'] = $item['name_key'];
		$dbSelectParams['rel_id'] = $item['rel_id'];

		return db_get('custom_fields', $dbSelectParams);
	}
}
