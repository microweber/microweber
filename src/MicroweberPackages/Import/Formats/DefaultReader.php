<?php
namespace MicroweberPackages\Import\Formats;

class DefaultReader
{

	public $file;

	public function __construct($file = false)
	{
        if ($file) {
            $this->file = $file;
        }
	}
}
