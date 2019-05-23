<?php
namespace Microweber\Utils\Backup\Readers;

class DefaultReader
{

	public $file;

	public function __construct($file)
	{

		if (! is_file($file)) {
			throw new \Exception('You have not provided a existing backup to restore.');
		}
		
		$this->file = $file;
	}
}