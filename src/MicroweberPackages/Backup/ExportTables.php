<?php
namespace MicroweberPackages\Backup;

class ExportTables
{

	public $tables;
	public $tablesMap;
	
	public function addItemToTable($table, $item)
	{
        foreach ($item as &$itemValue) {
            if (is_array($itemValue)) {
                $itemValue = json_encode($itemValue);
            }
        }

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

            if (!isset($item['id'])) {
                return;
            }

			$this->addItemToTable($table, $item);
		}
	}

	public function getAllTableItems()
	{
		return $this->tables;
	}
}