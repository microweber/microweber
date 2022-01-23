<?php

namespace MicroweberPackages\App\Http\Controllers\Traits;


use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;

trait SitemapHelpersTrait
{
    private function isMutilangOn()
    {
        if (is_module('multilanguage')
            && get_option('is_active', 'multilanguage_settings') === 'y'
            && function_exists('multilanguage_get_all_category_links'))
        {
            $res = true;
        } else {
            $res = false;
        }

        return $res;
    }

    private function fetchTagsLinks()
    {
        $pages = Page::where('is_active',1)->where('is_deleted',0)->get();
        $tagsData = [];
        $siteUrl = site_url();

        foreach($pages as $page) {
            $contentTags = content_tags($page->id, true);

            if(!empty($contentTags)) {
                foreach($contentTags as $index => $tag) {

                    if($this->isMutilangOn()) {
                        $allActiveLangs = get_supported_languages(true);

                        foreach($allActiveLangs as $index => $lang) {
                            $tmp = [
                                'original_link' => "{$siteUrl}{$lang['locale']}/{$page->url}/tags:{$tag['tag_slug']}",
                                'updated_at' => $page->updated_at->format('Y-m-d H:i:s'),
                            ];

                            $tagsData[] = $tmp;
                        }
                    } else {
                        $tmp = [
                            'original_link' => "{$siteUrl}{$page->url}/tags:{$tag['tag_slug']}",
                            'updated_at' => $page->updated_at->format('Y-m-d H:i:s'),
                        ];

                        $tagsData[] = $tmp;
                    }
                }
            }
        }

        return $tagsData;
    }

    private function fetchProductsLinks()
    {
        if($this->isMutilangOn()) {
            $productsData = $this->fetchMultilangContentByType('product');
        } else {
            $productsData = $this->fetchNotMutilangProducts();
        }

        return $productsData;
    }

    private function fetchPagesLinks()
    {
        if($this->isMutilangOn()) {
            $pagesData = $this->fetchMultilangContentByType('page');
        } else {
            $pagesData = $this->fetchNotMutilangPosts();
        }
        return $pagesData;
    }

    private function fetchPostsLinks()
    {
        if($this->isMutilangOn()) {
            $postsData = $this->fetchMultilangContentByType('post');
        } else {
            $postsData = $this->fetchNotMutilangPosts();
        }

        return $postsData;
    }

    private function fetchCategoriesLinks()
    {
        if($this->isMutilangOn()) {
            $categoriesData = multilanguage_get_all_category_links();
        } else {
            $categoriesData = $this->fetchNotMutilangCategories();
        }

        return $categoriesData;
    }

    private function fetchNotMutilangCategories()
    {
        $categories = Category::all();
        $categoriesLinksData = [];

        foreach($categories as $cat) {
            $tmp = [
                'original_link' => $cat->link(),
                'updated_at' => $cat->updated_at->format('Y-m-d H:i:s'),
            ];

            $categoriesLinksData[] = $tmp;
        }

        return $categoriesLinksData;
    }

    private function fetchNotMutilangPosts()
    {
        $posts = Post::all();
        $postsLinksData = [];

        foreach($posts as $post) {
            $tmp = [
                'original_link' => $post->link(),
                'updated_at' => $post->updated_at->format('Y-m-d H:i:s'),
            ];

            $postsLinksData[] = $tmp;
        }

        return $postsLinksData;
    }

    private function fetchNotMutilangProducts()
    {
        $products = Product::all();
        $productsLinksData = [];

        foreach($products as $prod) {
            $tmp = [
                'original_link' => $prod->link(),
                'updated_at' => $prod->updated_at->format('Y-m-d H:i:s'),
            ];

            $productsLinksData[] = $tmp;
        }

        return $productsLinksData;
    }

    private function fetchMultilangContentByType($type)
    {
        $allContentLinks = multilanguage_get_all_content_links();
        $items = [];
        foreach($allContentLinks as $link) {
            if(isset($link['item']) and isset($link['item']['content_type'] ) and $link['item']['content_type'] == $type) {
                $items[] = $link;
            }
        }

        return $items;
    }

    private function needToUpdateSitemap($sitemapFileLocation)
    {
        return true;

        $updateSitemap = false;

        if (is_file($sitemapFileLocation)) {
            $filelastmodified = filemtime($sitemapFileLocation);
            // The file is old
            if ((time() - $filelastmodified) > 3 * 3600) {
                $updateSitemap = true;
            }
        } else {
            $updateSitemap = true; //file does not exist
        }

        return $updateSitemap;
    }
}
