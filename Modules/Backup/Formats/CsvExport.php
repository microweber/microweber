<?php
namespace MicroweberPackages\Export\Formats;

class CsvExport extends DefaultExport
{

	/**
	 * The type of export
	 * @var string
	 */
	public $type = 'csv';

	public function start()
	{

		// $export = array();

		/* if (!empty($this->data)) {
			foreach ($this->data as $tableName=>$tableData) {
				foreach($tableData as $item) {
					$export[$tableName][] = $item;
				}
			}
		} */

		$exportedFiles = array();

		if (!empty($this->data)) {
			foreach($this->data as $tableName=>$exportData) {

				if (empty($exportData)) {
					continue;
				}

				$generateFileName = $this->_generateFilename($tableName);

				$csv = \League\Csv\Writer::createFromPath($generateFileName['filepath'], 'w');
				//$csv->setEncodingFrom('UTF-8');
				$csv->setOutputBOM(\League\Csv\Writer::BOM_UTF8);
				$csv->insertOne(array_keys(reset($exportData)));
				$csv->insertAll($exportData);

				$exportedFiles[] = $generateFileName;
			}
		}

		return array("files"=>$exportedFiles);
	}

	public function array2csv($data, $delimiter = ',', $enclosure = '"', $escape_char = "\\")
	{

		$f = fopen('php://memory', 'r+');
		foreach ($data as $item) {
			fputcsv($f, $item, $delimiter, $enclosure, $escape_char);
		}
		rewind($f);
		return stream_get_contents($f);

	}
}
