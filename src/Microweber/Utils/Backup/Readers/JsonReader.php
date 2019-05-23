<?php
namespace Microweber\Utils\Backup\Readers;

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