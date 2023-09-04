<?php
namespace MicroweberPackages\Import\Traits;

use MicroweberPackages\Import\DatabaseSave;
use function db_get;

trait DatabaseContentDataWriter
{

	private function _getContentDataDatabase($item)
	{
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['field_name'] = $item['field_name'];
		$dbSelectParams['field_value'] = $item['field_value'];
		$dbSelectParams['rel_id'] = $item['rel_id'];

		return db_get('content_data', $dbSelectParams);
	}

	private function _saveContentData($item)
	{
		// Save new item
		$saveNewContentData = $item;

		// Get content for menu
		if ($item['rel_type'] == 'content') {
			$content = $this->_getContentById($item['rel_id']);
			if (! empty($content)) {
				$contentDatabase = $this->_getContentDatabase($content);
				if (! empty($contentDatabase)) {
					$saveNewContentData['rel_id'] = $contentDatabase['id'];
					$saveNewContentData['content_id'] = $contentDatabase['id'];
				}
			}
		}

		// Save menu item
		if (! empty($saveNewContentData)) {
			unset($saveNewContentData['id']);
			$itemDatabase = $this->_getContentDataDatabase($saveNewContentData);
			if (empty($itemDatabase)) {
				return DatabaseSave::save('content_data', $saveNewContentData);
			}
            return $itemDatabase['id'];
		}
	}
}
