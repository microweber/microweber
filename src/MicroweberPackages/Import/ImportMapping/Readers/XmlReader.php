<?php

namespace MicroweberPackages\Import\ImportMapping\Readers;

class XmlReader implements iReader
{
    public $html = [];

    public function printHtmlMapping($content)
    {

        $dataX = $this->readXml($content);
        $data = [];

        $data['rss']['channel']['products'][] = [
            'title' => 'Test',
            'price' => '13',
        ];
        $data['rss']['channel']['products'][] = [
            'title' => 'Test3',
            'price' => '113',
        ];
        $data['rss']['channel']['products'][] = [
            'title' => 'Test2',
            'price' => '133',
        ];

      /*  $dataX = [];
        $dataX['categories'][] = ['title'=>1];
        $dataX['categories'][] = ['title'=>2];
        $dataX['categories'][] = ['title'=>3];*/

        //dd($dataX);
        $html = $this->view_r([
            $dataX
        ], "", true, "class", "background: green;font-size: 20px;color: #fff;");


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


    private function view_r($arr, $tab = "", $encode = false, $focus_attr = null, $style = null, $lastKey=null)
    {
        $return_debug = "<div class='tags'>";

        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                if (is_array($value)) {

                    $nextKey = null;
                    if (!is_numeric($key)) {
                        if(is_array($value)) {
                            $nextKey = $key;
                        }
                    }

                    if ($lastKey) {
                        $return_debug .= "<div class='tag_key'>&lt;$lastKey&gt;</div>";
                    }

                    $return_debug .= $this->view_r($value, $tab . "", $encode, $focus_attr, $style, $nextKey);

                    if ($lastKey) {
                        $return_debug .= "<div class='tag_key'>&lt;/$lastKey&gt;</div>";
                    }

                } else {
                    if (!is_numeric($key)) {
                        $return_debug .= "<table class='tag_key'>";
                        $return_debug .= "<tr>";
                        $return_debug .= "<td class='tag_value'>&lt;$key&gt;";
                        $return_debug .=  $value;
                        $return_debug .= "&lt;/$key&gt;</td>";
                        $return_debug .= "<td class='tag_select'>".$this->dropdownSelect()."</td>";
                        $return_debug .= "</tr>";
                        $return_debug .= "</table>";
                    } else {
                        $return_debug .= "<span class='tag_value'>" . $value . "</span>";
                        break;
                    }
                }
            }
        }

        $return_debug .= "</div>";
        return $return_debug;
    }

    private function dropdownSelect()
    {
        $html = '
            <select class="form-control">
                <option>Select</option>
            </select>
        ';
        return $html;
    }


    private function openTrTdTag($name)
    {
        $html = PHP_EOL;
        $html .= '&lt;' . $name . '&gt;';
        $html .= PHP_EOL;

        return $html;
    }

    private function closeTrTdTag($name)
    {
        $html = PHP_EOL;
        $html .= '&lt;/' . $name . '&gt;';
        $html .= PHP_EOL;

        return $html;
    }
}
