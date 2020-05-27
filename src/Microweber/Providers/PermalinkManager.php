<?php

namespace Microweber\Providers;


use function Clue\StreamFilter\fun;

class PermalinkManager
{
    /** @var \Microweber\Application */
    public $app;
    public $structure = 'post';

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $structure = get_option('permalink_structure', 'website');
        if ($structure) {
            $this->structure = $structure;
        }

        //$this->structure = 'category_post';
    }

    public function slug($link, $type)
    {
        if (!$link) {
            $link = $this->app->url_manager->current();
        }

        $linkSegments = url_segment(-1, $link);
        $linkSegments = array_filter($linkSegments, 'strlen');

        if (empty($linkSegments)) {
            return false;
        }

        $structureMap = $this->getStructuresReadMap();
        foreach ($structureMap as $structureMapIndex=>$structureMapItem) {
           if (strpos($structureMapItem, $type) !== false) {
                if (isset($linkSegments[$structureMapIndex])) {

                    $findSlugByType = $linkSegments[$structureMapIndex];

                    if ($type == 'category') {
                        $findCategoryBySlug = get_categories('url=' . $findSlugByType . '&single=1');
                        if ($findCategoryBySlug) {
                            return $findCategoryBySlug['url'];
                        }
                    }

                    if ($type == 'page') {

                        // If page found return slug
                        $findPageBySlug = get_pages('url=' . $findSlugByType . '&single=1');
                        if ($findPageBySlug) {
                            return $findPageBySlug['url'];
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
                        $findPostBySlug = get_posts('url=' . $findSlugByType . '&single=1');
                        if ($findPostBySlug && isset($findPostBySlug['parent']) && $findPostBySlug['parent'] != false) {
                            $findPostPageBySlug = get_pages('id=' . $findPostBySlug['parent'] . '&single=1');
                            if ($findPostPageBySlug) {
                                return $findPostPageBySlug['url'];
                            }
                        }

                        var_dump([
                            'link'=>$link,
                            'type'=>$type,
                            'findSlugByType'=>$findSlugByType,
                            'linkSegments'=>$linkSegments,
                            'structureMapIndex'=>$structureMapIndex
                        ]);
                    }

                    if ($type == 'post') {
                        $findPostsBySlug = get_posts('url=' . $findSlugByType . '&single=1');
                        if ($findPostsBySlug) {
                            return $findPostsBySlug['url'];
                        }
                    }
                }
            }
        }

        return false;
    }

    public function link($id, $type)
    {
        $link = [];

        if ($type == 'content') {
            $link = $this->_linkContent($id);
        }

        if ($type == 'category') {
            $link = $this->_linkCategory($id);
        }

        if (!$link) {
            return false;
        }

        $link = implode('/', $link);
        $link = site_url($link);

       return $link;
    }

    private function _linkContent($contentId)
    {
        $link = [];

        $content = get_content('id=' . $contentId . '&single=1');
        if ($content) {

            if ($content['content_type'] == 'page') {
                $link[] = $content['url'];
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
                    $categorySlugForPost = $this->_getCategorySlugForPost($content['id']);
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

                    $categorySlugForPost = $this->_getCategorySlugForPost($content['id']);
                    if ($categorySlugForPost) {
                        $link[] = $categorySlugForPost;
                    }
                }

                $link[] = $content['url'];
            }
        }

        return $link;
    }

    private function _getCategorySlugForPost($postId)
    {
        $slug = false;
        $categories = get_categories_for_content($postId);
        if ($categories && isset($categories[0])) {
            $categories[0] = get_category_by_id($categories[0]['id']);
            $slug = $categories[0]['url'];
        }

        return $slug;
    }

    private function _linkCategory($categoryId)
    {
        $link = [];

        $category = get_category_by_id($categoryId);
        if ($category) {

            switch ($this->structure) {
                case 'page_post':
                case 'post':
                case 'category_post':
                case 'page_category_post':
                case 'page_category_sub_categories_post':
                    $pageCategory = $this->app->category_manager->get_page($categoryId);
                    if ($pageCategory) {
                        $link[] = $pageCategory['url'];
                    }
                    break;
            }

            $link[] = $category['url'];
        }

        return $link;
    }

    public function getStructuresReadMap()
    {
        $map = [];
       // $map[] = 'locale';

        if ($this->structure == 'post') {
            $map[] = 'page|category|post'; // page category or post
        }

        if ($this->structure == 'page_post') {
            $map[] = 'page';
            $map[] = 'category|post';
        }

        if ($this->structure == 'category_post') {
            $map[] = 'page|category|post'; // page category or post
            $map[] = 'post|category';
        }

        if ($this->structure == 'page_category_post') {
            $map[] = 'page';
            $map[] = 'category';
            $map[] = 'post';
        }

        return $map;
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