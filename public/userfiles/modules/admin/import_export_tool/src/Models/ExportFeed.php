<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use MicroweberPackages\Export\Formats\Helpers\SpreadsheetHelper;
use MicroweberPackages\Import\Formats\CsvReader;
use MicroweberPackages\Import\Formats\XlsxReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;

class ExportFeed extends Model
{
    public $fillable = [
      'name',
      'is_draft',
      'split_to_parts',
      'export_format',
      'export_type',
    ];
    protected $casts = [
        'split_to_parts' => 'int',
    ];

}
