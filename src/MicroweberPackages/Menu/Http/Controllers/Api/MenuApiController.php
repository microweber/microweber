<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Menu\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Menu\Http\Requests\MenuApiRequest;
use MicroweberPackages\Menu\Repositories\MenuApiRepository;

class MenuApiController extends AdminDefaultController
{
    public $menu;

    public function __construct(MenuApiRepository $menu)
    {
        $this->menu = $menu;
        parent::__construct();
    }

    /**
     * Display a listing of the product.\
     *
      * @return JsonResource
     */
    public function index()
    {
        return (new JsonResource($this->menu->all()))->response();
    }

    /**
     * Store menu in database
     * @param MenuApiRequest $request
     * @return mixed
     */
    public function store(MenuApiRequest $request)
    {
        return (new JsonResource($this->menu->create($request->all())));
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return JsonResource
     */
    public function show($id)
    {
        return (new JsonResource($this->menu->show($id)));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  MenuApiRequest $request
     * @param  string $id
     * @return JsonResource
     */
    public function update(MenuApiRequest $request, $id)
    {
        return (new JsonResource($this->menu->update($request->all(), $id)));
    }

    /**
     * Destroy resources by given id.
     * @param int $id
     * @return JsonResource
     */
    public function destroy($id)
    {
        return (new JsonResource(['id'=>$this->menu->delete($id)]));
    }
}
