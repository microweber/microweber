<?php

namespace MicroweberPackages\Import\ImportMapping;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use MicroweberPackages\Import\ImportMapping\Readers\ItemMapReader;

class HtmlDropdownMappingPreview
{
    public $content = [];
    public $contentParentTags = false;
    public $html = [];

    public function setContent($content)
    {
        $this->content = $content;
    }

   public function setContentParentTags($tag)
    {
        $this->contentParentTags = $tag;
    }

    public function render()
    {
        $content = $this->content;

        $firstItem = data_get($content, $this->contentParentTags . '.0');
        Arr::forget($content, $this->contentParentTags);
        data_fill($content, $this->contentParentTags . '.0', $firstItem);

        $html = $this->arrayPreviewInHtmlRecursive($content, $this->contentParentTags);

        return $html;
    }

    private function arrayPreviewInHtmlRecursive($array, $contentParentTags, $i=0, $lastKey = [])
    {
        $html = "<div class='tags'>";

        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {

                    if ($key) {
                        if (isset($value[0])) {
                            $html .= "<table class='tag_key'>";
                            $html .= "<tr>";
                            $html .= "<td class='tag_value'>".$this->openKeyTag($key)."</td>";
                            $html .= "<td class='tag_select'>".$this->dropdownIterratableSelect($key)."</td>";
                            $html .= "</tr>";
                            $html .= "</table>";
                        } else {
                            $html .= "<table class='tag_key'>";
                            $html .= "<tr>";
                            $html .= "<td class='tag_value'>".$this->openKeyTag($key)."</td>";
                            $html .= "<td></td>";
                            $html .= "</tr>";
                            $html .= "</table>";
                        }
                    }

                    $sendKey = [];
                    $sendKey['parent'] = $lastKey;
                    $sendKey['key'] = $key;

                    $html .= $this->arrayPreviewInHtmlRecursive($value, $contentParentTags, $i, $sendKey);
                    if ($key) {
                        $html .= $this->closKeyTag($key);
                    }

                } else {

                    if (isset($lastKey['key']) && is_numeric($lastKey['key'])) {
                        unset($lastKey['key']);
                    }

                    // If key is numeric this is iterratable
                    if (is_numeric($key)) {
                        $html .= "<span class='tag_value'>" . $value . "</span>";
                        break;
                    } else {
                        $html .= "<table class='tag_key'>";
                        $html .= "<tr>";
                        $html .= "<td class='tag_value'>&lt;$key&gt;";
                        $html .=  $value;
                        $html .= "&lt;/$key&gt;</td>";

                        $getParentMapKey = $this->getRecursiveKeysFromArray($lastKey, $key);
                        $getParentMapKey[] = $key;
                        $mapKey = implode('.', $getParentMapKey);

                        if (Str::startsWith($mapKey, $contentParentTags)) {
                            $html .= "<td class='tag_select'>" . $this->dropdownSelect($mapKey) . "</td>";
                        } else{
                            $html .= "<td></td>";
                        }

                        $html .= "</tr>";
                        $html .= "</table>";
                    }
                }
            }
        }

        $html .= "</div>";

        return $html;
    }

    private function getRecursiveKeysFromArray($array)
    {
        $newArray = array();
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array), \RecursiveIteratorIterator::SELF_FIRST) as $k => $v) {
            if ($k === 'key') {
                $newArray[] = $v;
            }
        }

        return $newArray;
    }

    private function dropdownIterratableSelect($mapKey)
    {
        $html = '<select class="form-control" name="map_iterratable['.$mapKey.']">';
        $html .= '<option value="variants">Tags</option>';
        $html .= '<option value="variants">Product Variants</option>';
        $html .= '</select>';

        return $html;
    }

    private function dropdownSelect($mapKey)
    {
        $selectOptions = [];
        $selectOptions[''] = [
            'name'=>'Select type',
            'selected'=>false,
        ];

        foreach (ItemMapReader::$itemTypes as $key=>$name) {

            $selected = false;
            foreach (ItemMapReader::$map[$key] as $itemMapKey) {
                if(stripos($mapKey, $itemMapKey) !== false) {
                    $selected = true;
                    break;
                }
            }

            $selectOptions[$key] = [
                'name'=>$name,
                'map_key'=>$mapKey,
                'selected'=>$selected,
            ];
        }

        $html = '<select class="form-control" name="map['.$mapKey.']">';

        foreach ($selectOptions as $name => $option) {

            if (!isset($option['name'])) {
                continue;
            }

            $selected = '';
            if ($option['selected']) {
                $selected = 'selected';
            }

            $html .= '<option '.$selected.' value="'.$name.'">'.$option['name'].'</option>';
        }

        $html .= '</select>';

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
