<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use Modules\Page\Http\Requests\PageRequest;
use Modules\Page\Repositories\PageApiRepository;

class PageApiController extends AdminDefaultController
{
    public $page;

    public function __construct(PageApiRepository $page)
    {
        $this->page = $page;
    }

    /**
     * Display a listing of the product.\
     *
     * @param \Modules\Page\Http\Requests\PageRequest $request
     * @return JsonResource
     */
    public function index()
    {
        return (new JsonResource($this->page->all()))->response();
    }

    /**
     * Store product in database
     * @param \Modules\Page\Http\Requests\PageRequest $request
     * @return mixed
     */
    public function store(PageRequest $request)
    {
        return (new JsonResource($this->page->create($request->all())));
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return JsonResource
     */
    public function show($id)
    {
        return (new JsonResource($this->page->show($id)));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Modules\Page\Http\Requests\PageRequest $request
     * @param string $id
     * @return JsonResource
     */
    public function update(PageRequest $request, $id)
    {
        return (new JsonResource($this->page->update($request->all(), $id)));
    }

    /**
     * Destroy resources by given id.
     *
     * @param string $id
     * @return JsonResource
     */
    public function destroy($id)
    {
        return (new JsonResource(['id' => $this->page->delete($id)]));
    }
}
