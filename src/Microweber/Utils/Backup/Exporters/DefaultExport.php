<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\Exporters\Interfaces\ExportInterface;

class DefaultExport implements ExportInterface {

	protected $data;

	public function __construct($data) {
		$this->data = $data;
	}

	public function start() {
		// start exporting
	}
	
}