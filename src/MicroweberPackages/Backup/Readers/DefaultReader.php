<?php
namespace MicroweberPackages\Backup\Readers;

class DefaultReader
{

	public $file;

	public function __construct($file)
	{
		$this->file = $file;
	}
}