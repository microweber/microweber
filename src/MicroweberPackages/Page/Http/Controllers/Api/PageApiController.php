<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Page\Http\Controllers\Api;

use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Page\Http\Requests\PageRequest;
use MicroweberPackages\Page\Repositories\PageRepository;

class PageApiController extends AdminDefaultController
{
    public $page;

    public function __construct(PageRepository $page)
    {
        $this->page = $page;
    }

    /**
     * Display a listing of the product.\
     *
     * @param PageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->page->all();
    }

    /**
     * Store product in database
     * @param PageRequest $request
     * @return mixed
     */
    public function store(PageRequest $request)
    {
        return $this->page->create($request->all());
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->page->find($id);
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
        return $this->page->update($request->all(), $id);
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function delete($id)
    {
        return $this->page->delete($id);
    }

    /**
     * Delete resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        return $this->page->destroy($ids);
    }
}