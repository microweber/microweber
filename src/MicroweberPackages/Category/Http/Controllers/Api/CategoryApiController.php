<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Category\Http\Controllers\Api;

use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Category\Http\Requests\CategoryRequest;
use MicroweberPackages\Category\Repositories\CategoryRepository;

class CategoryApiController extends AdminDefaultController
{
    public $category;

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the product.\
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->category->all();
    }

    /**
     * Store product in database
     * @param CategoryRequest $request
     * @return mixed
     */
    public function store(CategoryRequest $request)
    {
        return $this->category->create($request->all());
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->category->find($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  CategoryRequest $request
     * @param  string $id
     * @return Response
     */
    public function update(CategoryRequest $request, $id)
    {
        return $this->category->update($request->all(), $id);
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function delete($id)
    {
        return $this->category->delete($id);
    }

    /**
     * Delete resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        return $this->category->destroy($ids);
    }

}