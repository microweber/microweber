<?php
namespace Microweber\Utils\Backup\Readers;

use Microweber\Utils\Backup\Exporters\SpreadsheetHelper;

class XlsxReader extends DefaultReader
{

	public function readData()
	{
		$tables = $this->_getTableList();
		$data = $this->_readSpreadsheet();

		if (isset($data[0]['rel_type'])) {
			$data = array(
				$data[0]['rel_type'] => $data
			);
		}

		$filename = basename($this->file);
		$fileExtension = get_file_extension($this->file);
		$importToTable = str_replace('.' . $fileExtension, false, $filename);

		$foundedTable = false;
		foreach ($tables as $table) {
			if (strpos($importToTable, $table) !== false) {
				$foundedTable = $table;
				break;
			}
		}

		if ($foundedTable) {
			return array(
				$foundedTable => $data
			);
		} else {
			return false;
		}
	}

	private function _getTableList()
	{
		$readyTables = array();

		$tables = mw()->database_manager->get_tables_list();
		foreach ($tables as $table) {
			$readyTables[] = str_replace(mw()->database_manager->get_prefix(), false, $table);
		}

		return $readyTables;
	}

	private function _readSpreadsheet()
	{
		$data = array();

		$rows = SpreadsheetHelper::newSpreadsheet($this->file)->getRows();

		$dataHeader = $rows[0];
		unset($rows[0]);

		foreach ($rows as $row) {

			$readyRow = array();
			foreach ($row as $rowKey => $rowValue) {
				$readyRow[$dataHeader[$rowKey]] = $rowValue;
			}

			$data[] = $readyRow;
		}

		return $data;
	}
}