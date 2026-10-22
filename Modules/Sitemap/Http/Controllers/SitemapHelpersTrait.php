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

            // Enrich with product detail: the main image as an OFFICIAL
            // <image:image> entry (crawler-indexed), plus price / categories /
            // tags under a custom namespace that crawlers ignore but our styled
            // XSLT view renders for humans. Everything is optional + guarded so a
            // single product's missing data never breaks the sitemap.
            $tmp = array_merge($tmp, $this->fetchProductSitemapExtras($prod->id));

            $productsLinksData[] = $tmp;
        }

        return $productsLinksData;
    }

    /**
     * Gather the optional product detail (price, categories, tags, image) used
     * to enrich the products sitemap. Keys are only present when data exists.
     *
     * @return array{price?:string, categories?:array<int,string>, tags?:array<int,string>, images?:array<int,string>}
     */
    protected function fetchProductSitemapExtras($contentId): array
    {
        $extra = [];

        try {
            // Price, formatted with the store currency symbol.
            if (function_exists('get_product_price')) {
                $price = get_product_price($contentId);
                if ($price !== false && $price !== null && $price !== '') {
                    $symbol = function_exists('currency_symbol') ? (currency_symbol() ?: '') : '';
                    // "$100.00" for a glyph symbol, "USD 100.00" for a currency code.
                    $sep = ($symbol !== '' && ctype_alpha(substr($symbol, -1))) ? ' ' : '';
                    $extra['price'] = trim($symbol . $sep . number_format((float) $price, 2));
                }
            }

            // Category titles.
            if (function_exists('get_categories_for_content')) {
                $cats = get_categories_for_content($contentId);
                if (is_array($cats) && !empty($cats)) {
                    $titles = [];
                    foreach ($cats as $cat) {
                        $title = is_array($cat) ? ($cat['title'] ?? null)
                            : (is_object($cat) ? ($cat->title ?? null) : null);
                        if (!empty($title)) {
                            $titles[] = $title;
                        }
                    }
                    if (!empty($titles)) {
                        $extra['categories'] = array_values(array_unique($titles));
                    }
                }
            }

            // Tag names.
            if (function_exists('content_tags')) {
                $tags = content_tags($contentId, true);
                if (is_array($tags) && !empty($tags)) {
                    $names = [];
                    foreach ($tags as $tag) {
                        $name = is_array($tag) ? ($tag['tag'] ?? $tag['tag_slug'] ?? null) : null;
                        if (!empty($name)) {
                            $names[] = $name;
                        }
                    }
                    if (!empty($names)) {
                        $extra['tags'] = array_values(array_unique($names));
                    }
                }
            }

            // Main product image as an absolute URL.
            if (function_exists('get_picture')) {
                $picture = get_picture($contentId);
                if (is_string($picture) && $picture !== '') {
                    $extra['images'] = [$this->absolutiseMediaUrl($picture)];
                }
            }
        } catch (\Throwable $e) {
            // Never let an enrichment lookup break sitemap generation.
        }

        return $extra;
    }

    /**
     * Make a (possibly relative or {SITE_URL}-prefixed) media path an absolute URL.
     */
    protected function absolutiseMediaUrl(string $url): string
    {
        $url = str_replace('{SITE_URL}', site_url(), $url);

        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        return rtrim(site_url(), '/') . '/' . ltrim($url, '/');
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
