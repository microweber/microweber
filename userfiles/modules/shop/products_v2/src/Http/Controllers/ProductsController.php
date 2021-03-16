<?php

namespace MicroweberPackages\Shop\Products\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\ModuleFrontController;
use MicroweberPackages\App\Http\Controllers\Traits\ContentSchemaOrg;
use MicroweberPackages\App\Http\Controllers\Traits\ContentShowFields;
use MicroweberPackages\App\Http\Controllers\Traits\ContentThumbnailSize;

class ProductsController extends ModuleFrontController
{
    use ContentThumbnailSize, ContentSchemaOrg, ContentShowFields;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $defaultLimit = 20;
        if (isset($this->moduleOptions['data-limit'])) {
            $defaultLimit = $this->moduleOptions['data-limit'];
        }

        $limit = (int) $request->get('limit', $defaultLimit);
        $orderBy = $request->get('orderBy');
        $priceBetween = $request->get('priceBetween');

        $filter = [];
        if ($orderBy) {
            $filter['orderBy'] = $orderBy;
        }
        if ($priceBetween) {
            $priceBetween = str_replace('%2C', ',', $priceBetween);
            $filter['priceBetween'] = $priceBetween;
        }

        $getProductsQuery = \MicroweberPackages\Product\Models\Product::query();

        if (isset($_GET['custom_field'])) {
            $getProductsQuery->whereCustomField($_GET['custom_field']);
        }

        $getProductsQuery->filter($filter);
        $getProducts = $getProductsQuery->paginate($limit);

        if ($getProducts->total() == 0) {
            echo 'No products found';
            return;
        }

        $data = [];
        foreach ($getProducts as $product) {

            $mediaUrls = [];
            foreach ($product->media as $media) {
                $mediaUrls[] = $media->filename;
            }

            $img = false;
            if(isset($mediaUrls[0])){
                $img = $mediaUrls[0];
            }

            $data[] = [
                'description'=>'',
                'id' => $product->id,
                'image' => $img,
                'link' => $product->url,
                'title' => $product->title,
                'prices' => [$product->price],
            ];
        }

        return $this->view(false, ['data'=>$data]);
    }

}
