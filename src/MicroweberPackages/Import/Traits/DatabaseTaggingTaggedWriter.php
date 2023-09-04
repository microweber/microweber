<?php
namespace MicroweberPackages\Import\Traits;

use MicroweberPackages\Import\DatabaseSave;
use function db_get;

trait DatabaseTaggingTaggedWriter
{
	private function _getTagginTaggedDatabase($item) {

		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['tag_slug'] = $item['tag_slug'];
		$dbSelectParams['tag_name'] = $item['tag_name'];
		$dbSelectParams['taggable_type'] = $item['taggable_type'];
		$dbSelectParams['taggable_id'] = $item['taggable_id'];

		return db_get('tagging_tagged', $dbSelectParams);
	}

	private function _taggingTagged($item) {

		// Save new item
		$saveNewTaggingTagged = $item;

		// Its founded in import file database
		$content = $this->_getContentById($item['taggable_id']);
		if (!empty($content)) {
			$contentDatabase = $this->_getContentDatabase($content);

			if (! empty($contentDatabase)) {
				$saveNewTaggingTagged['taggable_id'] = $contentDatabase['id'];
			}
		}

		// Save item
		if (!empty($saveNewTaggingTagged)) {
			unset($saveNewTaggingTagged['id']);

			$itemDatabase = $this->_getTagginTaggedDatabase($saveNewTaggingTagged);

			if (empty($itemDatabase)) {
			return	DatabaseSave::save('tagging_tagged', $saveNewTaggingTagged);
			}
            return $itemDatabase['id'];
 		}
	}
}
