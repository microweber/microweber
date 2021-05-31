<?php
namespace MicroweberPackages\Shop\FrontendFilter\Traits;

trait PriceFilter {

    public function applyQueryPrice()
    {
        $minPrice = $this->request->get('min_price', false);
        $maxPrice = $this->request->get('max_price', false);

        if ($minPrice && $maxPrice) {
            $this->query->filter([
                'priceBetween'=> $minPrice . ',' . $maxPrice
            ]);
        }
    }

}
