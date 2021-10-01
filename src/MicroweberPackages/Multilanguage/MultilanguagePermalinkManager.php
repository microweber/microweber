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

        $content = app()->content_repository->findById($contentId);
        if ($content) {

            if ($content['content_type'] == 'page') {
                $link['original_slug'] = $content['url'];
                if ($this->language) {
                    if (isset($content->multilanguage[$this->language]['url'])) {
                        $link['original_slug'] = $content->multilanguage[$this->language]['url'];
                    }
                }
            }

            if ($content['content_type'] != 'page') {
                if ($this->structure == 'page_post') {
                    if (isset($content['parent']) && $content['parent'] != 0) {
                     //   $postParentPage = get_pages('id=' . $content['parent'] . '&single=1');
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
                      //  $postParentPage = get_pages('id=' . $content['parent'] . '&single=1');
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

                $link['original_slug'] = $content['url'];
                if ($this->language) {
                    if (isset($content->multilanguage[$this->language]['url'])) {
                        $link['original_slug'] = $content->multilanguage[$this->language]['url'];
                    }
                }
            }
        }

        self::$_linkContent[$this->language][$contentId] = $link;

        return $link;
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
