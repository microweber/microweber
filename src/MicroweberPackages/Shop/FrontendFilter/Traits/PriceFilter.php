<?php
namespace MicroweberPackages\Shop\FrontendFilter\Traits;

trait PriceFilter {

    public function appendFiltersActivePrice()
    {
        $minPrice = $this->request->get('min_price', 0.00);
        $maxPrice = $this->request->get('max_price', 100000000000);

        if ($maxPrice) {
            $filter = new \stdClass();
            $filter->name = _e('Price', true) .': '. $minPrice . ' - ' . $maxPrice;
            $filter->link = '';
            $filter->value = $maxPrice;
            $filter->key = 'min_price, max_price';
            $this->filtersActive[] = $filter;
        }
    }

    public function applyQueryPrice()
    {
        $minPrice = $this->request->get('min_price', 0.00);
        $maxPrice = $this->request->get('max_price', 100000000000);

        if ($maxPrice) {
            $this->query->filter([
                'priceBetween'=> $minPrice . ',' . $maxPrice
            ]);
        }
    }

}
