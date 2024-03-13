<?php

event_bind('mw.front', function ($params = false) {
    template_foot(function () {

        $contentId = content_id();
        $getContentById = app()->content_manager->get_by_id($contentId);
        if (isset($getContentById['content_type'])) {
            $contentType = $getContentById['content_type'];
            if ($contentType == 'product') {

                $productPrice = 0;
                $productSpecialPrice = 0;
                $productStock = 'OutOfStock';
                $getProductPrice = get_product_price($contentId);
                if ($getProductPrice) {
                    $productPrice = $getProductPrice;
                }
                $getSpecialPrice = get_product_discount_price($contentId);
                if ($getSpecialPrice) {
                    $productSpecialPrice = $getSpecialPrice;
                }
                $getProductStock = is_product_in_stock($contentId);
                if ($getProductStock) {
                    $productStock = 'InStock';
                }

                $schemaOffers = [];
                if ($productPrice) {
                    $schemaOffers[] =  \Spatie\SchemaOrg\Schema::offer()
                        ->price($productPrice)
                        ->availability($productStock)
                        ->priceCurrency(get_currency_code());
                }
                if ($productSpecialPrice) {
                    $schemaOffers[] =  \Spatie\SchemaOrg\Schema::offer()
                        ->price($productSpecialPrice)
                        ->availability($productStock)
                        ->priceCurrency(get_currency_code());
                }

                $graph = new \Spatie\SchemaOrg\Graph();
                $graph
                    ->product()
                    ->offers($schemaOffers)
                    ->name($getContentById['title'])
                    ->brand($graph->organization());

                $graph->hide(\Spatie\SchemaOrg\Organization::class);
                $graph
                    ->organization()
                    ->name(site_hostname());

                return $graph->toScript();
            }

            if ($contentType == 'post') {

                $graph = new \Spatie\SchemaOrg\Graph();
                $graph
                    ->article()
                    ->name($getContentById['title'])
                    ->brand($graph->organization());

                $graph->hide(\Spatie\SchemaOrg\Organization::class);
                $graph
                    ->organization()
                    ->name(site_hostname());

                return $graph->toScript();
            }
        }


    });
});
