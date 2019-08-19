<?php
namespace Microweber\Utils\Backup\Readers;

use Microweber\Utils\Backup\Exporters\SpreadsheetHelper;

class XlsxReader extends DefaultReader
{

	public function readData()
	{
		return $this->_readSpreadsheet();
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