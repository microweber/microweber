<?php
namespace Microweber\Utils\Backup\Exporters;

class JsonExport extends DefaultExport
{
	public function start() {
		return json_encode($this->data, JSON_PRETTY_PRINT);
	}
}