<?php
namespace Microweber\Utils\Backup;

class ExportTables
{

	public $tables;
	public $tablesMap;
	
	public function addItemToTable($table, $item)
	{
		if (isset($this->tablesMap[$table])) {
			if (in_array($item['id'], $this->tablesMap[$table])) {
				return;
			}
		}

		$this->tablesMap[$table][] = $item['id'];
		$this->tables[$table][] = $item;
	}

	public function addItemsToTable($table, $items)
	{
		if (empty($items)) {
			return;
		}

		foreach ($items as $item) {
			$this->addItemToTable($table, $item);
		}
	}

	public function getAllTableItems()
	{
		return $this->tables;
	}
}