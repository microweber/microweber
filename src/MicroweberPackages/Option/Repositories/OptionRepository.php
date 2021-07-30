<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/30/2021
 * Time: 12:21 PM
 */

namespace MicroweberPackages\Option\Repositories;


use MicroweberPackages\Option\Models\Option;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


class OptionRepository extends AbstractRepository
{

    protected $searchable = [
        'id',
        'option_group',
        'option_value',
        'module',
        'is_system',

    ];


    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Option::class;

}