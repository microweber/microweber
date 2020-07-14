<?php
namespace MicroweberPackages\Utils\BackupManager\Readers;

class DefaultReader
{

	public $file;

	public function __construct($file)
	{
		$this->file = $file;
	}
}