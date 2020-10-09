<?php

namespace MicroweberPackages\Crud\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

trait HasCrudActions
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($this->getRepository()) {
            return $this->getRepository()->all();
        }

        if ($request->has('query')) {
            return $this->getModel()
                //->search($request->get('query'))
                ->query()
                ->limit($request->get('limit', 10))
                ->get();
        }

        return $this->getModel()->all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array_merge([
            $this->getResourceName() => $this->getModel(),
        ]);

        return $data;
    }

    public function store(Request $request)
    {
        if ($this->getValidator()) {
            $request->validate($this->getValidator()->rules());
        }

        if ($this->getRepository()) {
            return $this->getRepository()->create($request->all());
        }

        $entity = $this->getModel()->create($request);

        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo($entity);
        }

        return $entity->id;
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($this->getRepository()) {
            return $this->getRepository()->find($id);
        }

        $entity = $this->getEntity($id);
        if (request()->wantsJson()) {
            return $entity;
        }

        return $entity;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = array_merge([
            $this->getResourceName() => $this->getEntity($id),
        ]);

        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if ($this->getValidator()) {
            $request->validate($this->getValidator()->rules());
        }

        if ($this->getRepository()) {
            return $this->getRepository()->update($request->all(), $id);
        }

        $entity = $this->getEntity($id);
        $entity->update($request);

        return $entity->id;
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function delete($id)
    {
        if ($this->getRepository()) {
            return $this->getRepository()->delete($id);
        }

        $entity = $this->getEntity($id);
        $entity->delete();
    }

    /**
     * Delete resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        if ($this->getRepository()) {
            return $this->getRepository()->destroy($ids);
        }

        $this->getModel()
            ->withoutGlobalScope('active')
            ->whereIn('id', explode(',', $ids))
            ->delete();
    }

    /**
     * Get an entity by the given id.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getEntity($id)
    {

        return $this->getModel()
            ->with($this->relations())
            ->withoutGlobalScope('active')
            ->findOrFail($id);
    }

    /**
     * Get the relations that should be eager loaded.
     *
     * @return array
     */
    private function relations()
    {
        return collect($this->with ?? [])->mapWithKeys(function ($relation) {
            return [$relation => function ($query) {
                return $query->withoutGlobalScope('active');
            }];
        })->all();
    }

    /**
     * Get name of the resource.
     *
     * @return string
     */
    protected function getResourceName()
    {
        if (isset($this->resourceName)) {
            return $this->resourceName;
        }

        return lcfirst(class_basename($this->model));
    }

    /**
     * Get label of the resource.
     *
     * @return void
     */
    protected function getLabel()
    {
        return trans($this->label);
    }

    /**
     * Get route prefix of the resource.
     *
     * @return string
     */
    protected function getRoutePrefix()
    {
        if (isset($this->routePrefix)) {
            return $this->routePrefix;
        }

        return "admin.{$this->getModel()->getTable()}";
    }

    /**
     * Get a new instance of the model.
     *
     * @return void
     */
    protected function getModel()
    {
        if (isset($this->model) && class_exists($this->model)) {
            return new $this->model;
        }

        return false;
    }

    /**
     * Get a new instance of the model.
     *
     * @return void
     */
    protected function getRepository()
    {
        if (isset($this->repository)) {
            return $this->repository;
        }

        return false;
    }

    protected function getValidator()
    {
        if (isset($this->validator) && class_exists($this->validator)) {
            return new $this->validator;
        }

        return false;
    }

    /**
     * Get request object
     *
     * @param string $action
     * @return \Illuminate\Http\Request
     */
    protected function getRequest($action)
    {
        if (! isset($this->validation)) {
            return request();
        }

        if (isset($this->validation[$action])) {
            return resolve($this->validation[$action]);
        }

        return resolve($this->validation);
    }

}
