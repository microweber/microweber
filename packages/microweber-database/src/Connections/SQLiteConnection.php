<?php

namespace MicroweberPackages\Database\Connections;

use Illuminate\Database\SQLiteConnection as BaseSQLiteConnection;
use MicroweberPackages\Database\Grammars\SQLiteQueryGrammar;

/**
 * SQLite connection that uses the prefix-safe JSON grammar
 * (see SQLiteQueryGrammar). Registered via Connection::resolverFor('sqlite').
 */
class SQLiteConnection extends BaseSQLiteConnection
{
    protected function getDefaultQueryGrammar()
    {
        return new SQLiteQueryGrammar($this);
    }
}
