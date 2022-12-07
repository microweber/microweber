<?php
namespace MicroweberPackages\Import\Formats;

class XmlReader extends DefaultReader
{
    public function readData()
	{
		$xml = simplexml_load_file($this->file);
		$xml = json_decode(json_encode($xml), true);

        return array("content"=>$xml);
	}

}
