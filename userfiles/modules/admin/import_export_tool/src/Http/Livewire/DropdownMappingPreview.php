<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapCategoryReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class DropdownMappingPreview extends Component
{
    public $data;
    public $import_feed_id = 0;
    public $import_feed = [];
    public $supported_languages = 0;
    public $listeners = [
          'dropdownMappingPreviewRefresh'=>'refreshImportFeedState'
    ];

    public function refreshImportFeedState()
    {
        $importFeed = ImportFeed::where('id', $this->import_feed_id)->first();
        if ($importFeed != null) {
            $this->import_feed = $importFeed->toArray();
        }
    }

    public function mount($importFeedId)
    {
        $importFeed = ImportFeed::where('id', $importFeedId)->first();
        if ($importFeed == null) {
            return redirect(route('admin.import-export-tool.index'));
        }

        $this->import_feed_id = $importFeedId;
        $this->import_feed = $importFeed->toArray();

        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            $supportedLanguages = get_supported_languages();
            if (!empty($supportedLanguages)) {
                $this->supported_languages = $supportedLanguages;
            }
        }
    }

    public $html = [];

    public function render()
    {
        $contentParentTag = $this->import_feed['content_tag'];
        $content = $this->import_feed['source_content'];

      /*  $allFieldsFilled = [];
        if (isset($content[$contentParentTag])) {
            foreach ($content[$contentParentTag] as $contentItem) {
                foreach ($contentItem as $contentItemKey=>$contentItemValue) {
                    if (!empty(trim(strip_tags($contentItemValue))) && !isset($allFieldsFilled[$contentItemKey])) {
                        $allFieldsFilled[$contentItemKey] = $contentItemValue;
                    }
                }
            }
            $allFieldsFilledReady = $allFieldsFilled;
            $allFieldsFilled = [];
            $allFieldsFilled[$contentParentTag][] = $allFieldsFilledReady;
        }*/

        $allFieldsFilled = [];
        $readContent = Arr::get($content, $contentParentTag);
        if (!empty($readContent)) {
            foreach ($readContent as $contentItem) {
                foreach ($contentItem as $contentItemKey=>$contentItemValue) {
                    if (is_string($contentItemValue)) {
                        if (!empty(trim(strip_tags($contentItemValue))) && !isset($allFieldsFilled[$contentItemKey])) {
                            $allFieldsFilled[$contentItemKey] = $contentItemValue;
                        }
                    } else if (is_array($contentItemValue)) {
                        if (!empty($contentItemValue) && !isset($allFieldsFilled[$contentItemKey])) {
                            $allFieldsFilled[$contentItemKey] = $contentItemValue;
                        }
                    }
                }
            }
        }

        $allFieldsFilled = Arr::undot([$contentParentTag=>[$allFieldsFilled]]);

        $dropdowns = $this->arrayPreviewInHtmlRecursive($allFieldsFilled, $contentParentTag);

        if (empty($this->import_feed['mapped_tags'])) {
            $automaticSelected = $this->getAutomaticSelectedOptions();
            $importFeed = ImportFeed::where('id', $this->import_feed_id)->first();
            if ($importFeed) {
                $importFeed->mapped_tags = $automaticSelected;
                $importFeed->save();
            }
        }

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

                        $html .= "<table class='tag_key' style='width:100%;margin-left:20px;'>";
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
        if ($mapKey == $this->import_feed['content_tag']) {
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

    private function dropdownSelect($mapKey, $value = false)
    {
        $selectOptions = [];

        if ($this->import_feed['import_to'] == 'categories') {
            $itemMapReaderItemNames = ItemMapCategoryReader::getItemNames();
            $itemMapReaderMap = ItemMapCategoryReader::getMap();
            $itemGroups = ItemMapCategoryReader::getItemGroups();
        } else {
            $itemMapReaderItemNames = ItemMapReader::getItemNames();
            $itemMapReaderMap = ItemMapReader::getMap();
            $itemGroups = ItemMapReader::getItemGroups();
        }

        foreach ($itemMapReaderItemNames as $key=>$name) {

            $selected = false;
            if (isset($itemMapReaderMap[$key])) {
                foreach ($itemMapReaderMap[$key] as $itemMapKey) {
                    $itemMapKey = mb_strtolower($itemMapKey);
                    $mapKeyExp = explode('.',$mapKey);
                    if (isset($mapKeyExp[1])) {
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
                        $dropdowns[$groupName][] = [
                            'value'=>$name,
                            'name'=>$option['name'],
                            'selected'=>$option['selected'],
                        ];
                    }
                }
            }
            if (!$itemIsFindedInGroup) {
                $dropdowns[ucfirst($this->import_feed['import_to'])][] = [
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
