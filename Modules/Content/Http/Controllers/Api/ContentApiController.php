<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace Modules\Content\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use Modules\Content\Repositories\ContentRepositoryApi;

class ContentApiController extends AdminDefaultController
{
    public $content;

    public function __construct(ContentRepositoryApi $content)
    {
        $this->content = $content;
    }

    /**
     * Display a listing of the content.\
     *
     * @param Request $request
     * @return JsonResource
     */
    public function index(Request $request)
    {


        return (new JsonResource($this->content->filter($request->all() )->get()));
    }

    /**
     * Store content in database
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return (new JsonResource($this->content->create($request->all())));
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return JsonResource
     */
    public function show($id)
    {
        return (new JsonResource($this->content->show($id)));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string $id
     * @return JsonResource
     */
    public function update(Request $request, $id)
    {
        return (new JsonResource($this->content->update($request->all(), $id)));
    }

    /**
     * Destroy resources by given id.
     * @param string $id
     * @return void
     */
    public function destroy($id)
    {
        return (new JsonResource(['id'=>$this->content->delete($id)]));
    }


    public function get_admin_js_tree_json($params = [])
    {
        return app()->category_manager->get_admin_js_tree_json($params);
    }
}
