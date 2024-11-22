<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace Modules\Post\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use Modules\Post\Http\Requests\PostRequest;
use Modules\Post\Repositories\PostApiRepository;

class PostApiController extends AdminDefaultController
{
    public $post;

    public function __construct(PostApiRepository $post)
    {
        $this->post = $post;
    }

    /**
     * Display a listing of the product.\
     *
     * @param PostRequest $request
     * @return JsonResource
     */
    public function index(Request $request)
    {
        return (new JsonResource(
            $this->post
                ->filter($request->all())
                ->paginate($request->get('limit', 30))
                ->appends($request->except('page'))

        ))->response();
    }

    /**
     * Store product in database
     * @param \Modules\Post\Http\Requests\PostRequest $request
     * @return mixed
     */
    public function store(PostRequest $request)
    {
        return (new JsonResource($this->post->create($request->all())));
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return JsonResource
     */
    public function show($id)
    {
        return (new JsonResource($this->post->show($id)));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PostRequest $request
     * @param  string $id
     * @return JsonResource
     */
    public function update(PostRequest $request, $id)
    {
        return (new JsonResource($this->post->update($request->all(), $id)));
    }

    /**
     * Destroy resources by given id.
     * @param string $id
     * @return JsonResource
     */
    public function destroy($id)
    {
        return (new JsonResource(['id'=>$this->post->delete($id)]));
    }
}
