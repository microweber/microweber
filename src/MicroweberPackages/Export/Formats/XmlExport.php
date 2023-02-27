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
        $xmlString = $this->array2xml($this->data);

        $xmlFileName = $this->_generateFilename();

        file_put_contents($xmlFileName['filepath'], $xmlString);

        return array("files" => array($xmlFileName));
    }

    public function array2xml($data, $name = 'root', &$doc = null, &$node = null)
    {
        if ($doc == null) {
            $doc = new \DOMDocument('1.0', 'UTF-8');
            $doc->formatOutput = TRUE;
            $node = $doc;
        }

        if (is_array($data)) {
            foreach ($data as $var => $val) {
                if (is_numeric($var)) {
                    $this->array2xml($val, $name, $doc, $node);
                } else {
                    if (!isset($child)) {
                        $child = $doc->createElement($name);
                        $node->appendChild($child);
                    }

                    $this->array2xml($val, $var, $doc, $child);
                }
            }
        } else {
            $child = $doc->createElement($name);
            $node->appendChild($child);
            $textNode = $doc->createTextNode($data);
            $child->appendChild($textNode);
        }

        if ($doc == $node) return $doc->saveXML();
    }
}
