<?php

namespace Modules\Sitemap\Http\Controllers;


use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Category\Models\Category;
use Modules\Page\Models\Page;
use Modules\Post\Models\Post;
use Modules\Product\Models\Product;

trait SitemapHelpersTrait
{
    public function isMutilangOn()
    {

        return  MultilanguageHelpers::multilanguageIsEnabled();

    }

    public function fetchTagsLinks()
    {
        $pages = Page::active()->get();
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
                                'id'=>$page->id,
                                'original_link' => "{$siteUrl}{$lang['locale']}/{$page->url}/tags:{$tag['tag_slug']}",
                                'updated_at' => $page->updated_at->format('Y-m-d H:i:s'),
                            ];

                            $tagsData[] = $tmp;
                        }
                    } else {
                        $tmp = [
                            'id'=>$page->id,
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

    public function fetchProductsLinks()
    {
        if($this->isMutilangOn()) {
            $productsData = $this->fetchMultilangContentByType('product');
        } else {
            $productsData = $this->fetchNotMutilangProducts();
        }

        return $productsData;
    }

    public function fetchPagesLinks()
    {
        if($this->isMutilangOn()) {
            $pagesData = $this->fetchMultilangContentByType('page');
        } else {
            $pagesData = $this->fetchNotMutilangPosts();
        }
        return $pagesData;
    }

    public function fetchPostsLinks()
    {
        if($this->isMutilangOn()) {
            $postsData = $this->fetchMultilangContentByType('post');
        } else {
            $postsData = $this->fetchNotMutilangPosts();
        }

        return $postsData;
    }

    public function fetchCategoriesLinks()
    {
        if($this->isMutilangOn()) {
            $categoriesData = multilanguage_get_all_category_links();
        } else {
            $categoriesData = $this->fetchNotMutilangCategories();
        }

        return $categoriesData;
    }

    public function fetchNotMutilangCategories()
    {
        $categories = Category::all();
        $categoriesLinksData = [];

        foreach($categories as $cat) {
            $tmp = [
                'id'=>$cat->id,
                'original_link' => $cat->link(),
                'updated_at' => $cat->updated_at->format('Y-m-d H:i:s'),
                'priority' => '0.6',
                'changefreq' => 'weekly',
            ];

            $categoriesLinksData[] = $tmp;
        }

        return $categoriesLinksData;
    }

    public function fetchNotMutilangPosts()
    {
        $posts = Post::active()->get();
        $postsLinksData = [];

        foreach($posts as $post) {
            $tmp = [
                'id' => $post->id,
                'original_link' => $post->link(),
                'updated_at' => $post->updated_at->format('Y-m-d H:i:s'),
            ];

            $postsLinksData[] = $tmp;
        }

        return $postsLinksData;
    }

    public function fetchNotMutilangProducts()
    {
        $products = Product::active()->get();
        $productsLinksData = [];

        foreach($products as $prod) {
            $tmp = [
                'id' => $prod->id,
                'original_link' => $prod->link(),
                'updated_at' => $prod->updated_at->format('Y-m-d H:i:s'),
            ];

            $productsLinksData[] = $tmp;
        }

        return $productsLinksData;
    }

    public function fetchMultilangContentByType($type)
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

    /**
     * Should the sitemap file at $sitemapFileLocation be regenerated?
     *
     * Returns true when the file is missing, unreadable, or older than
     * 3 hours. The 3-hour TTL is documented in
     * docs/modules/sitemap/usage.md#cache-behaviour and matches the
     * convention search-engine crawlers typically use.
     *
     * Earlier versions short-circuited with `return true;` at the top of
     * this method — effectively disabling the cache. The Sitemap module's
     * AI-333 docs flagged the short-circuit as a known bug with a
     * one-line fix; removing the early return activates the documented
     * TTL behaviour.
     */
    public function needToUpdateSitemap($sitemapFileLocation)
    {
        if (! is_file($sitemapFileLocation)) {
            return true;
        }

        $filelastmodified = @filemtime($sitemapFileLocation);
        if ($filelastmodified === false) {
            // Unreadable / permission issue — regenerate to be safe
            return true;
        }

        // The 3-hour TTL — file older than this needs regeneration
        return (time() - $filelastmodified) > (3 * 3600);
    }
}
