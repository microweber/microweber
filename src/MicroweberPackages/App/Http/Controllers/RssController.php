<?php

namespace MicroweberPackages\App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use MicroweberPackages\Content\Content;

class RssController extends Controller
{
    public function __construct()
    {
        View::addNamespace('rss', __DIR__.'/../../resources/views/rss');
    }

    public function index(Request $request)
    {

        $view = 'atom';
        if ($request->get('format') == 'wordpress') {
            $view = 'wordpress';
        }

        $lang = $request->get('lang', false);

        $contentData = [];
        if ($this->isMutilangOn()) {
            if ($lang && is_lang_supported($lang)) {
                change_language_by_locale($lang, false);
            } else {
                change_language_by_locale(app()->lang_helper->default_lang(), false);
            }
        }

        $filter = '';
        if ($request->get('parent_id')) {
            $filter .='&parent=' . intval($request->get('parent_id'));
        }

        $cont = get_content('is_active=1&is_deleted=0&limit=2500&orderby=updated_at desc'.$filter);

        $siteTitle = app()->option_manager->get('website_title', 'website');
        $siteDesc = app()->option_manager->get('website_description', 'website');

        if (!empty($cont)) {
            foreach ($cont as $k => $item) {
                $tmp = [];
                $tmp['id'] = $item['id'];
                $tmp['url'] = content_link($item['id']);
                $tmp['title'] = $item['title'];
                $content = content_description($item['id']);;
                if(isset($item['content']) and $item['content']){
                    $content = $item['content'];
                }

                if(isset($item['content_body']) and $item['content_body']){
                    $content = $item['content_body'];
                }
                $content = app()->url_manager->replace_site_url_back($content);
                $tmp['description'] = $content;
                $tmp['tags'] = content_tags($item['id']);
                $tmp['categories'] = content_categories($item['id']);

                $imgUrl = get_picture($item['id']);
                if (!empty($imgUrl)) {
                    $imgData = $this->getFileData($imgUrl);
                    $tmp['image_url'] = $imgUrl;
                    $tmp['image_size'] = $imgData['size'];
                    $tmp['image_type'] = $imgData['type'];
                }

                $contentData[] = $tmp;
            }
        }

        $data = [
            'siteTitle' => $siteTitle,
            'siteDescription' => $siteDesc,
            'siteUrl' => mw()->url_manager->hostname(),
            'rssData' => $contentData,
        ];

        return response()->view('rss::'.$view, $data)->header('Content-Type', 'text/xml');
    }

    public function posts(Request $request)
    {
        $contentData = [];

        if($request->lang && $this->isMutilangOn() && is_lang_supported($request->lang)) {
            change_language_by_locale($request->lang,false);
        }

        $siteTitle = app()->option_manager->get('website_title', 'website');
        $siteDesc = app()->option_manager->get('website_description', 'website');

        $posts = get_content('is_active=1&is_deleted=0&subtype=post&limit=2500&orderby=updated_at desc');

        if(!empty($posts)) {
            foreach($posts as $post) {
                $tmp = [];

                $picture = get_picture($post['id']);
                $priceData = get_product_prices($post['id'], false);
                $price = !empty($priceData['price']) ? $priceData['price'] : null;

                $tmp['title'] = $post['title'];
                $tmp['description'] = $post['description'];
                $tmp['url'] = content_link($post['id']);
                $tmp['image'] = $picture;
                $tmp['price'] = $price;

                $contentData[] = $tmp;
            }
        }

        $data = [
            'siteTitle' => $siteTitle,
            'siteDescription' => $siteDesc,
            'siteUrl' => mw()->url_manager->hostname(),
            'rssData' => $contentData,
        ];

        return response()
            ->view('rss::posts', $data)
            ->header('Content-Type', 'text/xml');
    }

    public function products(Request $request)
    {
        $contentData = [];

        if($request->lang && $this->isMutilangOn() && is_lang_supported($request->lang)) {
            change_language_by_locale($request->lang,false);
        }

        $siteTitle = app()->option_manager->get('website_title', 'website');
        $siteDesc = app()->option_manager->get('website_description', 'website');

        $products = get_content('is_active=1&is_deleted=0&subtype=product&limit=2500&orderby=updated_at desc');

        if(!empty($products)) {
            foreach($products as $product) {
                $tmp = [];

                $picture = get_picture($product['id']);
                $priceData = get_product_prices($product['id'], false);
                $price = !empty($priceData['price']) ? $priceData['price'] : null;

                $tmp['title'] = $product['title'];
                $tmp['description'] = $product['description'];
                $tmp['url'] = content_link($product['id']);
                $tmp['image'] = $picture;
                $tmp['price'] = $price;

                $contentData[] = $tmp;
            }
        }

        $data = [
            'siteTitle' => $siteTitle,
            'siteDescription' => $siteDesc,
            'siteUrl' => mw()->url_manager->hostname(),
            'rssData' => $contentData,
        ];

        return response()
            ->view('rss::products', $data)
            ->header('Content-Type', 'text/xml');
    }

    private function getFileData($urlPath)
    {
        $size = null;
        $type = '';
        $data = @get_headers($urlPath, 1);

        if(isset($data['Content-Length'])) {
            $size = $data['Content-Length'];
        }

        if(isset($data['Content-Type'])) {
            $type = $data['Content-Type'];
        }

        $res = [
            'size' => $size,
            'type' => $type
        ];

        return $res;
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
}
