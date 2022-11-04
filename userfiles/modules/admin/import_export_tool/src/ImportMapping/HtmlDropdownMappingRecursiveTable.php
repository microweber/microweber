<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapCategoryReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader;


class HtmlDropdownMappingRecursiveTable
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

        $html = $this->arrayPreviewInHtmlRecursive($content, $this->contentParentTags);

        return $html;
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
                            $html .= "<td class='tag_select' style='width:300px'>" . $this->dropdownSelect($mapKey) . "</td>";
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


    public $automaticSelectedOptions = [];
    public function getAutomaticSelectedOptions() {
        return $this->automaticSelectedOptions;
    }

    private function dropdownSelect($mapKey)
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
                    if (stripos($mapKey, $itemMapKey) !== false) {
                        $selected = true;
                        break;
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

        $html = '
        <input type="hidden" id="js-import-feed-mapped-tag-'.md5($mapKeyHtml).'" wire:model="import_feed.mapped_tags.'.$mapKeyHtml.'" />
        <div wire:ignore>
        <select class="form-control" id="js-dropdown-select-'.md5($mapKeyHtml).'">
        <option value="none">Select</option>
        ';

        $showInGroup = [];

        $html .= '<optgroup label="'.ucfirst($this->importTo).' fields">';

        foreach ($selectOptions as $name => $option) {
            if (!isset($option['name'])) {
                continue;
            }
            if ($option['selected']) {
                $this->automaticSelectedOptions[$mapKeyHtml] = $name;
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
                        $showInGroup[$groupName][] = '<option value="'.$name.'">'.$option['name'].'</option>';
                    }
                }
            }
            if (!$itemIsFindedInGroup) {
                $html .= '<option value="'.$name.'">'.$option['name'].'</option>';
            }
        }
        $html .= '</optgroup>';

       foreach ($showInGroup as $groupName=>$groupItemsOptions) {
           $html .= '<optgroup label="'.$groupName.'">';
           foreach ($groupItemsOptions as $groupItemOption) {
               $html .= $groupItemOption;
           }
            $html .= '</optgroup>';
        }


        $html .= ' <optgroup label="Custom"><option value="custom_content_data">Content Data</option></optgroup>';
        $html .= '</select>';

        $html .= '<input type="text" style="border-color:rgb(69, 146, 255);color:rgb(69, 146, 255);" id="js-custom-map-key-'.md5($mapKeyHtml).'" placeholder="Please enter content data key" class="form-control mt-2" />';
        $html .= "
<script>

    function refreshMapKeyInputs".md5($mapKeyHtml)."() {
        if ($('#js-import-feed-mapped-tag-" . md5($mapKeyHtml) . "').val().includes('custom_content_data')) {
            const feedMapTagSplit = $('#js-import-feed-mapped-tag-" . md5($mapKeyHtml) . "').val().split('.');
            if (feedMapTagSplit[1]) {
                $('#js-custom-map-key-" . md5($mapKeyHtml) . "').val(feedMapTagSplit[1]);
                $('#js-dropdown-select-" . md5($mapKeyHtml) . "').val('custom_content_data');
            }
            $('#js-custom-map-key-" . md5($mapKeyHtml) . "').show();
        } else {
            var feedMapTagValue = $('#js-import-feed-mapped-tag-" . md5($mapKeyHtml) . "').val();
            if (feedMapTagValue.length > 0) {
                $('#js-dropdown-select-" . md5($mapKeyHtml) . "').val(feedMapTagValue);
            }
            $('#js-custom-map-key-" . md5($mapKeyHtml) . "').hide();
        }
    }

    refreshMapKeyInputs".md5($mapKeyHtml)."();
    document.addEventListener('livewire:load', function () {
        refreshMapKeyInputs".md5($mapKeyHtml)."();
    });

    document.getElementById('js-dropdown-select-".md5($mapKeyHtml)."').onchange = function() {

        var importFeedMappedTag = document.getElementById('js-import-feed-mapped-tag-".md5($mapKeyHtml)."');

        importFeedMappedTag.value = $('#js-dropdown-select-".md5($mapKeyHtml)."').val();
        importFeedMappedTag.dispatchEvent(new Event('input'));

        if ($('#js-dropdown-select-".md5($mapKeyHtml)."').val() == 'custom_content_data') {
            $('#js-custom-map-key-".md5($mapKeyHtml)."').show();
        } else {
            $('#js-custom-map-key-" . md5($mapKeyHtml) . "').hide();
        }
    };
     document.getElementById('js-custom-map-key-".md5($mapKeyHtml)."').onchange = function() {

        var importFeedMappedTag = document.getElementById('js-import-feed-mapped-tag-".md5($mapKeyHtml)."');
        var importFeedMappedTagValue = $('#js-custom-map-key-".md5($mapKeyHtml)."').val();
        importFeedMappedTagValue = importFeedMappedTagValue.replaceAll(' ','_');
        importFeedMappedTagValue = importFeedMappedTagValue.toLowerCase();

        $('#js-custom-map-key-".md5($mapKeyHtml)."').val(importFeedMappedTagValue);

        importFeedMappedTag.value = 'custom_content_data.' + importFeedMappedTagValue;
        importFeedMappedTag.dispatchEvent(new Event('input'));
    };
</script>
</div>";

        return $html;
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
