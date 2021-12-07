<?php

namespace MicroweberPackages\App\Http\Controllers;

use Illuminate\Support\Facades\View;
use MicroweberPackages\App\Http\Controllers\Traits\SitemapHelpersTrait;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Queue\Events\ProcessQueueEvent;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Product\Models\Product;

class SitemapController extends Controller
{
    use SitemapHelpersTrait;

   public function __construct()
   {
       View::addNamespace('sitemap', __DIR__.'/../../resources/views/sitemap');
   }

    public function index()
   {
       event(new ProcessQueueEvent());

       return response()->view('sitemap::index')->header('Content-Type', 'text/xml');
   }

   public function categories()
   {
       $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_categories_sitemap.xml';

       $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

       if($updateSitemap) {
           $categoriesData = $this->fetchCategoriesLinks();
           $sitemap = view('sitemap::items', ['itemsData' => $categoriesData])->render();
           file_put_contents($generatedSiteMapFile, $sitemap);
       }

       return response(file_get_contents($generatedSiteMapFile), 200)->header('Content-Type', 'text/xml');

   }

   public function tags()
   {
       $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_tags_sitemap.xml';
       $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

       if($updateSitemap) {

           $tagsData = $this->fetchTagsLinks();

           if(!empty($tagsData)) {
                $sitemap = view('sitemap::items', ['itemsData' => $tagsData])->render();
           } else {
               //If user have not used tags sitemap is just empty (no need to check !empty in sitemap::items)
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

       if($updateSitemap) {

           $productsData = $this->fetchProductsLinks();

           $sitemap = view('sitemap::items', ['itemsData' => $productsData])->render();

           file_put_contents($generatedSiteMapFile, $sitemap);
       }

       return response(file_get_contents($generatedSiteMapFile), 200)->header('Content-Type', 'text/xml');
   }

   public function posts()
   {
       $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_posts_sitemap.xml';

       $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

       if($updateSitemap) {

          $postsData = $this->fetchPostsLinks();

           $sitemap = view('sitemap::items', ['itemsData' => $postsData])->render();

           file_put_contents($generatedSiteMapFile, $sitemap);
       }

       return response(file_get_contents($generatedSiteMapFile), 200)->header('Content-Type', 'text/xml');
   }

   public function pages()
   {
       $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_pages_sitemap.xml';

       $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

       if($updateSitemap) {

           $pagesData = $this->fetchPagesLinks();

           $sitemap = view('sitemap::items', ['itemsData' => $pagesData])->render();

           file_put_contents($generatedSiteMapFile, $sitemap);
       }

       return response(file_get_contents($generatedSiteMapFile), 200)->header('Content-Type', 'text/xml');
   }

}
