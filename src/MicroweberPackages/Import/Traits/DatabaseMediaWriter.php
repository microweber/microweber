<?php
namespace MicroweberPackages\Import\Traits;

use MicroweberPackages\Import\DatabaseSave;
use function db_get;

trait DatabaseMediaWriter {

	private function _getMediaDatabase($item) {

		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['media_type'] = $item['media_type'];
		$dbSelectParams['filename'] = $item['filename'];
		$dbSelectParams['rel_type'] = $item['rel_type'];
		$dbSelectParams['rel_id'] = $item['rel_id'];

		return db_get('media', $dbSelectParams);
	}

	private function _saveMedia($item) {




        // Save new item
		$saveNewMedia = $item;


		// Get content for menu
		if ($item['rel_type'] == 'content') {
			$content = $this->_getContentById($item['rel_id']);
			if (!empty($content)) {
				$contentDatabase = $this->_getContentDatabase($content);
				if (! empty($contentDatabase)) {
					$saveNewMedia['rel_id'] = $contentDatabase['id'];
				}
			}
		}


		// Save media item
		if (!empty($saveNewMedia)) {
			unset($saveNewMedia['id']);
			$itemDatabase = $this->_getMediaDatabase($saveNewMedia);

			if (empty($itemDatabase)) {
			$mediaID =	DatabaseSave::save('media', $saveNewMedia);

            return $mediaID;
			}

            return $itemDatabase['id'];
		}


	}
}
