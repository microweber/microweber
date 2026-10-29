<?php
namespace Modules\Restore\Formats;

class XmlReader extends DefaultReader
{
    public function readData()
	{
		// LIBXML_NONET: XXE/SSRF defence-in-depth (no network during parse).
		$xml = simplexml_load_file($this->file, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA);
		$xml = json_decode(json_encode($xml), true);

        return array("content"=>$xml);
	}

}
