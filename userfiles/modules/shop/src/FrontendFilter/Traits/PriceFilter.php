<?php
namespace MicroweberPackages\Shop\FrontendFilter\Traits;

trait PriceFilter {

    public function applyQueryPrice()
    {
        $filters = $this->request->get('filters');

        if (isset($filters['from_price']) && isset($filters['to_price'])) {
            $this->query->filter([
                'priceBetween'=> $filters['from_price'] . ',' . $filters['to_price']
            ]);
        }
    }

}
