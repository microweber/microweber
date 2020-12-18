<?php
namespace Microweber\Utils\Backup\Readers;

use Microweber\Utils\Backup\Exporters\SpreadsheetHelper;

class XlsxReader extends DefaultReader
{

	public function readData()
	{
		$data = $this->_readSpreadsheet();

        if (isset($data[0]['id'])) {
            return array("content"=>$data);
        }

        return $data;
	}

	private function _readSpreadsheet()
	{
		$data = array();

		$rows = SpreadsheetHelper::newSpreadsheet($this->file)->getRows();

		$dataHeader = $rows[0];
		unset($rows[0]);

		$i=0;
		foreach ($rows as $row) {

            $readyRow = array();
            foreach ($row as $rowKey => $rowValue) {
                $readyRow[$dataHeader[$rowKey]] = $rowValue;
            }

            if (!isset($readyRow['id'])) {
                $readyRow['id'] = $i;
            }

			$data[] = $readyRow;
            $i++;
		}
		
		return $data;
	}
}