<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers;

class XmlToArray implements iReader
{
    public function readXml($content)
    {
        $dom = $this->loadDom($content);

        return $this->domToArray($dom);
    }

    public function loadDom($content) {

        $previousValue = libxml_use_internal_errors(true);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->loadXml($content);

        libxml_use_internal_errors($previousValue);

        if (libxml_get_errors()) {
            return [];
        }
        return $dom;
    }

    private function domToArray($root)
    {
        $result = array();

        if (is_object($root) && $root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if (in_array($child->nodeType, [XML_TEXT_NODE, XML_CDATA_SECTION_NODE])) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['_value']
                        : $result;
                }

            }
            $groups = array();
            foreach ($children as $child) {

                if ($child->nodeName == '#comment') {
                    continue;
                }

                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = $this->domToArray($child);
                } else {
                    if (!isset($groups[$child->nodeName])) {
                        $result[$child->nodeName] = array($result[$child->nodeName]);
                        $groups[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = $this->domToArray($child);
                }
            }
        }
        return $result;
    }

    public function getArrayIterratableTargetKeys($array)
    {
        $tags = [];
        if (!empty($array)) {
            foreach ($array as $key=>$value) {
                if (is_array($value)) {
                    $recursive = $this->getArrayIterratableTargetKeys($value);
                    if (isset($recursive[0])) {
                        $tags[$key] = [];
                        continue;
                    }
                    $tags[$key] = $recursive;
                }
            }
        }

        return $tags;
    }
}
