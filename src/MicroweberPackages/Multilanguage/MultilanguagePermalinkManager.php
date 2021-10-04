<?php
namespace MicroweberPackages\Multilanguage;

class MultilanguagePermalinkManager extends \Microweber\Providers\PermalinkManager
{

    public $language = false;

    public function __construct($language = false)
    {
        parent::__construct();

        if ($language) {
            $this->language = $language;
        } else {
            $this->language = mw()->lang_helper->current_lang();
        }

        $this->structureMapPrefix[] = 'locale';

        $getLinkAfter = $this->__getLinkAfter();

        if ($getLinkAfter) {
            $this->linkAfter[] = $getLinkAfter;
        }
    }


    public static $_linkContent = [];
    public function linkContent($contentId)
    {
        if (isset(self::$_linkContent[$this->language][$contentId])) {
            return self::$_linkContent[$this->language][$contentId];
        }

        $link = [];
        $originalSlug = false;

        $content = app()->content_repository->findById($contentId);
        if ($content) {

            if ($content['content_type'] == 'page') {
                $originalSlug = $content['url'];
                if ($this->language) {
                    if (isset($content->multilanguage[$this->language]['url'])) {
                        $originalSlug = $content->multilanguage[$this->language]['url'];
                    }
                }
            }

            if ($content['content_type'] != 'page') {
                if ($this->structure == 'page_post') {
                    if (isset($content['parent']) && $content['parent'] != 0) {
                        $postParentPage = app()->content_repository->getById($content['parent']);

                        if ($postParentPage) {
                            $link[] = $postParentPage['url'];
                        }
                    }
                }

                if ($this->structure == 'category_post') {
                    $categorySlugForPost = $this->getCategorySlugForPost($content['id']);
                    if ($categorySlugForPost) {
                        $link[] = $categorySlugForPost;
                    }
                }

                if ($this->structure == 'page_category_post') {
                    if (isset($content['parent']) && $content['parent'] != 0) {
                        $postParentPage = app()->content_repository->getById($content['parent']);

                        if ($postParentPage) {
                            $link[] = $postParentPage['url'];
                        }
                    }

                    $categorySlugForPost = $this->getCategorySlugForPost($content['id']);
                    if ($categorySlugForPost) {
                        $link[] = $categorySlugForPost;
                    }
                }

                $originalSlug = $content['url'];
                if ($this->language) {
                    if (isset($content->multilanguage[$this->language]['url'])) {
                        $originalSlug = $content->multilanguage[$this->language]['url'];
                    }
                }
            }
        }

        $link['original_slug'] = $originalSlug;

        self::$_linkContent[$this->language][$contentId] = $link;

        return $link;
    }

    public function linkCategory($categoryId)
    {
        $link = [];

        $category = get_category_by_id($categoryId);
        if ($category) {
            switch ($this->structure) {
                case 'category_post':
                case 'page_post':
                case 'post':
                case 'page_category_post':
                case 'page_category_sub_categories_post':
                    $pageCategory = $this->app->category_manager->get_page($categoryId);
                    $pageId = $pageCategory['id'];
                    $pageCategory = app()->content_repository->findById($pageId);
                    if ($pageCategory != null) {
                        $pageCategoryMultilanguage = (array) $pageCategory->multilanguage;
                        if (isset($pageCategoryMultilanguage[$this->language]['url'])) {
                            $link[] = $pageCategoryMultilanguage[$this->language]['url'];
                        }
                    }
                    break;
            }

            $link['original_slug'] = $category['url'];
        }

        return $link;
    }

    public function getCategorySlugForPost($postId)
    {
        $slug = false;
        $categories = get_categories_for_content($postId);

        if ($categories && isset($categories[0])) {
            $main_cat  = $selected_cat = $categories[0];
            foreach ($categories as $category){
                if(isset($category['parent_id']) and isset($main_cat['id']) and $category['parent_id'] == $main_cat['id']){
                    $selected_cat = $category;
                }
            }

            if ($selected_cat and isset($selected_cat['id'])) {
                $selected_cat = app()->category_repository->findById($selected_cat['id']);
                if ($selected_cat != null) {
                    $selected_cat_multilanguage = (array) $selected_cat->multilanguage;
                    if (isset($selected_cat_multilanguage[$this->language]['url'])) {
                        $slug = $selected_cat_multilanguage[$this->language]['url'];
                    }
                }
            }
        }

        return $slug;
    }

    public function clearCache()
    {
        self::$__getLinkAfterLocaleSettings = false;
        self::$_linkContent = [];
    }

    private static $__getLinkAfterLocaleSettings = false;
    public function __getLinkAfter()
    {
        $currentLang = $this->language;

        // Display locale
        if (!isset(self::$__getLinkAfterLocaleSettings[$currentLang])) {
            self::$__getLinkAfterLocaleSettings[$currentLang] = app()->multilanguage_repository->getSupportedLocaleByLocale($currentLang);
        }

        if (isset(self::$__getLinkAfterLocaleSettings[$currentLang]) && self::$__getLinkAfterLocaleSettings[$currentLang] !== null) {
            if (!empty(self::$__getLinkAfterLocaleSettings[$currentLang]['display_locale'])) {
                $currentLang = self::$__getLinkAfterLocaleSettings[$currentLang]['display_locale'];
            } elseif (!empty(self::$__getLinkAfterLocaleSettings[$currentLang]['locale']))  {
                $currentLang = self::$__getLinkAfterLocaleSettings[$currentLang]['locale'];
            }
        }

        return $currentLang;
    }
}
