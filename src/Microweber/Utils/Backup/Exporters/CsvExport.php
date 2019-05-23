<?php
namespace Microweber\Utils\Backup\Exporters;

class CsvExport extends DefaultExport
{

	public function start()
	{
		return $this->array2csv($this->data);
	}

	public function array2csv($array)
	{
		$csv = array();
		foreach ($array as $item) {
			if (is_array($item)) {
				$csv[] = $this->array2csv($item);
			} else {
				$csv[] = $item;
			}
		}
		return implode(',', $csv);
	}
}