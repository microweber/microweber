<?php



namespace MicroweberPackages\Product\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ProductJsonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request)
    {
          return [
            "success" => true,
            "data" =>   $this->resource ,
        ];
    }
}