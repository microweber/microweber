<?php
namespace MicroweberPackages\Import\Formats;

class DefaultReader
{

	public $file;

	public function __construct($file)
	{
		$this->file = $file;
	}
}
