<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Page\Http\Controllers\Admin;

use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Crud\Traits\HasCrudActions;
use MicroweberPackages\Page\Http\Requests\PageRequest;
use MicroweberPackages\Page\Page;
use MicroweberPackages\Page\Repositories\PageRepository;

class PagesController extends AdminController
{
    use HasCrudActions;

    public $repository = PageRepository::class;
    public $model = Page::class;
    public $validator = PageRequest::class;

}