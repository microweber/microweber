<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Page\Http\Controllers\Admin;

use MicroweberPackages\Crud\Traits\HasCrudActions;
use MicroweberPackages\Page\Http\Requests\PageRequest;
use MicroweberPackages\Page\Page;

class PagesController
{
    use HasCrudActions;

    public $model = Page::class;
    public $validator = PageRequest::class;

}