<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Exporters\JsonExport;
use Microweber\Utils\Backup\Exporters\CsvExport;
use Microweber\Utils\Backup\Exporters\XmlExport;

class Export
{

	public $type = 'json';

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getContent()
	{
		
		
		
		echo 33;
		die();
		switch ($this->type) {
			case 'json':
				$export = new JsonExport();
				return $export->start();
			case 'csv':
				$export = new CsvExport();
				return $export->start();
			case 'xml':
				$export = new XmlExport();
				return $export->start();
			default:
				return array(
					'error' => 'Export format not supported.'
				);
		}
	}
}