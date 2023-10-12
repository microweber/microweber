<?php

namespace MicroweberPackages\App\Managers;

class PermalinkManager
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;
    public $structureMapPrefix = [];
    public $linkAfter = [];
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
    }


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
                        $findPostBySlug = get_content('subtype=post&url=' . $findSlugByType . '&single=1');
                        if ($findPostBySlug && isset($findPostBySlug['parent']) && $findPostBySlug['parent'] != false) {
                          //  $findPostPageBySlug = get_pages('id=' . $findPostBySlug['parent'] . '&single=1');
                            $findPostPageBySlug =  app()->content_repository->getById($findPostBySlug['parent']);
                            if ($findPostPageBySlug) {
                                return $findPostPageBySlug['url'];
                            }
                        }

                        /*   var_dump([
                                'link'=>$link,
                                'type'=>$type,
                                'findSlugByType'=>$findSlugByType,
                                'linkSegments'=>$linkSegments,
                                'structureMapIndex'=>$structureMapIndex
                            ]);*/
                    }

                    if ($type == 'post') {
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
                        $findPostsBySlug = get_content('url=' . $findSlugByType . '&single=1');

                        if ($findPostsBySlug) {
                            return $findPostsBySlug['url'];
                        }
                    }


                    /*
                        * Here it must not return anything if not found slug in database.
                        * Case we brake many cases.
                        *
                        return $findSlugByType;
                    */
                }
            }
        }

        return false;
    }

    public function link($id, $type, $returnSlug = false)
    {
        $segments = [];

        if ($type == 'content') {
            $linkContent = $this->linkContent($id);
            if ($linkContent) {
                $segments = array_merge($segments, $linkContent);
            }
        }

        if ($type == 'category') {
            $linkCategory = $this->linkCategory($id);
            if ($linkCategory) {
                $segments = array_merge($segments, $linkCategory);
            }
        }

        if (empty($segments)) {
            return false;
        }

        if ($this->linkAfter && is_array($this->linkAfter) && !empty($this->linkAfter)) {
            $segments = array_merge($this->linkAfter, $segments);
        }

        $linkImploded = implode('/', $segments);
        $linkFull = site_url($linkImploded);

        if ($returnSlug) {

            $slug = $segments['original_slug'];
            unset($segments['original_slug']);
            $slugPrefix = $segments;

            $slugPrefixReturn = site_url();
            $slugPrefixUrlReturn = site_url();

            if (!empty($slugPrefix)) {
                $slugPrefixReturn = implode('/', $slugPrefix) . '/';
                $slugPrefixUrlReturn = site_url(implode('/', $slugPrefix)) . '/';
            }

            return [
                'url'=> $linkFull,
                'slug_prefix'=>$slugPrefixReturn,
                'slug_prefix_url'=>$slugPrefixUrlReturn,
                'slug'=>$slug
            ];
        }

        return $linkFull;
    }

    public function linkContent($contentId)
    {
        $link = [];

        //$content = get_content('id=' . $contentId . '&single=1');
        $content =  app()->content_repository->getById($contentId);

        if ($content) {

            if ($content['content_type'] == 'page') {
                $link['original_slug'] = $content['url'];
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
            }
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
                $selected_cat = get_category_by_id($selected_cat['id']);
                if ($selected_cat and isset($selected_cat['url'])) {
                    $slug = $selected_cat['url'];
                }
            }
        }

        return $slug;
    }

    public function linkCategory($categoryId)
    {
        $link = [];

        $category = get_category_by_id($categoryId);
        if ($category) {

            switch ($this->structure) {
                case 'page_post':
                case 'post':
                // case 'category_post':
                case 'page_category_post':
                case 'page_category_sub_categories_post':
                    $pageCategory = $this->app->category_manager->get_page($categoryId);
                    if ($pageCategory) {
                        $link[] = $pageCategory['url'];
                    }
                    break;
            }

            $link['original_slug'] = $category['url'];
        }

        return $link;
    }

    public function getStructuresReadMap()
    {
        $structureMap = [];

        if ($this->structureMapPrefix && is_array($this->structureMapPrefix) && !empty($this->structureMapPrefix)) {
            $structureMap = array_merge($this->structureMapPrefix, $structureMap);
        }

        if ($this->structure == 'post') {
            $structureMap[] = 'page|post'; // page category or post
            $structureMap[] = 'category'; // page category or post
        }

        if ($this->structure == 'page_post') {
            $structureMap[] = 'page';
            $structureMap[] = 'category|post';
        }

        if ($this->structure == 'category_post') {
            $structureMap[] = 'page|category|post'; // page category or post
            $structureMap[] = 'post';
        }

        if ($this->structure == 'page_category_post') {
            $structureMap[] = 'page';
            $structureMap[] = 'category|post';
            $structureMap[] = 'post';
        }

        return $structureMap;
    }

    public function getStructures()
    {
        return array(
            'post' => 'sample-post',
            'page_post' => 'page/sample-post',
            'category_post' => 'sample-category/sample-post',
           // 'category_sub_categories_post' => 'sample-category/sub-category/sample-post',
            'page_category_post' => 'sample-page/sample-category/sample-post',
            //'page_category_sub_categories_post' => 'sample-page/sample-category/sub-category/sample-post'
        );
    }
    public function clearCache()
    {
        //...
    }


}
