<?php
namespace MicroweberPackages\Import\Traits;

use MicroweberPackages\Import\DatabaseSave;
use function db_get;

trait DatabaseContentFieldsWriter {

	private function _getContentFieldDatabase($item) {

		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['field'] = $item['field'];
		$dbSelectParams['rel_type'] = $item['rel_type'];
		$dbSelectParams['rel_id'] = $item['rel_id'];

		return db_get('content_fields', $dbSelectParams);
	}

	private function _saveContentField($item) {

		// Save new item
		$saveNewItem = $item;

		// Get content for menu
		$content = $this->_getContentById($item['rel_id']);
		if (!empty($content)) {
			$contentDatabase = $this->_getContentDatabase($content);
			if (! empty($contentDatabase)) {
				$saveNewItem['rel_id'] = $contentDatabase['id'];
			}
		}

		// Save menu item
        if (!empty($saveNewItem)) {
            unset($saveNewItem['id']);
            $itemDatabase = $this->_getContentFieldDatabase($saveNewItem);
            if (empty($itemDatabase)) {
                return DatabaseSave::save('content_fields', $saveNewItem);
            }
            return $itemDatabase['id'];
        }
	}
}
