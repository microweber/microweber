<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use MicroweberPackages\Import\Formats\CsvReader;
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

    public function downloadFeed($sourceFile)
    {
        $dir = storage_path() . DS . 'import_export_tool';
        $filename = $dir . DS . md5($sourceFile) . '.txt';
        if (!is_dir($dir)) {
            mkdir_recursive($dir);
        }

        $downloaded = mw()->http->url($sourceFile)->download($filename);

        if ($downloaded && is_file($filename)) {

            // Xml check read
            $contentTag = false;
            $content = file_get_contents($filename);
            $newReader = new XmlToArray();
            $sourceContent = $newReader->readXml($content);
            $repeatableTargetKeys = $newReader->getArrayRepeatableTargetKeys($sourceContent);
            $repeatableTargetKeys = Arr::dot($repeatableTargetKeys);
            if (!empty($repeatableTargetKeys)) {
                $contentTag = array_key_first($repeatableTargetKeys);
            }

            if (!empty($this->content_tag)) {
                $repeatableData = Arr::get($sourceContent, $this->content_tag);
            } else if ($contentTag) {
                $repeatableData = Arr::get($sourceContent, $contentTag);
            }

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

            if (empty($sourceContent)) {
                unlink($filename);
                return;
            }

            $realpath = str_replace(base_path(), '', $filename);

            $this->source_file = $sourceFile;
            $this->source_content = $sourceContent;
            $this->source_file_realpath = $realpath;
            $this->detected_content_tags = $repeatableTargetKeys;
            $this->count_of_contents = count($repeatableData);
            $this->mapped_content = [];
            $this->source_file_size = filesize($filename);
            $this->last_downloaded_date = Carbon::now();

            if ($contentTag && empty($this->content_tag)) {
                $this->content_tag = $contentTag;
            }
            $this->save();

            return true;
        }
        
        return false;
    }
}
