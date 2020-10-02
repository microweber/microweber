<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Post\Http\Controllers\Admin;

use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Crud\Traits\HasCrudActions;
use MicroweberPackages\Post\Http\Requests\PostRequest;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Post\Repositories\PostRepository;

class PostsController extends AdminController
{
    use HasCrudActions;

    public $repository = PostRepository::class;
    public $model = Post::class;
    public $validator = PostRequest::class;

}