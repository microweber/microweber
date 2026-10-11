<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/9/2020
 * Time: 4:52 PM
 */
namespace MicroweberPackages\Multilanguage;

class MultilanguageLinksGenerator
{
    private $_fetched_ml_content;

    public function links($type = 'all', $id = false)
    {
        $links = [];

        $allActiveLangs = get_supported_languages();
        $defaultLang = current_lang();

        foreach($allActiveLangs as $lang) {
            change_language_by_locale($lang['locale'],false);
            $this->generate($type, $id, $lang);
        }

        foreach ($this->_fetched_ml_content as $langLocale=>$fetched_contents) {
            foreach ($fetched_contents as $fetched_content) {
                $multilangUrlsMapId[$fetched_content['item']['id']][$langLocale] = $fetched_content;
            }
        }

        foreach ($multilangUrlsMapId as $contentId=>$contentData) {
            $multiCatLangUrls = [];
            foreach ($contentData as $contentDataLang=>$contentDataDetails) {

                $metaLocale = $contentDataLang;
                $expMetaLocale = explode('_', $metaLocale);
                if (count($expMetaLocale) > 1) {
                    $metaLocale = $expMetaLocale[0];
                }

                $multiCatLangUrls[$contentDataLang] = [
                    'link'=>$contentDataDetails['link'],
                    'slug'=>$contentDataDetails['slug'],
                    'locale'=>$contentDataLang,
                    'meta_locale'=> $metaLocale
                ];
            }

            foreach ($contentData as $contentDataLocale=>$contentDataFull) {
                $links[] = [
                    'original_link'=>$contentDataFull['link'],
                    'item'=>$contentDataFull['item'],
                    'updated_at'=>$contentDataFull['item']['updated_at'],
                    'multilanguage_links'=>$multiCatLangUrls
                ];
            }
        }

        change_language_by_locale($defaultLang,false);

        return $links;
    }

    public function contentLinks($contentId = false)
    {
        return $this->links('content', $contentId);
    }

    public function productLinks($productId = false)
    {
        return $this->links('product', $productId);
    }

    public function postLinks($postId = false)
    {
        return $this->links('post', $postId);
    }

    public function categoryLinks($categoryId = false)
    {

        return $this->links('category', $categoryId);
    }

    private function generate($type ='all', $id = false, $lang = false)
    {
        $generateContent = true;
        $generateCategories = true;

        if ($type == 'category') {
            $generateContent = false;
            $generateCategories = true;
        }

        if ($type == 'content' || $type == 'product' || $type == 'post') {
            $generateContent = true;
            $generateCategories = false;
        }

       if ($generateCategories) {
           $categoryQuery = 'no_limit=1';
           if ($id) {
               $categoryQuery .='&id='.$id;
           }
           $categories = get_categories($categoryQuery);
           foreach ($categories as $category) {
               if (!empty($lang)) {
                   $this->_fetch_link_multilang($category, 'category', $lang);
               }
           }
       }

        if ($generateContent) {
            $contentQuery = 'is_active=1&is_deleted=0&limit=2500&fields=id,content_type,url,updated_at&orderby=updated_at desc';
            if ($id) {
                $contentQuery .='&id='.$id;
            }
            if ($type == 'product') {
                $contentQuery .='&content_type=product';
            }
             if ($type == 'post') {
                $contentQuery .='&content_type=post';
            }
            $cont = get_content($contentQuery);
            if (!empty($cont)) {
                foreach ($cont as $item) {
                    if (!empty($item['content_type']) && !empty($item['url']) && in_array($item['content_type'], ['page', 'product', 'post'])) {

                        if (!empty($lang)) {
                            $this->_fetch_link_multilang($item, 'content', $lang);
                        }
                    }
                }
            }
        }
    }

    private function _fetch_link_multilang($item, $type, $lang)
    {
        if($type === 'category') {
            $link = category_link($item['id']);
        } else if($type === 'content' || $type == 'product' || $type == 'post' || $type == 'page') {
            $link = app()->content_manager->link($item['id']);
        }

        $this->_fetched_ml_content[$lang['locale']][] = [
            'id'=>$item['id'],
            'item'=>$item,
            'type'=>$type,
            'link'=>$link,
            'slug'=>$item['url'],
            'lang'=>$lang
        ];
    }
}
