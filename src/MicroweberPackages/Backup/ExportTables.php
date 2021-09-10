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

	public function removeItemFromTable($table, $itemId) {

	    if (isset($this->tablesMap[$table])) {
            $newMap = [];
            foreach ($this->tablesMap[$table] as $mapItemId) {
                if ($mapItemId == $itemId) {
                    continue;
                }
                $newMap[] = $mapItemId;
            }
            $this->tablesMap[$table] = $newMap;
        }

	    if (isset($this->tables[$table])) {
            $newItems = [];
	        foreach ($this->tables[$table] as $item) {
	            if (isset($item['id']) && $item['id'] == $itemId) {
	                continue;
                }
	            $newItems[] = $item;
            }
	        $this->tables[$table] = $newItems;
        }
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
