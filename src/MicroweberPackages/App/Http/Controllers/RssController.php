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

        $showImages = $request->images;
        $view = 'atom';
        if ($request->get('format') == 'wordpress') {
            $view = 'wordpress';
            $showImages = 1;
        }

        $contentData = [];
        if($request->lang && $this->isMutilangOn() && is_lang_supported($request->lang)) {
            change_language_by_locale($request->lang,false);
        }

        $cont = get_content('is_active=1&is_deleted=0&limit=2500&orderby=updated_at desc');

        $siteTitle = app()->option_manager->get('website_title', 'website');
        $siteDesc = app()->option_manager->get('website_description', 'website');

        if (!empty($cont)) {
            foreach ($cont as $k => $item) {
                $tmp = [];
                $tmp['id'] = $item['id'];
                $tmp['url'] = content_link($item['id']);
                $tmp['title'] = $item['title'];
                $tmp['description'] = content_description($item['id']);
                $tmp['tags'] = content_tags($item['id']);
                $tmp['categories'] = content_categories($item['id']);

                if ($showImages == 1) {
                    $imgUrl = get_picture($item['id']);
                    if (!empty($imgUrl)) {
                        $imgData = $this->getFileData($imgUrl);

                        $tmp['image_url'] = $imgUrl;
                        $tmp['image_size'] = $imgData['size'];
                        $tmp['image_type'] = $imgData['type'];
                    }
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
