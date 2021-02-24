<?php

namespace MicroweberPackages\App\Http\Controllers;

use Illuminate\Support\Facades\View;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Queue\Events\ProcessQueueEvent;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Product\Models\Product;

class SitemapController extends Controller
{

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
           if($this->isMutilangOn()) {
               $categoriesData = multilanguage_get_all_category_links();
           } else {
               $categoriesData = $this->fetchNotMutilangCategories();
           }

           $sitemap = view('sitemap::items', ['itemsData' => $categoriesData])->render();

           file_put_contents($generatedSiteMapFile, $sitemap);
       }

       $fp = fopen($generatedSiteMapFile, 'r');

       // send the right headers
       header('Content-Type: text/xml');
       header('Content-Length: ' . filesize($generatedSiteMapFile));

       // dump the file and stop the script
       fpassthru($fp);

       //TODO event_trigger('mw_robot_url_hit');
       exit();
   }

   public function tags()
   {
       $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_tags_sitemap.xml';
       $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

       if($updateSitemap) {
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

           if(!empty($tagsData)) {
                $sitemap = view('sitemap::items', ['itemsData' => $tagsData])->render();
           } else {
               //If user have not used tags sitemap is just empty (no need to check !empty in sitemap::items)
               $sitemap = '';
           }

           file_put_contents($generatedSiteMapFile, $sitemap);
       }

       $fp = fopen($generatedSiteMapFile, 'r');

       // send the right headers
       header('Content-Type: text/xml');
       header('Content-Length: ' . filesize($generatedSiteMapFile));

       // dump the file and stop the script
       fpassthru($fp);

       //TODO event_trigger('mw_robot_url_hit');
       exit();
   }

   public function products()
   {
       $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_products_sitemap.xml';

       $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

       if($updateSitemap) {
           if($this->isMutilangOn()) {
               $productsData = $this->fetchMultilangContentByType('product');
           } else {
               $productsData = $this->fetchNotMutilangProducts();
           }

           $sitemap = view('sitemap::items', ['itemsData' => $productsData])->render();

           file_put_contents($generatedSiteMapFile, $sitemap);
       }

       $fp = fopen($generatedSiteMapFile, 'r');

       // send the right headers
       header('Content-Type: text/xml');
       header('Content-Length: ' . filesize($generatedSiteMapFile));

       // dump the file and stop the script
       fpassthru($fp);

       //TODO event_trigger('mw_robot_url_hit');
       exit();
   }

   public function posts()
   {
       $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_posts_sitemap.xml';

       $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

       if($updateSitemap) {
           if($this->isMutilangOn()) {
               $postsData = $this->fetchMultilangContentByType('post');
           } else {
               $postsData = $this->fetchNotMutilangPosts();
           }

           $sitemap = view('sitemap::items', ['itemsData' => $postsData])->render();

           file_put_contents($generatedSiteMapFile, $sitemap);
       }

       $fp = fopen($generatedSiteMapFile, 'r');

       // send the right headers
       header('Content-Type: text/xml');
       header('Content-Length: ' . filesize($generatedSiteMapFile));

       // dump the file and stop the script
       fpassthru($fp);

       //TODO event_trigger('mw_robot_url_hit');
       exit();
   }

   public function pages()
   {
       $generatedSiteMapFile = mw_cache_path() . mw()->url_manager->hostname() . '_pages_sitemap.xml';

       $updateSitemap = $this->needToUpdateSitemap($generatedSiteMapFile);

       if($updateSitemap) {
           if($this->isMutilangOn()) {
               $pagesData = $this->fetchMultilangContentByType('page');
           } else {
               $pagesData = $this->fetchNotMutilangPosts();
           }

           $sitemap = view('sitemap::items', ['itemsData' => $pagesData])->render();

           file_put_contents($generatedSiteMapFile, $sitemap);
       }

       $fp = fopen($generatedSiteMapFile, 'r');

       // send the right headers
       header('Content-Type: text/xml');
       header('Content-Length: ' . filesize($generatedSiteMapFile));

       // dump the file and stop the script
       fpassthru($fp);

       //TODO event_trigger('mw_robot_url_hit');
       exit();
   }

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
