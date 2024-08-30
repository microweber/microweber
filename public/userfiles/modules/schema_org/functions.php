<?php

function getSchemaOrgScriptByContentIds($contentIds)
{
    if (!empty($contentIds)) {

        $graph = new \Spatie\SchemaOrg\Graph();

        foreach ($contentIds as $contentId) {
            $graph = getSchemaOrgContentFilled($graph, $contentId);
        }

        if ($graph) {
            return $graph->toScript();
        }

        return '';
    }
}

function getSchemaOrgContentFilled($graph, $contentId)
{
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

            $graph
                ->product($getContentById['url'])
                ->offers($schemaOffers)
                ->url(content_link($contentId))
                ->name($getContentById['title'])
                ->headline($getContentById['title'])
                ->author(user_name($getContentById['created_by']))
                ->image(get_picture($contentId))
                ->description(content_description($contentId))
                ->brand($graph->organization());

            if (method_exists($graph, 'hide')) {
                $graph->hide(\Spatie\SchemaOrg\Organization::class);
            }
            $graph
                ->organization()
                ->name(site_hostname());

            return $graph;
        }

        if ($contentType == 'page') {

            $graph->webPage($getContentById['url'])
                ->url(content_link($contentId))
                ->name($getContentById['title'])
                ->headline($getContentById['title'])
                ->author(user_name($getContentById['created_by']))
                ->image(get_picture($contentId))
                ->description(content_description($contentId))
                ->brand($graph->organization());

            if (method_exists($graph, 'hide')) {
                $graph->hide(\Spatie\SchemaOrg\Organization::class);
            }

            $graph
                ->organization()
                ->name(site_hostname());

            return $graph;
        }

        if ($contentType == 'post') {

            $graph
                ->article($getContentById['url'])
                ->url(content_link($contentId))
                ->name($getContentById['title'])
                ->headline($getContentById['title'])
                ->author(user_name($getContentById['created_by']))
                ->image(get_picture($contentId))
                ->articleBody(content_description($contentId))
                ->brand($graph->organization());

            if (method_exists($graph, 'hide')) {
                $graph->hide(\Spatie\SchemaOrg\Organization::class);
            }

            $graph
                ->organization()
                ->name(site_hostname());

            return $graph;
        }
    }
}

event_bind('mw.front', function ($params = false) {
    template_foot(function () {

        $contentId = content_id();
        if ($contentId) {
            $graph = new \Spatie\SchemaOrg\Graph();
            $graphFilled = getSchemaOrgContentFilled($graph, $contentId);

            if ($graphFilled) {
                return $graphFilled->toScript();
            }
        }

    });
});
