<?php
namespace MicroweberPackages\Import\Traits;

use MicroweberPackages\Import\DatabaseSave;
use function db_get;

trait DatabaseRelationWriter
{

	/**
	 * Get relationship
	 *
	 * @param array $item
	 * @return array
	 */
	private function _getRelation($item)
	{
		if (isset($item['rel_type']) && isset($this->content[$item['rel_type']])) {
			foreach ($this->content[$item['rel_type']] as $dataItem) {

				if (isset($item['rel_id']) && isset($dataItem['id']) && $dataItem['id'] == $item['rel_id']) {
					return $dataItem;
				}
			}
		}
	}

	private function _getRelationDatabase($item)
	{

		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['title'] = $item['title'];
		$dbSelectParams['created_at'] = $item['created_at'];

		return db_get($item['rel_type'], $dbSelectParams);
	}

	private function _fixRelations($savedItem)
	{
		$relation = $this->_getRelation($savedItem['item']);
		if (empty($relation)) {
			//echo 'No relation in this item.' . PHP_EOL;
			return;
		}

		$relation['rel_type'] = $savedItem['item']['rel_type'];
		$relation['save_to_table'] = $savedItem['item']['rel_type'];

		if (! empty($relation) && isset($relation['title'])) {

			$relationDatabase = $this->_getRelationDatabase($relation);
			if (empty($relationDatabase)) {
				// Save relation data if not exists
				$this->_saveItemDatabase($relation);
				// Get new relation
				$relationDatabase = $this->_getRelationDatabase($relation);
			}

			if (!empty($relationDatabase)) {

				$savedItem['item']['rel_id'] = $relationDatabase['id'];
				$savedItem['item']['id'] = $savedItem['itemIdDatabase'];

				DatabaseSave::save($savedItem['item']['save_to_table'], $savedItem['item']);

				// echo 'Relation is fixed.' . PHP_EOL;

			}
		}
	}
}
