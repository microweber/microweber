<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use MicroweberPackages\Export\Formats\Helpers\SpreadsheetHelper;
use MicroweberPackages\Export\SessionStepper;
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
        'source_type' => 'download_link',
        'split_to_parts' => 10,
        // 'update_items' => ["visible","images","description","categories"],
        'download_images' => self::YES,
    ];

    protected $casts = [
        'download_images' => 'int',
        'update_items' => 'array',
        'imported_content_ids' => 'array',
        'mapped_tags' => 'array',
        'detected_content_tags' => 'array',
        'media_url_separators' => 'array',
        'category_separators' => 'array',
        'category_ids_separators' => 'array',
        'category_add_types' => 'array',
        'tags_separators' => 'array',
        'custom_content_data_fields' => 'array',
    ];

    protected $appends = ['source_content', 'mapped_content'];

    public function getMappedContentAttribute()
    {
        $mappedContentFile = $this->getMappedContentRealpath($this->id);
        if (is_file($mappedContentFile)) {
           return json_decode(file_get_contents($mappedContentFile), TRUE);
        }

        return [];
    }

    public function getSourceContentAttribute()
    {
        $sourceContentFile = $this->getSourceContentRealpath($this->id);
        if (is_file($sourceContentFile)) {
            return json_decode(file_get_contents($sourceContentFile), TRUE);
        }

        return [];
    }

    public static function getImportTempPath()
    {
        $environment = App::environment();
        $folder = storage_path('import_export_tool/') . ('default' . DIRECTORY_SEPARATOR);

        if(defined('MW_IS_MULTISITE') and MW_IS_MULTISITE) {
            $folder = storage_path('import_export_tool/') . ($environment . DIRECTORY_SEPARATOR);
        }

        if (!is_dir($folder)) {
            mkdir_recursive($folder);
        }

        return $folder;
    }

    public function getMappedContentRealpath($id)
    {
        return self::getImportTempPath() . 'mapped-content-' . $id.'.json';
    }

    public function getSourceContentRealpath($id)
    {
        return self::getImportTempPath() . 'source-content-' . $id.'.json';
    }

    public function saveMappedContent($content)
    {
        $mappedContentFile = $this->getMappedContentRealpath($this->id);

        $this->mapped_content_realpath = $mappedContentFile;
        $this->save();

        return file_put_contents($mappedContentFile, json_encode($content));
    }

    public function readContentFromFile(string $filename, $fileType = false)
    {
        if ($fileType == 'xlsx' || $fileType == 'xls') {
            return $this->readContentFromXlsx($filename);
        } elseif ($fileType == 'xml') {
            return $this->readContentFromXml($filename);
        } elseif ($fileType == 'csv') {
            return $this->readContentFromCsv($filename);
        } else {
            return false;
        }
    }

    private function readContentFromXlsx(string $filename)
    {

        $repeatableTargetKeys = [];
        $spreadshet = SpreadsheetHelper::newSpreadsheet($filename);
        $sheetCount = $spreadshet->getSheetCount();
        if ($sheetCount == 0) {
            //  throw new \Exception('No sheets found');
            return false;
        }

        if (empty($this->content_tag)) {
            $this->content_tag = $spreadshet->setSheet(0)->getSheet()->getTitle();
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
            $sourceContent = [$this->content_tag => $readedRows[$this->content_tag]];
            $countOfContents = count($readedRows[$this->content_tag]);
        }

        $sourceContentFile = $this->getSourceContentRealpath($this->id);
        file_put_contents($sourceContentFile, json_encode($sourceContent));

        $this->source_content_realpath = $sourceContentFile;
        $this->detected_content_tags = $repeatableTargetKeys;
        $this->count_of_contents = $countOfContents;
        $this->split_to_parts = SessionStepper::recomendedSteps($countOfContents);
        $this->mapped_tags = [];
        $this->mapped_content_realpath = '';

        $this->save();

        return true;
    }

    private function readContentFromCsv(string $filename)  {

        $reader = new CsvReader($filename);
        $sourceContent = ['Data' => $reader->readData()];
        $contentTag = 'Data';
        $repeatableTargetKeys = ['Data' => []];
        $repeatableData = $sourceContent['Data'];

        $sourceContentFile = $this->getSourceContentRealpath($this->id);
        file_put_contents($sourceContentFile, json_encode($sourceContent));

        $this->source_content_realpath = $sourceContentFile;
        $this->detected_content_tags = $repeatableTargetKeys;
        $this->count_of_contents = count($repeatableData);
        $this->split_to_parts = SessionStepper::recomendedSteps(count($repeatableData));
        $this->mapped_tags = [];
        $this->mapped_content = [];
        $this->content_tag = $contentTag;

        $this->save();
    }

    private function readContentFromXml(string $filename) {

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

            $this->count_of_contents = 0;
            if (is_array($repeatableData)) {
                $this->count_of_contents = count($repeatableData);
                $this->split_to_parts = SessionStepper::recomendedSteps(count($repeatableData));
            }

            $sourceContentFile = $this->getSourceContentRealpath($this->id);
            file_put_contents($sourceContentFile, json_encode($content));

            $this->source_content_realpath = $sourceContentFile;
            $this->mapped_tags = [];
            $this->save();

            return true;
        }

        return false;
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
        $this->source_content_realpath = null;
        $this->last_import_start = null;
        $this->last_import_end = null;
        $this->count_of_contents = null;
        $this->content_tag = null;
        $this->imported_content_ids = null;
        $this->detected_content_tags = [];
        $this->mapped_tags = [];
        $this->mapped_content_realpath = null;
        $this->save();
    }
}
