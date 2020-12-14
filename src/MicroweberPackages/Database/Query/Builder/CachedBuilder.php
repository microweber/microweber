<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/14/2020
 * Time: 12:16 PM
 */
namespace MicroweberPackages\Database\Query\Builder;

use function Opis\Closure\serialize as serializeClosure;




class CachedBuilder extends \Illuminate\Database\Query\Builder {


    /**
     * Execute the get query statement.
     *
     * @param  array  $columns
     * @return array|static[]
     */
    public function get($columns = ['*'])
    {

    //    dump($this->toSql());
      //  var_dump($this->getBindings());

        return parent::get($columns);
    }


}