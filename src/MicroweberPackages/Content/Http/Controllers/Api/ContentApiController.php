<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Content\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Content\Http\Requests\ContentRequest;
use MicroweberPackages\Content\Repositories\ContentRepository;

class ContentApiController extends AdminDefaultController
{
    public $content;

    public function __construct(ContentRepository $content)
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
    public function store(ContentRequest $request)
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
     * @param  PageRequest $request
     * @param  string $id
     * @return Response
     */
    public function update(PageRequest $request, $id)
    {
        return (new JsonResource($this->content->update($request->all(), $id)));
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function delete($id)
    {
        return (new JsonResource($this->content->delete($id)));
    }

    /**
     * Delete resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        return (new JsonResource($this->content->destroy($ids)));
    }
}