<?php
namespace MicroweberPackages\Backup\Readers;

use MicroweberPackages\Backup\Readers\Vendors\ShopifyReader;

class XmlReader extends DefaultReader
{

    public function readData()
	{
	    $shopify = new ShopifyReader();
        $xml = $shopify->read($this->file);

	    return $xml;

//		$xml = simplexml_load_file($this->file);
//		$xml = json_decode(json_encode($xml), true);

		if (isset($xml['channel']['item'])) {
			$xml = $this->readWordpress();
		}

        if (isset($xml[0]['id'])) {
            return array("content"=>$xml);
        }

		return $xml;
	}

}
