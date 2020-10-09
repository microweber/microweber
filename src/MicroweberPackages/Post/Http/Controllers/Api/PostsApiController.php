<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Post\Http\Controllers\Api;

use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Post\Http\Requests\PostRequest;
use MicroweberPackages\Post\Repositories\PostRepository;

class PostsApiController extends AdminDefaultController
{
    public $post;

    public function __construct(PostRepository $post)
    {
        $this->post = $post;
    }

    /**
     * Display a listing of the product.\
     *
     * @param PostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->post->all();
    }

    /**
     * Store product in database
     * @param PostRequest $request
     * @return mixed
     */
    public function store(PostRequest $request)
    {
        return $this->post->create($request->all());
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->post->find($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PostRequest $request
     * @param  string $id
     * @return Response
     */
    public function update(PostRequest $request, $id)
    {
        return $this->post->update($request->all(), $id);
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function delete($id)
    {
        return $this->post->delete($id);
    }

    /**
     * Delete resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        return $this->post->destroy($ids);
    }

}