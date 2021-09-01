<?php

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

    public function linkContent($contentId)
    {
        $link = [];

        $content = \MicroweberPackages\Content\Content::find($contentId);
        //$content = app()->content_repository->findById($contentId);
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
                        $postParentPage = get_pages('id=' . $content['parent'] . '&single=1');
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
                        $postParentPage = get_pages('id=' . $content['parent'] . '&single=1');
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

        return $link;
    }

    public function __getLinkAfter()
    {
        $rewriteUrl = false;
        $defaultLang = get_option('language', 'website');

        $currentLang = $this->language;

        if ($defaultLang !== $currentLang) {
            $rewriteUrl = true;
        }

        // needs fix
        $prefixForAll = 'y';
        if ($prefixForAll == 'y') {
            $rewriteUrl = true;
        }
        if ($rewriteUrl) {
            // Display locale
            $localeSettings = app()->multilanguage_repository->getSupportedLocaleByLocale($currentLang);

            if ($localeSettings !== null) {
                if (!empty($localeSettings['display_locale'])) {
                    $currentLang = $localeSettings['display_locale'];
                } elseif (!empty($localeSettings['locale']))  {
                    $currentLang = $localeSettings['locale'];
                }
            }
        }

        if ($rewriteUrl) {
            return $currentLang;
        }
    }
}
