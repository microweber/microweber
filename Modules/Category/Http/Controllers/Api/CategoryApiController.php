<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace Modules\Category\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Repositories\CategoryRepositoryApi;

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
     * @param \Modules\Category\Http\Requests\CategoryRequest $request
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
     * @param \Modules\Category\Http\Requests\CategoryRequest $request
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

        $result->admin_edit_url = get_category_edit_link($result->id);

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
    public function delete(CategoryRequest $request, $id)
    {
        return $this->category->delete($id);
    }

    public function destroy(CategoryRequest $request)
    {
        $ids = $request->get('ids', []);

        return $this->category->destroy($ids);
    }


    public function hiddenBulk(CategoryRequest $request)
    {
        $ids = $request->get('ids', []);

        return $this->category->hiddenBulk($ids);
    }

    public function visibleBulk(CategoryRequest $request)
    {
        $ids = $request->get('ids', []);

        return $this->category->visibleBulk($ids);
    }

    public function moveBulk(CategoryRequest $request)
    {
        $ids = $request->get('ids', []);
        $moveToRelId = $request->get('moveToRelId', false);
        $moveToParentIds = $request->get('moveToParentIds', []);


        return $this->category->moveBulk($ids, $moveToParentIds, $moveToRelId);
    }

}
