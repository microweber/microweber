<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapCategoryReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader;

trait HtmlDropdownMappingRecursiveTable
{
    public $content = [];
    public $contentParentTags = false;
    public $html = [];
    public $importTo;

    public function setImportTo($importTo)
    {
        $this->importTo = $importTo;
    }

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

        $dropdowns = $this->arrayPreviewInHtmlRecursive($content, $this->contentParentTags);

        return view('import_export_tool::admin.dropdown-mapping.preview', compact('dropdowns'));
    }

    private function arrayPreviewInHtmlRecursive($array, $contentParentTags, $i=0, $lastKey = [])
    {
        $html = "<div class='tags'>";

        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {

                    $sendKey = [];
                    $sendKey['parent'] = $lastKey;
                    $sendKey['key'] = $key;

                    $htmlRecursive  = $this->arrayPreviewInHtmlRecursive($value, $contentParentTags, $i, $sendKey);
                    if (!$key) {
                        $html .= $htmlRecursive;
                    }

                    if ($key) {
                        if (isset($value[0])) {

                            $getParentMapKey = $this->getRecursiveKeysFromArray($lastKey, $key);
                            $getParentMapKey[] = $key;
                            $mapKey = implode('.', $getParentMapKey);


                            $html .= "<table class='tag_key'>";
                            $html .= "<tr>";
                            $html .= "<td class='tag_value'>";
                            $html .= $this->openKeyTag($key);
                            $html .= $htmlRecursive;
                            if ($key) {
                                $html .= $this->closeKeyTag($key);
                            }
                            $html .= "</td>";
                            $html .= "<td class='tag_select'>";
                            $html .=  $this->dropdownRepeatableSelect($mapKey);
                            $html .= "</td>";
                            $html .= "</tr>";
                            $html .= "</table>";
                        } else {
                            $html .= "<table class='tag_key'>";
                            $html .= "<tr>";
                            $html .= "<td class='tag_value'>";
                            $html .=  $this->openKeyTag($key);
                            $html .= $htmlRecursive;
                            if ($key) {
                                $html .= $this->closeKeyTag($key);
                            }
                            $html .= "</td>";
                            $html .= "</tr>";
                            $html .= "</table>";
                        }
                    }


                } else {

                    if (isset($lastKey['key']) && is_numeric($lastKey['key'])) {
                        unset($lastKey['key']);
                    }

                    // If key is numeric this is repeatable
                    if (is_numeric($key)) {
                        $html .= "<span class='tag_value'>" . $value . "</span>";
                        break;
                    } else {

                        if (mb_strlen($value) > 50) {
                            $value = mw()->format->limit($value, 50);
                        }

                        $html .= "<table class='tag_key' style='width:100%'>";
                        $html .= "<tr class='tag_value_select_tr'>";
                        $html .= "<td class='tag_value'>&lt;$key&gt;";
                        $html .=  '<span class="value">'.$value.'</span>';
                        $html .= "&lt;/$key&gt;</td>";

                        $getParentMapKey = $this->getRecursiveKeysFromArray($lastKey, $key);
                        $getParentMapKey[] = $key;
                        $mapKey = implode('.', $getParentMapKey);

                        if (Str::startsWith($mapKey, $contentParentTags)) {
                            $html .= "<td class='tag_select' style='width:300px'>" . $this->dropdownSelect($mapKey, $value) . "</td>";
                        } else{
                            $html .= "";
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

    private function dropdownRepeatableSelect($mapKey)
    {
        if ($mapKey == $this->contentParentTags) {
            return "";
        }

        $mapKeyHtml = str_replace('.',';',$mapKey);

        $html = '<select class="form-control" wire:model="import_feed.mapped_tags._repeatable_.'.$mapKeyHtml.'">';
        $html .= '<option value="tags">Tags</option>';
        $html .= '<option value="categories">Categories In Tree</option>';
        $html .= '<option value="first_level_categories">First Level Categories</option>';
        $html .= '<option value="variants">Product Variants</option>';
        $html .= '</select>';

        return $html;
    }

    private function dropdownSelect($mapKey, $value = false)
    {
        $selectOptions = [];

        if ($this->importTo == 'categories') {
            $itemMapReaderItemNames = ItemMapCategoryReader::getItemNames();
            $itemMapReaderMap = ItemMapCategoryReader::$map;
            $itemGroups = ItemMapCategoryReader::getItemGroups();
        } else {
            $itemMapReaderItemNames = ItemMapReader::getItemNames();
            $itemMapReaderMap = ItemMapReader::$map;
            $itemGroups = ItemMapReader::getItemGroups();
        }

        foreach ($itemMapReaderItemNames as $key=>$name) {

            $selected = false;
            if (isset($itemMapReaderMap[$key])) {
                foreach ($itemMapReaderMap[$key] as $itemMapKey) {

                    $itemMapKey = 'multilanguage.title.en_US';

                    $mapKeyExp = explode('.',$mapKey);
                    if (isset($mapKeyExp[1])) {

                        if (!empty($this->supported_languages)) {
                            foreach ($this->supported_languages as $supportedLanguage) {
                                $itemMapKeyLower = strtolower($itemMapKey);
                                $mapKeyExpLower = strtolower($mapKeyExp[1]);
                                $supportedLocale = strtolower($supportedLanguage['locale']);
                                if (strpos($mapKeyExpLower, $supportedLocale) !== false) {
                                    $mapKeyExpLowerWithoutLocale = str_replace('_' . $supportedLocale, '', $mapKeyExpLower);
                                   /* dump($itemMapKeyLower);
                                    dump($mapKeyExpLowerWithoutLocale);
                                     dd($mapKeyExpLower);*/
                                    $selected = true;
                                    break 2;
                                }
                            }
                        }

                        if ($mapKeyExp[1] == $itemMapKey) {
                            $selected = true;
                            break;
                        }
                    }
                }
            }

            $selectOptions[$key] = [
                'name'=>$name,
                'map_key'=>$mapKey,
                'selected'=>$selected,
            ];
        }

        $mapKeyHtml = str_replace('.',';',$mapKey);

        $dropdowns = [];

        foreach ($selectOptions as $name => $option) {
            if (!isset($option['name'])) {
                continue;
            }
            $itemIsFindedInGroup = false;
            foreach ($itemGroups as $groupName=>$groupItems) {
                foreach ($groupItems as $groupItem) {

                    $appendInGroup = false;
                    $expName = explode('.', $name);
                    if (isset($expName[0])) {
                        if ($groupItem == $expName[0].'.*') {
                            $appendInGroup = true;
                        }
                    }

                    if ($groupItem == $name) {
                        $appendInGroup = true;
                    }

                    if ($appendInGroup) {
                        $itemIsFindedInGroup = true;
                        $dropdowns[$groupName][] = [
                            'value'=>$name,
                            'name'=>$option['name'],
                            'selected'=>$option['selected'],
                        ];
                    }
                }
            }
            if (!$itemIsFindedInGroup) {
                $dropdowns[ucfirst($this->importTo)][] = [
                    'value'=>$name,
                    'name'=>$option['name'],
                    'selected'=>$option['selected'],
                ];
            }
        }

        return \Livewire\Livewire::mount('import-export-tool::dropdown_mapping', [
                'importFeedId'=>$this->import_feed['id'],
                'dropdowns'=>$dropdowns,
                'mapKey'=>$mapKeyHtml,
                'value'=>$value,
            ]
        )->html();

    }


    private function openKeyTag($name)
    {
        $html = PHP_EOL;
        $html .= '<div class="tag_key">&lt;' . $name . '&gt;</div>';
        $html .= PHP_EOL;

        return $html;
    }

    private function closeKeyTag($name)
    {
        $html = PHP_EOL;
        $html .= '<div class="tag_key">&lt;/' . $name . '&gt;</div>';
        $html .= PHP_EOL;

        return $html;
    }
}
