<?php

namespace Microweber\Providers;


class PermalinkManager
{
    /** @var \Microweber\Application */
    public $app;

    public $categoryTreeLevel = 3;

    //   'content', 'category', 'categories', 'page', 'post', 'locale'
    public $url_structure = [
        'content',
        'category',
    ];

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

    }


    // @todo refactor this class to use $url_structure





















    /**
     * @deprecated
     */
    public function getStructure()
    {
        $permalinkStructure = get_option('permalink_structure', 'website');
        if ($permalinkStructure == false) {
            $permalinkStructure = 'post';
        }

        return $permalinkStructure;
    }

    /**
     * @deprecated
     *  must be renamed to get_slug
     */
    public function parseLink($link, $type = 'page')
    {
        if (!$link) {
            $link = $this->app->url_manager->current();
        }

        $get = $this->app->event_manager->trigger('permalink.parse_link.link', $link);
        if (is_array($get) && isset($get[0])) {
            $link = $get[0];
        }

        $permalinkStructure = $this->getStructure();

        $linkSegments = url_segment(-1, $link);
        $lastSegment = last($linkSegments);

        if ($permalinkStructure == 'post' || $permalinkStructure == 'page_post') {
            if ($type == 'post') {
                $findPostSlug = $this->_getPostSlugFromUrl($linkSegments);
                $getPost = get_content('url=' . $findPostSlug . '&single=1');
                if ($getPost) {
                    return $getPost['url'];
                }
                return false;
            }

            if ($type == 'category') {
                $findPostSlug = $this->_getPostSlugFromUrl($linkSegments);
                $getPost = get_content('url=' . $findPostSlug . '&single=1');
                if ($getPost) {
                    $getCategory = get_categories_for_content($getPost['id']);;
                    if ($getCategory and isset($getCategory[0])) {
                        return $getCategory[0]['url'];
                    }
                }
                return false;
            }
            if ($type == 'page') {
                return $linkSegments[0];
            }
        }

        if ($permalinkStructure == 'category_post' || $permalinkStructure == 'category_sub_categories_post') {
            if ($type == 'page') {
                $categorySlug = $this->_getCategorySlugFromUrl($linkSegments);
                if ($categorySlug) {
                    $categoryId = get_category_id_from_url($categorySlug);
                    if ($categoryId) {
                        $categoryPage = get_page_for_category($categoryId);
                        if ($categoryPage && isset($categoryPage['url'])) {
                            return $categoryPage['url'];
                        }
                    }
                }

                return $lastSegment;
            }
            if ($type == 'category') {

                // Kogato si v bloga i tursish da wzemesh kategoriq ot url: /blog
                $findCategorySlug = $this->_getCategorySlugFromUrl($linkSegments);
                /*
                var_dump([
                    'inputSegments'=>$linkSegments,
                    'outputCategorySlug'=>$findCategorySlug
                ]);*/

                if ($findCategorySlug) {
                    return $findCategorySlug;
                }

                return false;
            }
            if ($type == 'post') {

                $findPostSlug = $this->_getPostSlugFromUrl($linkSegments);

                /*  var_dump([
                      'inputSegments'=>$linkSegments,
                      'outputPostSlug'=>$findPostSlug
                  ]);*/

                if ($findPostSlug) {
                    return $findPostSlug;
                }

                return false;
            }
        }

        if ($permalinkStructure == 'page_category_post') {

            if (isset($linkSegments[0]) && $type == 'page') {
                return $linkSegments[0];
            }

            if (isset($linkSegments[1]) && $type == 'category') {
                return $linkSegments[1];
            }

            if (isset($linkSegments[2]) && $type == 'post') {
                return $linkSegments[2];
            }
        }
        if ($permalinkStructure == 'page_category_sub_categories_post') {

            if (isset($linkSegments[0]) && $type == 'page') {
                return $linkSegments[0];
            }

            if (isset($linkSegments[0]) && $type == 'post') {

                $findPostSlug = $this->_getPostSlugFromUrl($linkSegments);
                if ($findPostSlug) {
                    return $findPostSlug;
                }

                return false;
            }

            if (isset($linkSegments[1]) && $type == 'category') {

                return $this->_getCategorySlugFromUrl($linkSegments);
            }

            if (isset($linkSegments[1]) && $type == 'categories') {
                $categories = array();
                unset($linkSegments[0]);
                foreach ($linkSegments as $segment) {
                    $categories[] = $segment;
                }
                return $categories;
            }
        }

        return false;
    }
    /**
     * @deprecated
     */
    private function _getPostSlugFromUrl($linkSegments)
    {

        $lastSegment = last($linkSegments);
        $findPost = get_content('url=' . $lastSegment . '&single=1');

        if ($findPost && isset($findPost['content_type']) && $findPost['content_type'] != 'page') {
            return $lastSegment;
        }

        return false;
    }
    /**
     * @deprecated
     */
    private function _getCategorySlugFromUrl($linkSegments)
    {

        $lastSegment = last($linkSegments);

        $findContentByUrl = get_categories('url=' . $lastSegment . '&single=1');
        if ($findContentByUrl) {
            return $lastSegment;
        }

        array_pop($linkSegments);
        $segmentBeforeLast = array_pop($linkSegments);

        $findContentByUrl = get_categories('url=' . $segmentBeforeLast . '&single=1');
        if ($findContentByUrl) {
            return $segmentBeforeLast;
        }

        $override = $this->app->event_manager->trigger('permalink.parse_link.category', $lastSegment);
        if (is_array($override) && isset($override[0])) {
            return $lastSegment;
        }

        return false;
    }

    /**
     * @deprecated
     */
    public function generateLink($content, $advanced = false)
    {
        $outputContent = $content;

        $permalinkStructure = $this->getStructure();

        if ($content['content_type'] != 'page') {

            $generateUrl = '';

            if (strpos($permalinkStructure, 'page_') !== false || $permalinkStructure == 'post') {
                $parentPage = get_pages('id=' . $content['parent'] . '&single=1');
                if ($parentPage) {
                    $generateUrl .= $parentPage['url'] . '/';
                }
            }

            if ($content['content_type'] != 'page' && strpos($permalinkStructure, 'category') !== false) {
                $categories = get_categories_for_content($content['id']);
                if ($categories && isset($categories[0])) {
                    $categories[0] = get_category_by_id($categories[0]['id']);
                    if (strpos($permalinkStructure, 'category_sub_categories') !== false) {
                        if (isset($categories[0]['parent_id']) && $categories[0]['parent_id'] != 0) {
                            $parentCategory = get_category_by_id($categories[0]['parent_id']);
                            if ($parentCategory) {
                                $generateUrl .= $parentCategory['url'] . '/';
                            }
                        }
                    }
                    $generateUrl .= $categories[0]['url'] . '/';
                }
            }

            if ($advanced) {
                $outputContent['slug_prefix'] = $generateUrl;
                $outputContent['slug'] = $outputContent['url'];
            }

            $outputContent['url'] = $generateUrl . $outputContent['url'];
        }

        return $outputContent;
    }
    /**
     * @deprecated
     */
    public function generateContentLink()
    {

    }
    /**
     * @deprecated
     */
    public function generateCategoryLink($categoryId)
    {
        if (intval($categoryId) == 0) {
            return false;
        }

        $categoryId = intval($categoryId);
        $categoryInfo = $this->app->category_manager->get_by_id($categoryId);

        if (!isset($categoryInfo['rel_type'])) {
            return;
        }

        if (trim($categoryInfo['rel_type']) != 'content') {
            return;
        }

        $generateUrl = '';
        $permalinkStructure = $this->getStructure();

        if (strpos($permalinkStructure, 'page_') !== false || $permalinkStructure == 'post' || $permalinkStructure == false) {
            $content = $this->app->category_manager->get_page($categoryId);
            if ($content) {
                $generateUrl .= $this->app->app->content_manager->link($content['id']) . '/';
            }

            if (!$content && defined('PAGE_ID')) {
                $generateUrl .= $this->app->app->content_manager->link(PAGE_ID) . '/';
            }
        }

        if (strpos($permalinkStructure, 'category_sub_categories') !== false) {
            $parentCategoryInfo = $this->app->category_manager->get_by_id($categoryInfo['parent_id']);
            if ($parentCategoryInfo) {
                $generateUrl .= $parentCategoryInfo['url'] . '/';
            }
        }

        $generateUrl .= $categoryInfo['url'];
        if (!stristr($generateUrl, $this->app->url_manager->site())) {
            $generateUrl = site_url($generateUrl);
        }


        $override = $this->app->event_manager->trigger('permalink.generate_category_link', $generateUrl);
        if (is_array($override) && isset($override[0])) {
            return $override[0];
        }

        return $generateUrl;

    }

    public function getStructures()
    {
        return array(
            'post' => 'sample-post',
            'page_post' => 'page/sample-post',
            'category_post' => 'sample-category/sample-post',
            'category_sub_categories_post' => 'sample-category/sub-category/sample-post',
            'page_category_post' => 'sample-page/sample-category/sample-post',
            'page_category_sub_categories_post' => 'sample-page/sample-category/sub-category/sample-post'
        );
    }
}
