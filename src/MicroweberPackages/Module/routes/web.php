<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 7/16/2020
 * Time: 2:17 PM
 */

Route::get('website-builder-from-json', function() {

    $websiteBuilderFromJson = new \MicroweberPackages\Module\WebsiteBuilderFromJson();
    $websiteBuilderFromJson->run();

});
