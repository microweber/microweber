<?php

namespace MicroweberPackages\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\User\Http\Requests\UserCreateRequest;
use MicroweberPackages\User\Http\Requests\UserUpdateRequest;
use MicroweberPackages\User\Models\User;

class UserApiController
{
    /**
     * Display a listing of users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return (new JsonResource(
            User::filter($request->all())
                ->paginate($request->get('limit', 30))
                ->appends($request->except('page'))
        ))->response();
    }

    /**
     * Store user in database.
     *
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserCreateRequest $request)
    {
        $result = User::create($request->all());
        return (new JsonResource($result))->response();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = User::find($id);
        return (new JsonResource($result))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param string $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, $user)
    {
        $record = User::findOrFail($user);
        $record->update($request->all());
        return (new JsonResource($record))->response();
    }

    /**
     * Delete resource by given id.
     *
     * @param string $id
     * @return bool|null
     */
    public function delete($id)
    {
        $record = User::findOrFail($id);
        return $record->delete();
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return JsonResource
     */
    public function destroy($ids)
    {
        return new JsonResource(['ids' => User::destroy($ids)]);
    }
}
