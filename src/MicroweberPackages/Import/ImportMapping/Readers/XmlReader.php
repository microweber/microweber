<?php

namespace MicroweberPackages\Import\ImportMapping\Readers;

class XmlReader implements iReader
{
    public $html = [];

    public function printHtmlMapping($content)
    {

        $dataX = $this->readXml($content);

        $html = $this->view_r([
            $dataX
        ]);

        return $html;
    }

    public function readContent($content)
    {
        $data = $this->readXml($content);

        $mapFields = [];

        // Google feed
        if (isset($data['rss']['channel']['item'][0])) {
            if (isset($data['rss']['channel']['item'][0])) {
                foreach ($data['rss']['channel']['item'] as $item) {
                    $mapFieldsItem = ItemMapReader::getMapping($item);
                    $mapFields = array_merge($mapFields, $mapFieldsItem);
                }
            }
        }

        return [
            'map_fields' => $mapFields,
            'data' => $data
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

    private function domToArray($root)
    {

        $result = array();

        if ($root->hasChildNodes()) {
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


    private function view_r($array)
    {
        $html = "<div class='tags'>";

        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {

                    if (!is_numeric($key)) {
                        if(is_array($value)) {
                            if (isset($array['rss']['channel']['item'])) {
                                $value['channel'] = [];
                                $value['channel']['item'][] = $array['rss']['channel']['item'][0];
                            }
                        }
                    }

                    if ($key) {
                        $html .= $this->openKeyTag($key);
                    }

                    $html .= $this->view_r($value);

                    if ($key) {
                        $html .= $this->closKeyTag($key);
                    }

                } else {
                    if (!is_numeric($key)) {
                        $html .= "<table class='tag_key'>";
                        $html .= "<tr>";
                        $html .= "<td class='tag_value'>&lt;$key&gt;";
                        $html .=  $value;
                        $html .= "&lt;/$key&gt;</td>";
                        $html .= "<td class='tag_select'>".$this->dropdownSelect()."</td>";
                        $html .= "</tr>";
                        $html .= "</table>";
                    } else {
                        $html .= "<span class='tag_value'>" . $value . "</span>";
                        break;
                    }
                }
            }
        }

        $html .= "</div>";

        return $html;
    }

    private function dropdownSelect()
    {
        $html = '
            <select class="form-control">
                 <option name="title">Title</option>
                <option name="description">Description</option>
                <option name="images">Images</option>
                <option name="price">Price</option>
                <option name="sku">SKU</option>
            </select>
        ';
        return $html;
    }


    private function openKeyTag($name)
    {
        $html = PHP_EOL;
        $html .= '<div class="tag_key">&lt;' . $name . '&gt;</div>';
        $html .= PHP_EOL;

        return $html;
    }

    private function closKeyTag($name)
    {
        $html = PHP_EOL;
        $html .= '<div class="tag_key">&lt;/' . $name . '&gt;</div>';
        $html .= PHP_EOL;

        return $html;
    }
}
