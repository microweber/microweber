<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use MicroweberPackages\Export\Formats\Helpers\SpreadsheetHelper;
use MicroweberPackages\Import\Formats\CsvReader;
use MicroweberPackages\Import\Formats\XlsxReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;

class ImportFeed extends Model
{
    public const YES = 1;
    public const NO = 0;

    public const SOURCE_TYPE_UPLOAD_FILE = 'upload_file';
    public const SOURCE_TYPE_DOWNLOAD_LINK = 'download_link';

    protected $attributes = [
        'source_type' => self::SOURCE_TYPE_DOWNLOAD_LINK,
        'split_to_parts' => 10,
       // 'update_items' => ["visible","images","description","categories"],
        'download_images' => self::YES,
    ];

    protected $casts = [
        'download_images'=>'int',
        'update_items'=>'array',
        'imported_content_ids'=>'array',
        'source_content'=>'array',
        'mapped_tags'=>'array',
        'mapped_content'=>'array',
        'detected_content_tags'=>'array'
    ];

    public function readContentFromFile(string $filename, $fileType = false)
    {
        if ($fileType == 'xlsx') {
            return $this->readContentFromXlsx($filename);
        } elseif ($fileType == 'xml') {
            return $this->readContentFromXml($filename);
        } else {
            return false;
        }
    }

    public function readContentFromXlsx(string $filename) {

        $spreadshet = SpreadsheetHelper::newSpreadsheet($filename);
        $sheetCount = $spreadshet->getSheetCount();
        if ($sheetCount == 0) {
             //  throw new \Exception('No sheets found');
            return false;
        }

        // Read sheet
        $readedRows = [];
        for ($i = 0; $i <= $sheetCount; $i++) {

            try {
                $getSheet = $spreadshet->setSheet($i)->getSheet();
                $getRows = $spreadshet->getRows();
                $sheetNames[$i] = $getSheet->getTitle();
                $repeatableTargetKeys[$getSheet->getTitle()] = [];

                // unset headers
                $dataHeader = $getRows[0];
                unset($getRows[0]);
                foreach ($getRows as $row) {
                    $readyRow = array();
                    foreach ($row as $rowKey => $rowValue) {
                        $readyRow[$dataHeader[$rowKey]] = $rowValue;
                    }
                    $readedRows[$getSheet->getTitle()][] = $readyRow;
                }
            } catch (\Exception $e) {

            }
        }

        $countOfContents = 0;
        $sourceContent = [];

        if (isset($readedRows[$this->content_tag])) {
            $sourceContent = [$this->content_tag=>$readedRows[$this->content_tag]];
            $countOfContents = count($readedRows[$this->content_tag]);
        }

        $this->source_content = $sourceContent;
        $this->detected_content_tags = $repeatableTargetKeys;
        $this->count_of_contents = $countOfContents;
        $this->mapped_content = [];

        $this->save();

        return true;
    }

    public function readContentFromXml(string $filename) {

        $content = file_get_contents($filename);

        $newReader = new XmlToArray();
        $content = $newReader->readXml($content);
        if (!empty($content)) {

            $repeatableData = [];

            if (empty($this->content_tag)) {

                // Try to automatically detect content tag

                $contentTag = false;
                $repeatableTargetKeys = $newReader->getArrayRepeatableTargetKeys($content);
                $repeatableTargetKeys = Arr::dot($repeatableTargetKeys);

                if (!empty($repeatableTargetKeys)) {
                    $contentTag = array_key_first($repeatableTargetKeys);
                    $this->detected_content_tags = $repeatableTargetKeys;
                }

                if ($contentTag) {
                    $repeatableData = Arr::get($content, $contentTag);
                    $this->content_tag = $contentTag;
                }

            } else {
                $repeatableData = Arr::get($content, $this->content_tag);
            }

            $this->count_of_contents = count($repeatableData);
            $this->mapped_content = [];
            $this->source_content = $content;
            $this->save();

            return true;
        }

        return false;
    }

    public function readFeedFromFileOld(string $filename) {

        /*   $repeatableTargetKeys = $newReader->getArrayRepeatableTargetKeys($sourceContent);
       $repeatableTargetKeys = Arr::dot($repeatableTargetKeys);
       if (!empty($repeatableTargetKeys)) {
           $contentTag = array_key_first($repeatableTargetKeys);
       }*/

        /*if (!empty($this->content_tag)) {
            $repeatableData = Arr::get($sourceContent, $this->content_tag);
        } else if ($contentTag) {
            $repeatableData = Arr::get($sourceContent, $contentTag);
        }*/

        if (empty($repeatableData)) {
            $repeatableData = [];
        }

        if (empty($sourceContent)) {
            $reader = new CsvReader($filename);
            $sourceContent = ['Data' => $reader->readData()];
            $contentTag = 'Data';
            $repeatableTargetKeys = ['Data' => []];
            $repeatableData = $sourceContent['Data'];
        }

     /*   $sourceContent = ['items' => [
            'item'=>[
                'title'=>'Item 1'
            ],
            'item'=>[
                'title'=>'Item 2'
            ],
            'item'=>[
                'title'=>'Item 3'
            ],
        ]];
        $contentTag = 'item';
        $repeatableTargetKeys = ['items' => []];
        $repeatableData = $sourceContent['items'];*/

        $this->source_content = $sourceContent;
        $this->detected_content_tags = $repeatableTargetKeys;
        $this->count_of_contents = count($repeatableData);
        $this->mapped_content = [];

        if ($contentTag && empty($this->content_tag)) {
            $this->content_tag = $contentTag;
        }

        $this->save();

    }

    public function downloadFeed($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $allowedExt = ['xml','csv','json','xlsx','xls'];
        $fileExt = pathinfo($url, PATHINFO_EXTENSION);

        $dir = storage_path() . DS . 'import_export_tool';

        $filename = $dir . DS . md5($url) . '.txt';
        if (in_array($fileExt, $allowedExt)) {
            $filename = $dir . DS . md5($url) . '.'.$fileExt;
        }

        if (!is_dir($dir)) {
            mkdir_recursive($dir);
        }

        // Delete old file if exist
        if (is_file($filename)) {
            unlink($filename);
        }

        $downloaded = mw()->http->url($url)->download($filename);

        if ($downloaded && is_file($filename)) {

            if($filename != $this->source_file_realpath) {
                $this->resetFeed();
            }

            $this->source_type = 'download_link';
            $this->source_url = $url;
            $this->source_file_realpath = $filename;
            $this->source_file_size = filesize($filename);
            $this->last_downloaded_date = Carbon::now();
            $this->save();

            return true;
        }

        return false;
    }

    public function resetFeed()
    {
        $this->source_content = [];
        $this->last_import_start = null;
        $this->last_import_end = null;
        $this->count_of_contents = null;
        $this->content_tag = null;
        $this->imported_content_ids = null;
        $this->detected_content_tags = [];
        $this->mapped_tags = [];
        $this->mapped_content = [];
        $this->save();
    }
}
