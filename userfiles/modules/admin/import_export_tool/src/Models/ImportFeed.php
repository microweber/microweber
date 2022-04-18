<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Models;

use Illuminate\Database\Eloquent\Model;

class ImportFeed extends Model
{
    public const YES = 1;
    public const NO = 0;

    protected $attributes = [
        'split_to_parts' => 10,
       // 'update_items' => ["visible","images","description","categories"],
        'download_images' => self::YES,
    ];

    protected $casts = [
        'download_images'=>'int',
        'update_items'=>'array',
        'mapped_tags'=>'array',
        'detected_content_tags'=>'array'
    ];
}
