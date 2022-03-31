<?php

namespace MicroweberPackages\Import\ImportMapping\Readers;

class XmlReader implements iReader
{
    public function printHtmlMapping($data)
    {
        $data = $this->readContent($data);

       // $html = $this->printHtmlArray($data['data']['rss']['channel']['item']);
        $html = $this->printHtmlArray($data['data']);

        dd($html);
    }

    public function printHtmlArray($data)
    {
        $html = '';
        foreach ($data as $key=>$value) {
            $tag = $key;
            $html .= PHP_EOL . '<'.$tag.'>';
            if (is_array($value)) {
                $newValue = $this->printHtmlArray($value);
                if (empty($newValue)) {
                  
                }
                $html .= PHP_EOL . $newValue;
            } else if (is_string($value)) {
                $html .= $value;
            }
            $html .= '</'.$tag.'>' . PHP_EOL;
        }
        return $html;
    }

    public function readContent($content)
    {
        $data = $this->readXml($content);

        $mapFields = [];

        // Google feed
        if (isset($data['rss']['channel']['item'][0])) {
            if(isset($data['rss']['channel']['item'][0])) {
                foreach ($data['rss']['channel']['item'] as $item) {
                    $mapFieldsItem = ItemMapReader::getMapping($item);
                    $mapFields = array_merge($mapFields,$mapFieldsItem);
                }
            }
        }

        return [
            'map_fields'=>$mapFields,
            'data'=>$data
        ];
    }

    private function readXml($content)
    {
        $previousValue = libxml_use_internal_errors(true);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->loadXml($content);

        libxml_use_internal_errors($previousValue);

        if (libxml_get_errors()) {
            return [];
        }

        return $this->domToArray($dom);
    }

    private function domToArray($root) {

        $result = array();

        if ($root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if (in_array($child->nodeType,[XML_TEXT_NODE,XML_CDATA_SECTION_NODE])) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['_value']
                        : $result;
                }

            }
            $groups = array();
            foreach ($children as $child) {
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

}
