<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace Modules\Content\Http\Controllers\Api;

use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;

class ContentApiController extends AdminDefaultController
{


    public function get_admin_js_tree_json($params = [])
    {
        return app()->category_manager->get_admin_js_tree_json($params);
    }
}
