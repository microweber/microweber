<?php
namespace Microweber\Utils\Backup\Readers;

use Microweber\Utils\Backup\Readers\Vendors\WordpressReader;

class XmlReader extends DefaultReader
{
	use WordpressReader;

	public function readData()
	{
		$xml = simplexml_load_file($this->file, 'SimpleXMLElement', LIBXML_NOCDATA);
		$xml = json_decode(json_encode($xml), true);
		
		if (isset($xml['channel']['item'])) { 
			$xml = $this->readWordpress();
		}

        if (isset($xml[0]['id'])) {
            return array("content"=>$xml);
        }
		
		return $xml;
	}

}