<?php
namespace Microweber\Utils\Backup\Readers;

$dir = __DIR__;
$dir = str_replace('Backup\Readers', '', $dir);
$dir = str_replace('Backup/Readers', '', $dir);

include_once $dir . 'lib/json-machine/vendor/autoload.php';

use JsonMachine\JsonMachine;

class JsonReader extends DefaultReader
{

	public function readData()
	{
		$readyJson = array();

		$json = JsonMachine::fromFile($this->file);

		foreach ($json as $jsonKey => $jsonValue) {
			$readyJson[$jsonKey] = $jsonValue;
		}

		return $readyJson;
	}
}