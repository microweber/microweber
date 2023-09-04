<?php
namespace MicroweberPackages\Import\Traits;

use MicroweberPackages\Import\DatabaseSave;
use function db_get;

trait DatabaseModuleWriter
{

	private function _getModuleDatabase($item)
	{
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

	private function _saveModule($item)
	{
		if (empty($item['rel_id'])) {
			return;
		}

		// Save menu item
		if (! empty($item)) {
			unset($item['id']);
			$itemDatabase = $this->_getModuleDatabase($item);
			if (empty($itemDatabase)) {
			return	DatabaseSave::save('media', $item);
			}
            return $itemDatabase['id'];
		}
	}
}
