<?php
namespace Microweber\Utils\Backup\Traits;

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

	private function _fixRelations($item, $itemIdDatabase)
	{
		
		$relation = $this->_getRelation($item);
		if (empty($relation)) {
			return;
		}
		
		$relation['rel_type'] = $item['rel_type'];

		if (! empty($relation)) {

			$relationDatabase = $this->_getRelationDatabase($relation);
			if (!empty($relationDatabase)) {
				
				$item['rel_id'] = $relationDatabase['id'];
				$item['id'] = $itemIdDatabase;
				db_save($item['save_to_table'], $item);
				
				echo 'Relation is fixed.' . PHP_EOL;
				
			}
		}
	}
}
