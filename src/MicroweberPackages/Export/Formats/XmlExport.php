<?php
namespace MicroweberPackages\Export\Formats;

class XmlExport extends DefaultExport
{


	/**
	 * The type of export
	 * @var string
	 */
	public $type = 'xml';

	public function start()
	{
		$xmlString = $this->arrayToXml($this->data, new \SimpleXMLElement('<root/>'))->asXML();

		$xmlFileName = $this->_generateFilename();

		file_put_contents($xmlFileName['filepath'], $xmlString);

		return array("files"=>array($xmlFileName));
	}

	public function arrayToXml(array $arr, \SimpleXMLElement $xml)
	{
		foreach ($arr as $k => $v) {
			is_array($v) ? $this->arrayToXml($v, $xml->addChild($k)) : $xml->addChild($k, $v);
		}
		return $xml;
	}
}
