<?php

namespace MicroweberPackages\Install\Schema;

use MicroweberPackages\Database\Facades\DatabaseManager;


class Countries
{
    public function get()
    {
        return [
            'countries' => [
                'code' => 'string',
                'name' => 'string',
                'continent' => 'string',
                'surfacearea' => 'float',
                'population' => 'integer',
                'localname' => 'string',
            ],
        ];
    }

    public function seed()
    {
        $table_sql = MW_PATH.'Utils'.DS.'lib'.DS.'countries.sql';

        DatabaseManager::import_sql_file($table_sql);
    }
}
