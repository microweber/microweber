<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Models;

use Illuminate\Database\Eloquent\Model;

class ImportFeed extends Model
{

    protected $casts = [
        'update_items'=>'array'
    ];
}
