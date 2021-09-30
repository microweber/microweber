<?php

use MicroweberPackages\App\Http\Controllers\Traits\SitemapHelpersTrait;

api_expose_admin('fullpage-cache-open-iframe', function ($params) {

    $pageOpen = app()->url_manager->download(site_url());

    echo $pageOpen;

});

class FullpageCacheHelper {

    use SitemapHelpersTrait;

    public function getUrls()
    {
        $categories = $this->fetchCategoriesLinks();
        $tags = $this->fetchTagsLinks();
        $posts = $this->fetchPostsLinks();
        $pages = $this->fetchPagesLinks();

        dd($categories);
        die();

    }

}
