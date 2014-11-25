<?php
namespace Weber\Utils;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;


class QueryBuilder extends Illuminate\Database\Query\Builder
{

    /**
     * Find a model by its primary key.
     *
     * @param  mixed $id
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public function find($id, $columns = array('*'))
    {
        Event::fire('before.find', array($this));
        $result = parent::find($id, $columns);
        Event::fire('after.find', array($this));
        return $result;
    }
}



