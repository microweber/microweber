<?php
namespace Modules\Restore\Formats;

class XmlReader extends DefaultReader
{
    public function readData()
	{
		// LIBXML_NONET blocks network access during parsing (defence-in-depth
		// against XXE/SSRF via external entities), matching the WordPressMigration
		// importers. External-entity substitution is already off by default on
		// libxml >= 2.9 (PHP 8.4), and we never pass LIBXML_NOENT.
		$xml = simplexml_load_file($this->file, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA);
		$xml = json_decode(json_encode($xml), true);

        return array("content"=>$xml);
	}

}
