<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/16/2021
 * Time: 7:04 PM
 */

namespace MicroweberPackages\Repository\Repositories\Interfaces;


interface ContentRepositoryInterface
{

    public function all();

    public function getById($id);

}