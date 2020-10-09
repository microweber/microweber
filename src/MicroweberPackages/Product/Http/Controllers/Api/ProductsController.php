<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Product\Http\Controllers\Api;

use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Product\Http\Requests\ProductRequest;
use MicroweberPackages\Product\Repositories\ProductRepository;

class ProductsController extends AdminDefaultController
{
    public $product;

    public function __construct(ProductRepository $product)
    {

        $this->product = $product;
    }

    /**
     * Display a listing of the product.\
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->product->all();
    }

    /**
     * Store product in database
     * @param ProductRequest $request
     * @return mixed
     */
    public function store(ProductRequest $request)
    {
        return $this->product->create($request->all());
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->product->find($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ProductRequest  $request
     * @param  string  $id
     * @return Response
     */
    public function update(ProductRequest $request, $id)
    {
        return $this->product->update($request->all(), $id);
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
      */
    public function delete($id)
    {
        return $this->product->delete($id);
    }

    /**
     * Delete resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        return $this->product->destroy($ids);
    }
}