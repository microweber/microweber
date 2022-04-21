<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Models;

use Illuminate\Database\Eloquent\Model;

class ImportFeed extends Model
{
    public const YES = 1;
    public const NO = 0;

    public const SOURCE_TYPE_UPLOAD_FILE = 'upload_file';
    public const SOURCE_TYPE_DOWNLOAD_LINK = 'download_link';

    protected $attributes = [
        'source_type' => self::SOURCE_TYPE_UPLOAD_FILE,
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
}
