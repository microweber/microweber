<?php
namespace Microweber\Utils\Backup\Exporters;

class XmlExport extends DefaultExport
{

	public function start()
	{
		return $this->arrayToXml($this->data, new \SimpleXMLElement('<root/>'))->asXML();
	}

	public function arrayToXml(array $arr, \SimpleXMLElement $xml)
	{
		foreach ($arr as $k => $v) {
			is_array($v) ? $this->arrayToXml($v, $xml->addChild($k)) : $xml->addChild($k, $v);
		}
		return $xml;
	}
}