<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Content\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Content\Repositories\ContentRepositoryApi;

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
     * @param ContentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return (new JsonResource($this->content->all()))->response();
    }

    /**
     * Store content in database
     * @param ContentRequest $request
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return (new JsonResource($this->content->show($id)));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ContentRequest $request
     * @param  string $id
     * @return Response
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
