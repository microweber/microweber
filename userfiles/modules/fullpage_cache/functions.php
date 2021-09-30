<?php

use MicroweberPackages\App\Http\Controllers\Traits\SitemapHelpersTrait;

api_expose_admin('fullpage-cache-open-iframe', function ($params) {

    $pageOpen = app()->url_manager->download(site_url());

    echo $pageOpen;

});

class FullpageCacheHelper {

    use SitemapHelpersTrait;

    public function getSlugsWithGroups()
    {
        $categorySlugs = [];
        $categories = $this->fetchCategoriesLinks();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                if (isset($category['multilanguage_links'])) {
                    foreach ($category['multilanguage_links'] as $multilanguageLink) {
                        $multilanguageLink = str_replace(site_url(),'',$multilanguageLink);
                        $categorySlugs[] = $multilanguageLink;
                    }
                } else {
                    $originalLink = str_replace(site_url(),'', $category['original_link']);
                    $categorySlugs[] = $originalLink;
                }
            }
        }

        $tagSlugs = [];
        $tags = $this->fetchTagsLinks();
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                if (isset($tag['multilanguage_links'])) {
                    foreach ($tag['multilanguage_links'] as $multilanguageLink) {
                        $multilanguageLink = str_replace(site_url(),'',$multilanguageLink);
                        $tagSlugs[] = $multilanguageLink;
                    }
                } else {
                    $originalLink = str_replace(site_url(),'', $tag['original_link']);
                    $tagSlugs[] = $originalLink;
                }
            }
        }

        $postSlugs = [];
        $posts = $this->fetchPostsLinks();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if (isset($post['multilanguage_links'])) {
                    foreach ($post['multilanguage_links'] as $multilanguageLink) {
                        $multilanguageLink = str_replace(site_url(),'',$multilanguageLink);
                        $postSlugs[] = $multilanguageLink;
                    }
                } else {
                    $originalLink = str_replace(site_url(),'', $post['original_link']);
                    $postSlugs[] = $originalLink;
                }
            }
        }

        $pageSlugs = [];
        $pages = $this->fetchPagesLinks();
        if (!empty($pages)) {
            foreach ($pages as $page) {
                if (isset($page['multilanguage_links'])) {
                    foreach ($page['multilanguage_links'] as $multilanguageLink) {
                        $multilanguageLink = str_replace(site_url(),'',$multilanguageLink);
                        $pageSlugs[] = $multilanguageLink;
                    }
                } else {
                    $originalLink = str_replace(site_url(),'', $page['original_link']);
                    $pageSlugs[] = $originalLink;
                }
            }
        }

        $allSlugs = $categorySlugs;
        $allSlugs = array_merge($allSlugs, $tagSlugs);
        $allSlugs = array_merge($allSlugs, $postSlugs);
        $allSlugs = array_merge($allSlugs, $pageSlugs);

        return [
            'All'=>$allSlugs,
            'Categories'=>$categorySlugs,
            'Tags'=>$tagSlugs,
            'Posts'=>$postSlugs,
            'Pages'=>$pageSlugs
        ];

    }

}
