<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\User\Http\Requests\UserRequest;
use MicroweberPackages\User\Http\Requests\UserCreateRequest;
use MicroweberPackages\User\Http\Requests\UserUpdateRequest;
use MicroweberPackages\User\Repositories\UserRepository;

class UserApiController
{
    public $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
    /**
     * Display a listing of the product.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return (new JsonResource(
            $this->user
                ->filter($request->all())
                ->paginate($request->get('limit', 30))
                ->appends($request->except('page'))

        ))->response();

    }

    /**
     * Store product in database
     *
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserCreateRequest $request)
    {
        $result = $this->user->create($request->all());
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
        $result = $this->user->show($id);

        return (new JsonResource($result))->response();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest $request
     * @param  string $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, $user)
    {

        $result = $this->user->update($request->all(), $user);
        return (new JsonResource($result))->response();
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function delete($id)
    {
        return $this->user->delete($id);
    }

    /**
     * Delete resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        return (new JsonResource(['ids'=>$this->user->destroy($ids)]));
    }
}
