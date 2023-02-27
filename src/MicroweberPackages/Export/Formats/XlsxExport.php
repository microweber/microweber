<?php
namespace MicroweberPackages\Export\Formats;

use MicroweberPackages\Export\Formats\Helpers\SpreadsheetHelper;

class XlsxExport extends DefaultExport
{
	/**
	 * The type of export
	 * @var string
	 */
	public $type = 'xlsx';

	public function start()
	{
		$exportedFiles = array();

		if (!empty($this->data)) {
			foreach($this->data as $tableName=>$exportData) {

				if (empty($exportData)) {
					continue;
				}

				$xlsxFileName = $this->_generateFilename($tableName);

                if (!$this->overwrite) {
                    if (is_file($xlsxFileName['filepath'])) {
                        $exportedFiles[] = $xlsxFileName;
                        continue;
                    }
                }

                $rowKeys = [];
                foreach ($exportData as $exportItem) {
                    foreach ($exportItem as $key=>$value) {
                        $rowKeys[$key] = $key;
                    }
                }
                $rowKeys = array_keys($rowKeys);

                $exportDataFilled = [];
                foreach ($exportData as $exportItem) {
                    foreach ($rowKeys as $rowKey) {
                        $exportItemFilled[$rowKey] = ' ';
                    }
                    foreach ($exportItem as $key=>$value) {
                        $exportItemFilled[$key] = $value;
                    }
                    $exportDataFilled[] = $exportItemFilled;
                }

				$spreadsheet = SpreadsheetHelper::newSpreadsheet();
				$spreadsheet->addRow($rowKeys);
				$spreadsheet->addRows($exportDataFilled);
				$spreadsheet->save($xlsxFileName['filepath']);

				$exportedFiles[] = $xlsxFileName;
			}
		}

		return array("files"=>$exportedFiles);
	}
}
