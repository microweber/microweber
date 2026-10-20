<?php

namespace MicroweberPackages\Database\Grammars;

use Illuminate\Database\Query\Grammars\SQLiteGrammar;

/**
 * Laravel 13's SQLite grammar compiles a JSON-contains as
 *   ... from json_each(col) where "json_each"."value" is ?
 * using $this->wrap('json_each.value'), which — when a table prefix is set
 * (DB_PREFIX) — wrongly prefixes the json_each pseudo-table, producing
 * "<prefix>json_each"."value" and the SQLite error:
 *   no such column: <prefix>json_each.value
 * (breaking whereJsonContains() in both migrations and queries on SQLite).
 *
 * json_each is a table-valued function result, never a real table, so it must
 * not take the connection's table prefix. Wrap its identifier parts directly.
 */
class SQLiteQueryGrammar extends SQLiteGrammar
{
    protected function compileJsonContains($column, $value)
    {
        [$field, $path] = $this->wrapJsonFieldAndPath($column);

        return 'exists (select 1 from json_each(' . $field . $path . ') where '
            . $this->wrapValue('json_each') . '.' . $this->wrapValue('value')
            . ' is ' . $value . ')';
    }
}
