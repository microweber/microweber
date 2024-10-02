<?php

namespace Modules\Sitemap\Http\Controllers;

use Illuminate\Routing\Controller;

class SitemapController extends Controller
{
    use SitemapHelpersTrait;

    public function index()
    {

        return response()->view('modules.sitemap::index')->header('Content-Type', 'text/xml');
    }

    public function categories()
    {
        $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_categories_sitemap.xml';

        $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

        if ($updateSitemap) {
            $categoriesData = $this->fetchCategoriesLinks();
            $sitemap = view('modules.sitemap::items', ['itemsData' => $categoriesData])->render();
            file_put_contents($generatedSiteMapFile, $sitemap);
        }

        return response(file_get_contents($generatedSiteMapFile), 200)->header('Content-Type', 'text/xml');
    }

    public function tags()
    {
        $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_tags_sitemap.xml';
        $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

        if ($updateSitemap) {
            $tagsData = $this->fetchTagsLinks();

            if (!empty($tagsData)) {
                $sitemap = view('modules.sitemap::items', ['itemsData' => $tagsData])->render();
            } else {
                $sitemap = '';
            }

            file_put_contents($generatedSiteMapFile, $sitemap);
        }

        return response(file_get_contents($generatedSiteMapFile), 200)->header('Content-Type', 'text/xml');
    }

    public function products()
    {
        $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_products_sitemap.xml';

        $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

        if ($updateSitemap) {
            $productsData = $this->fetchProductsLinks();
            $sitemap = view('modules.sitemap::items', ['itemsData' => $productsData])->render();
            file_put_contents($generatedSiteMapFile, $sitemap);
        }

        return response(file_get_contents($generatedSiteMapFile), 200)->header('Content-Type', 'text/xml');
    }

    public function posts()
    {
        $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_posts_sitemap.xml';

        $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

        if ($updateSitemap) {
            $postsData = $this->fetchPostsLinks();
            $sitemap = view('modules.sitemap::items', ['itemsData' => $postsData])->render();
            file_put_contents($generatedSiteMapFile, $sitemap);
        }

        return response(file_get_contents($generatedSiteMapFile), 200)->header('Content-Type', 'text/xml');
    }

    public function pages()
    {
        $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_pages_sitemap.xml';

        $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

        if ($updateSitemap) {
            $pagesData = $this->fetchPagesLinks();
            $sitemap = view('modules.sitemap::items', ['itemsData' => $pagesData])->render();
            file_put_contents($generatedSiteMapFile, $sitemap);
        }

        return response(file_get_contents($generatedSiteMapFile), 200)->header('Content-Type', 'text/xml');
    }

    public function getSlugsWithGroups()
    {
        $categorySlugs = [];
        $categories = $this->fetchCategoriesLinks();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                if (isset($category['multilanguage_links'])) {
                    foreach ($category['multilanguage_links'] as $multilanguageLink) {
                        $multilanguageLink = str_replace(site_url(), '', $multilanguageLink['link']);
                        $categorySlugs[] = $multilanguageLink;
                    }
                } else {
                    $originalLink = str_replace(site_url(), '', $category['original_link']);
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
                        $multilanguageLink = str_replace(site_url(), '', $multilanguageLink['link']);
                        $tagSlugs[] = $multilanguageLink;
                    }
                } else {
                    $originalLink = str_replace(site_url(), '', $tag['original_link']);
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
                        $multilanguageLink = str_replace(site_url(), '', $multilanguageLink['link']);
                        $postSlugs[] = $multilanguageLink;
                    }
                } else {
                    $originalLink = str_replace(site_url(), '', $post['original_link']);
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
                        $multilanguageLink = str_replace(site_url(), '', $multilanguageLink['link']);
                        $pageSlugs[] = $multilanguageLink;
                    }
                } else {
                    $originalLink = str_replace(site_url(), '', $page['original_link']);
                    $pageSlugs[] = $originalLink;
                }
            }
        }

        $allSlugs = $categorySlugs;
        $allSlugs = array_merge($allSlugs, $tagSlugs);
        $allSlugs = array_merge($allSlugs, $postSlugs);
        $allSlugs = array_merge($allSlugs, $pageSlugs);
        $allSlugs = array_filter($allSlugs);

        return [
            'all' => $allSlugs,
            'categories' => $categorySlugs,
            'tags' => $tagSlugs,
            'posts' => $postSlugs,
            'pages' => $pageSlugs
        ];
    }
}
