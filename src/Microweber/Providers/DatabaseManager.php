<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */

namespace Microweber\Providers;

use Illuminate\Support\Facades\DB;
use Microweber\Content;
use Microweber\Media;

class DatabaseManager extends \MicroweberPackages\DatabaseManager\DatabaseManager
{
    public function table($table)
    {
        // @todo move this to external resolver class or array
        if ($table == 'content') {
            return Content::query();
        }
        if ($table == 'media') {
            return Media::query();
        }

        return DB::table($table);
    }
}
