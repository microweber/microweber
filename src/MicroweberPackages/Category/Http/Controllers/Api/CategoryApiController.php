<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Category\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use   MicroweberPackages\Category\Http\Requests\CategoryRequest;
use MicroweberPackages\Category\Repositories\CategoryRepositoryApi;

class CategoryApiController extends AdminDefaultController
{
    public $category;

    public function __construct(CategoryRepositoryApi $category)
    {
        $this->category = $category;

        parent::__construct();
    }

    /**
     * Display a listing of the product.\
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CategoryRequest $request)
    {
        return (new JsonResource(
            $this->category
                ->filter($request->all())
                ->paginate($request->get('limit', 30))
                ->appends($request->except('page'))

        ))->response();


    }

    /**
     * Store product in database
     * @param CategoryRequest $request
     * @return mixed
     */
    public function store(CategoryRequest $request)
    {

        $data = $request->all();
        if ($data and isset($data['id']) and $data['id'] == 0) {
            unset($data['id']);
        }

        if ($data and isset($data['id']) and $data['id'] != 0) {
            $result = $this->category->update($request->all(), $data['id']);
        } else {
            $result = $this->category->create($data);
        }


        return (new JsonResource($result))->response();

    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = $this->category->find($id);
        return (new JsonResource($result))->response();

    }


    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, $id)
    {

        $result = $this->category->update($request->all(), $id);

        return (new JsonResource($result))->response();

    }

    /**
     * Destroy resources by given id.
     * @param string $id
     * @return void
     */
    public function destroy(CategoryRequest $request, $id)
    {

        $result = $this->category->show($id);
        if ($result) {
             (new JsonResource(['id' => $result->delete()]));
        }

    }

}
