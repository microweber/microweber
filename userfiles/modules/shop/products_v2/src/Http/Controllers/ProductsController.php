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
        $customFields = $request->get('customFields');

        $filter = [];
        if ($orderBy) {
            $filter['orderBy'] = $orderBy;
        }
        if ($priceBetween) {
            $priceBetween = str_replace('%2C', ',', $priceBetween);
            $filter['priceBetween'] = $priceBetween;
        }

        $getProductsQuery = \MicroweberPackages\Product\Models\Product::query();

        if ($customFields) {
            $getProductsQuery->whereCustomField($customFields);
        }

        $getProductsQuery->filter($filter);
        $getProducts = $getProductsQuery->paginate($limit)->withQueryString();

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

        $pagesCount = ceil($getProducts->total() / $limit);

        return $this->view(false,
            [
                'data'=>$data,
                'pages_count'=>$pagesCount,
                'paging_param'=>'page',
                'pagination'=>$getProducts->links('pagination::bootstrap-4')
            ]
        );
    }

}
