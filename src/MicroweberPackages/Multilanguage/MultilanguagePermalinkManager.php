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

    /**
     * @param $link
     * @param $type
     * @return false|mixed
     *
     * This function detect content from URL
     */
    public function slug($link, $type)
    {
        if (!$link) {
            $link = $this->app->url_manager->current(true);
        }

        $linkSegments = url_segment(-1, $link);
        $linkSegments = array_filter($linkSegments, 'strlen');

        if (empty($linkSegments)) {
            return false;
        }

        $structureMap = $this->getStructuresReadMap();
        foreach ($structureMap as $structureMapIndex => $structureMapItem) {
            if (strpos($structureMapItem, $type) !== false) {
                if (isset($linkSegments[$structureMapIndex])) {

                    $findSlugByType = $linkSegments[$structureMapIndex];
                    $findSlugByType = urldecode($findSlugByType);

                    $relType = 'content';
                    if ($type == 'category') {
                        $relType = 'categories';
                    }
                    if ($relType == 'post' or $relType == 'page' or $relType == 'product') {
                        $relType = 'content';
                    }

                    if ($type == 'category') {
                        $findCategoryBySlug = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url', $findSlugByType, $relType);
                        if ($findCategoryBySlug && isset($findCategoryBySlug['field_value'])) {
                            return $findCategoryBySlug['field_value'];
                        }
                        // Check original
                        $findCategoryBySlug = get_categories('url=' . $findSlugByType . '&single=1');
                        if ($findCategoryBySlug) {
                            return $findCategoryBySlug['url'];
                        }
                    }

                    if ($type == 'page') {

                        // If page found return slug
                        $findPageBySlug = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url', $findSlugByType, $relType);
                        if ($findPageBySlug && isset($findPageBySlug['field_value'])) {
                            return $findPageBySlug['field_value'];
                        }

                        // If page not found try to find page from category
                        $findCategoryBySlug = get_categories('url=' . $findSlugByType . '&single=1');
                        if ($findCategoryBySlug) {
                            $findCategoryPage = get_page_for_category($findCategoryBySlug['id']);
                            if ($findCategoryPage && isset($findCategoryPage['url'])) {
                                return $findCategoryPage['url'];
                            }
                        }

                        // If page not fond & category not found we try to find post
                        $findPostBySlug = get_content('subtype=post&url=' . $findSlugByType . '&single=1');
                        if ($findPostBySlug && isset($findPostBySlug['parent']) && $findPostBySlug['parent'] != false) {
                            //  $findPostPageBySlug = get_pages('id=' . $findPostBySlug['parent'] . '&single=1');
                            $findPostPageBySlug = app()->content_repository->getById($findPostBySlug['parent']);
                            if ($findPostPageBySlug) {
                                return $findPostPageBySlug['url'];
                            }
                        }

                        // If page found return slug
                        $findPageBySlug = get_pages('url=' . $findSlugByType . '&single=1');
                        if ($findPageBySlug) {
                            return $findPageBySlug['url'];
                        }
                    }

                    if ($type == 'post') {
                        $findPostsBySlug = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url', $findSlugByType, $relType);
                        if ($findPostsBySlug && isset($findPostsBySlug['field_value'])) {
                            return $findPostsBySlug['field_value'];
                        }

                        // Check original
                        $findPostsBySlug = get_content('subtype=post&url=' . $findSlugByType . '&single=1');
                        if ($findPostsBySlug) {
                            return $findPostsBySlug['url'];
                        }
                        $findPostsBySlug = get_content('url=' . $findSlugByType . '&single=1');
                        if ($findPostsBySlug && isset($findPostsBySlug['content_type']) && $findPostsBySlug['content_type'] != 'page') {
                            return $findPostsBySlug['url'];
                        }
                    }


                    if ($type == 'content') {
                        $findContentBySlug = app()->multilanguage_repository->getTranslationByFieldNameFieldValueAndRelType('url', $findSlugByType, $relType);
                        if ($findContentBySlug && isset($findContentBySlug['field_value'])) {
                            return $findContentBySlug['field_value'];
                        }

                        //Check original
                        $findContentBySlug = get_content('url=' . $findSlugByType . '&single=1');
                        if ($findContentBySlug) {
                            return $findContentBySlug['url'];
                        }
                    }
                }
            }
        }

        return false;
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

            $originalSlug = $content->getOriginal('url');
            if ($this->language) {
                if (isset($content->multilanguage[$this->language]['url'])) {
                    $originalSlug = $content->multilanguage[$this->language]['url'];
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
            }
        }

        $link['original_slug'] = $originalSlug;

        self::$_linkContent[$this->language][$contentId] = $link;

        return $link;
    }

    public function linkCategory($categoryId)
    {
        $link = [];

        $category = $this->app->category_repository->findById($categoryId);
        if ($category) {
            switch ($this->structure) {
                case 'category_post':
                    /// do nothing
                    break;
                case 'page_post':
                case 'post':
                case 'page_category_post':
                case 'page_category_sub_categories_post':
                    $pageCategory = $this->app->category_manager->get_page($categoryId);
                    if ($pageCategory) {
                        $pageId = $pageCategory['id'];
                        $pageCategory = app()->content_repository->findById($pageId);
                        if ($pageCategory != null) {
                            if (isset($pageCategory->multilanguage[$this->language]['url'])) {
                                $link[] = $pageCategory->multilanguage[$this->language]['url'];
                            } else {
                                $link[] = $pageCategory->getOriginal('url');
                            }
                        }
                    }
                    break;
            }
            if (isset($category->multilanguage[$this->language]['url'])) {
                $link['original_slug'] = $category->multilanguage[$this->language]['url'];
            } else {
                $link['original_slug'] = $category->getOriginal('url');
            }
        }

        return $link;
    }

    public function getCategorySlugForPost($postId)
    {
        $slug = false;
        $categories = get_categories_for_content($postId);

        if ($categories && isset($categories[0])) {
            $main_cat = $selected_cat = $categories[0];
            foreach ($categories as $category) {
                if (isset($category['parent_id']) and isset($main_cat['id']) and $category['parent_id'] == $main_cat['id']) {
                    $selected_cat = $category;
                }
            }

            if ($selected_cat and isset($selected_cat['id'])) {
                $selected_cat = app()->category_repository->findById($selected_cat['id']);
                if ($selected_cat != null) {
                    $selected_cat_multilanguage = (array)$selected_cat->multilanguage;
                    if (isset($selected_cat_multilanguage[$this->language]['url'])) {
                        $slug = $selected_cat_multilanguage[$this->language]['url'];
                    } else {
                        $slug = $selected_cat->getOriginal('url');
                    }
                }
            }
        }

        return $slug;
    }

    public function clearCache()
    {
        self::$__getLinkAfterLocaleSettings = [];
        self::$_linkContent = [];
    }

    private static $__getLinkAfterLocaleSettings = [];

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
            } elseif (!empty(self::$__getLinkAfterLocaleSettings[$currentLang]['locale'])) {
                $currentLang = self::$__getLinkAfterLocaleSettings[$currentLang]['locale'];
            }
        }

        return $currentLang;
    }

    public function getStructures()
    {
        return array(
            'post' => 'sample-post',
        );
    }
}
