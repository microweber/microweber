<?php

namespace Microweber\Providers;


class PermalinkManager
{

    public $categoryTreeLevel = 3;

    public function parseLink($link, $type = 'page')
    {
        if (!$link) {
            $link = app()->url_manager->current();
        }

        $get = mw()->event_manager->trigger('permalink.parse_link.link', $link);
        if (is_array($get) && isset($get[0])) {
            $link = $get[0];
        }

        $link = urldecode($link);
        $linkSegments = url_segment(-1, $link);
        $lastSegment = last($linkSegments);

        $permalinkStructure = get_option('permalink_structure', 'website');

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
            }
            if ($type == 'post') {
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
                return $lastSegment;
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

        return $lastSegment;
    }

    private function _getCategorySlugFromUrl($linkSegments) {

        $lastSegment = last($linkSegments);

        $findContentByUrl = get_categories('url=' . $lastSegment . '&single=1');
        if ($findContentByUrl) {
            return $lastSegment;
        }

        $override = app()->event_manager->trigger('permalink.parse_link.category', $lastSegment);
        if (is_array($override) && isset($override[0])) {
            return $lastSegment;
        }

        array_pop($linkSegments);
        $categoryName = array_pop($linkSegments);

        return $categoryName;
    }

    public function generateLink($content)
    {
        $outputContent = $content;
        $premalinkStructure = get_option('permalink_structure', 'website');

        if ($content['content_type'] != 'page') {

            $generateUrl = '';

            if (strpos($premalinkStructure, 'page_') !== false) {
                $parentPage = get_pages('id=' . $content['parent'] . '&single=1');
                if ($parentPage) {
                    $generateUrl .= $parentPage['url'] . '/';
                }
            }

            if ($content['content_type'] != 'page' && strpos($premalinkStructure, 'category') !== false) {
                $categories = get_categories_for_content($content['id']);
                if ($categories && isset($categories[0])) {
                    $categories[0] = get_category_by_id($categories[0]['id']);
                    if (strpos($premalinkStructure, 'category_sub_categories') !== false) {
                        if (isset($categories[0]['parent_id']) && $categories[0]['parent_id'] != 0) {
                            $parentCategory = get_category_by_id($categories[0]['parent_id']);
                            if ($parentCategory) {
                                $generateUrl .= $parentCategory['url'] . '/';
                            }
                        }

                        $generateUrl .= $categories[0]['url'] . '/';
                    }
                }
            }

            $outputContent['url'] = $generateUrl . $outputContent['url'];
        }

        return $outputContent;
    }

    public function generateContentLink()
    {

    }

    public function generateCategoryLink($categoryId)
    {
        if (intval($categoryId) == 0) {
            return false;
        }

        $categoryId = intval($categoryId);
        $categoryInfo = app()->category_manager->get_by_id($categoryId);

        if (!isset($categoryInfo['rel_type'])) {
            return;
        }

        if (trim($categoryInfo['rel_type']) != 'content') {
            return;
        }

        $generateUrl = '';
        $premalinkStructure = get_option('permalink_structure', 'website');

        if (strpos($premalinkStructure, 'page_') !== false) {
            $content = app()->category_manager->get_page($categoryId);
            if ($content) {
                $generateUrl .= app()->app->content_manager->link($content['id']) . '/';
            }

            if (!$content && defined('PAGE_ID')) {
                $generateUrl .= app()->app->content_manager->link(PAGE_ID) . '/';
            }
        }

        $parentCategoryInfo = app()->category_manager->get_by_id($categoryInfo['parent_id']);
        if ($parentCategoryInfo) {
            $generateUrl .= $parentCategoryInfo['url'] . '/';
        }

        $generateUrl .= $categoryInfo['url'];
        if (!stristr($generateUrl, app()->url_manager->site())) {
            $generateUrl = site_url($generateUrl);
        }

        return $generateUrl;

    }

    public function defineConstants($content)
    {
        if ($content == false) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $ref_page = $_SERVER['HTTP_REFERER'];
                if ($ref_page != '') {
                    $ref_page = strtok($ref_page, '?');
                    if ($ref_page == site_url()) {
                        $ref_page = app()->content_manager->homepage($ref_page);
                    } else {
                        $ref_page = app()->content_manager->get_by_url($ref_page);

                    }
                    if ($ref_page != false and !empty($ref_page)) {
                        $content = $ref_page;
                    }
                }
            }
        }
        $page = false;
        if (is_array($content)) {
            if (!isset($content['active_site_template']) and isset($content['id']) and $content['id'] != 0) {
                $content = app()->content_manager->get_by_id($content['id']);
                $page = $content;
            } elseif (isset($content['id']) and $content['id'] == 0) {
                $page = $content;
            } elseif (isset($content['active_site_template'])) {
                $page = $content;
            }

            if ($page == false) {
                $page = $content;
            }
        }


        app()->event_manager->trigger('content.define_constants', $page);

        if (is_array($page)) {
            if (isset($page['content_type']) and ($page['content_type'] == 'post' or $page['content_type'] != 'page')) {
                if (isset($page['id']) and $page['id'] != 0) {
                    $content = $page;

                    $current_categorys = app()->category_manager->get_for_content($page['id']);
                    if (!empty($current_categorys)) {
                        $current_category = end($current_categorys);

                        if (defined('CATEGORY_ID') == false and isset($current_category['id'])) {
                            define('CATEGORY_ID', intval($current_category['id']));
                        }
                    }

                    $page = app()->content_manager->get_by_id($page['parent']);

                    if (defined('POST_ID') == false) {
                        define('POST_ID', intval($content['id']));
                    }

                    if (is_array($page) and $page['content_type'] == 'product') {
                        if (defined('PRODUCT_ID') == false) {
                            define('PRODUCT_ID', intval($content['id']));
                        }
                    }
                }
            } else {
                $content = $page;
                if (defined('POST_ID') == false) {
                    define('POST_ID', false);
                }
            }

            if (defined('ACTIVE_PAGE_ID') == false) {
                if (!isset($page['id'])) {
                    $page['id'] = 0;
                }
                define('ACTIVE_PAGE_ID', $page['id']);
            }

            if (!defined('CATEGORY_ID')) {
                //define('CATEGORY_ID', $current_category['id']);
            }


            if (defined('CONTENT_ID') == false and isset($content['id'])) {
                define('CONTENT_ID', $content['id']);
            }

            if (defined('PAGE_ID') == false and isset($content['id'])) {
                define('PAGE_ID', $page['id']);
            }

            if (isset($page['parent'])) {
                $parent_page_check_if_inherited = app()->content_manager->get_by_id($page['parent']);

                if (isset($parent_page_check_if_inherited['layout_file']) and $parent_page_check_if_inherited['layout_file'] == 'inherit') {
                    $inherit_from_id = app()->content_manager->get_inherited_parent($parent_page_check_if_inherited['id']);

                    if (defined('MAIN_PAGE_ID') == false) {
                        define('MAIN_PAGE_ID', $inherit_from_id);
                    }
                }

                //$root_parent = $this->get_inherited_parent($page['parent']);

                //  $this->get_inherited_parent($page['id']);
                // if ($par_page != false) {
                //  $par_page = $this->get_by_id($page['parent']);
                //  }
                if (defined('ROOT_PAGE_ID') == false) {
                    $root_page = app()->content_manager->get_parents($page['id']);
                    if (!empty($root_page) and isset($root_page[0])) {
                        $root_page[0] = end($root_page);
                    } else {
                        $root_page[0] = $page['parent'];
                    }

                    define('ROOT_PAGE_ID', $root_page[0]);
                }

                if (defined('MAIN_PAGE_ID') == false) {
                    if ($page['parent'] == 0) {
                        define('MAIN_PAGE_ID', $page['id']);
                    } else {
                        define('MAIN_PAGE_ID', $page['parent']);
                    }
                }
                if (defined('PARENT_PAGE_ID') == false and isset($content['parent'])) {
                    define('PARENT_PAGE_ID', $content['parent']);
                }
                if (defined('PARENT_PAGE_ID') == false) {
                    define('PARENT_PAGE_ID', $page['parent']);
                }
            }
        }

        if (defined('ACTIVE_PAGE_ID') == false) {
            define('ACTIVE_PAGE_ID', false);
        }
        if (defined('CATEGORY_ID') == false) {
            $cat_url = app()->category_manager->get_category_id_from_url();
            if ($cat_url != false) {
                define('CATEGORY_ID', intval($cat_url));
            }
        }
        if (!defined('CATEGORY_ID')) {
            define('CATEGORY_ID', false);
        }


        if (defined('CONTENT_ID') == false) {
            define('CONTENT_ID', false);
        }

        if (defined('POST_ID') == false) {
            define('POST_ID', false);
        }
        if (defined('PAGE_ID') == false) {
            define('PAGE_ID', false);
        }

        if (defined('MAIN_PAGE_ID') == false) {
            define('MAIN_PAGE_ID', false);
        }

        if (isset($page) and isset($page['active_site_template']) and $page['active_site_template'] != '' and strtolower($page['active_site_template']) != 'inherit' and strtolower($page['active_site_template']) != 'default') {
            $the_active_site_template = $page['active_site_template'];
        } elseif (isset($page) and isset($page['active_site_template']) and ($page['active_site_template']) != '' and strtolower($page['active_site_template']) != 'default') {
            $the_active_site_template = $page['active_site_template'];
        } elseif (isset($content) and isset($content['active_site_template']) and ($content['active_site_template']) != '' and strtolower($content['active_site_template']) != 'default') {
            $the_active_site_template = $content['active_site_template'];
        } else {
            $the_active_site_template = app()->option_manager->get('current_template', 'template');
            //
        }

        if (isset($content['parent']) and $content['parent'] != 0 and isset($content['layout_file']) and $content['layout_file'] == 'inherit') {
            $inh = app()->content_manager->get_inherited_parent($content['id']);
            if ($inh != false) {
                $inh_parent = app()->content_manager->get_by_id($inh);
                if (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) != '' and strtolower($inh_parent['active_site_template']) != 'default') {
                    $the_active_site_template = $inh_parent['active_site_template'];
                } elseif (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) != '' and strtolower($inh_parent['active_site_template']) == 'default') {
                    $the_active_site_template = app()->option_manager->get('current_template', 'template');
                } elseif (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) == '') {
                    $the_active_site_template = app()->option_manager->get('current_template', 'template');
                }
            }
        }

        if (isset($the_active_site_template) and $the_active_site_template != 'default' and $the_active_site_template == 'mw_default') {
            $the_active_site_template = 'default';
        }

        if ($the_active_site_template == false) {
            $the_active_site_template = 'default';
        }

        if (defined('THIS_TEMPLATE_DIR') == false and $the_active_site_template != false) {
            define('THIS_TEMPLATE_DIR', templates_path() . $the_active_site_template . DS);
        }

        if (defined('THIS_TEMPLATE_FOLDER_NAME') == false and $the_active_site_template != false) {
            define('THIS_TEMPLATE_FOLDER_NAME', $the_active_site_template);
        }

        $the_active_site_template_dir = normalize_path(templates_path() . $the_active_site_template . DS);

        if (defined('DEFAULT_TEMPLATE_DIR') == false) {
            define('DEFAULT_TEMPLATE_DIR', templates_path() . 'default' . DS);
        }

        if (defined('DEFAULT_TEMPLATE_URL') == false) {
            define('DEFAULT_TEMPLATE_URL', templates_url() . '/default/');
        }

        if (trim($the_active_site_template) != 'default') {
            if ((!strstr($the_active_site_template, DEFAULT_TEMPLATE_DIR))) {
                $use_default_layouts = $the_active_site_template_dir . 'use_default_layouts.php';
                if (is_file($use_default_layouts)) {
                    if (isset($page['layout_file'])) {
                        $template_view = DEFAULT_TEMPLATE_DIR . $page['layout_file'];
                    } else {
                        $template_view = DEFAULT_TEMPLATE_DIR;
                    }
                    if (isset($page)) {
                        if (!isset($page['layout_file']) or (isset($page['layout_file']) and $page['layout_file'] == 'inherit' or $page['layout_file'] == '')) {
                            $par_page = app()->content_manager->get_inherited_parent($page['id']);
                            if ($par_page != false) {
                                $par_page = app()->content_manager->get_by_id($par_page);
                            }
                            if (isset($par_page['layout_file'])) {
                                $the_active_site_template = $par_page['active_site_template'];
                                $page['layout_file'] = $par_page['layout_file'];
                                $page['active_site_template'] = $par_page['active_site_template'];
                                $template_view = templates_path() . $page['active_site_template'] . DS . $page['layout_file'];
                            }
                        }
                    }

                    if (is_file($template_view) == true) {
                        if (defined('THIS_TEMPLATE_DIR') == false) {
                            define('THIS_TEMPLATE_DIR', templates_path() . $the_active_site_template . DS);
                        }

                        if (defined('THIS_TEMPLATE_URL') == false) {
                            $the_template_url = templates_url() . '/' . $the_active_site_template;
                            $the_template_url = $the_template_url . '/';
                            if (defined('THIS_TEMPLATE_URL') == false) {
                                define('THIS_TEMPLATE_URL', $the_template_url);
                            }
                            if (defined('TEMPLATE_URL') == false) {
                                define('TEMPLATE_URL', $the_template_url);
                            }
                        }
                        $the_active_site_template = 'default';
                        $the_active_site_template_dir = DEFAULT_TEMPLATE_DIR;
                    }
                }
            }
        }


        if (defined('ACTIVE_TEMPLATE_DIR') == false) {
            define('ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir);
        }

        if (defined('THIS_TEMPLATE_DIR') == false) {
            define('THIS_TEMPLATE_DIR', $the_active_site_template_dir);
        }

        if (defined('THIS_TEMPLATE_URL') == false) {
            $the_template_url = templates_url() . '/' . $the_active_site_template;

            $the_template_url = $the_template_url . '/';

            if (defined('THIS_TEMPLATE_URL') == false) {
                define('THIS_TEMPLATE_URL', $the_template_url);
            }
        }


        if (defined('TEMPLATE_NAME') == false) {
            define('TEMPLATE_NAME', $the_active_site_template);
        }

        if (defined('TEMPLATE_DIR') == false) {
            define('TEMPLATE_DIR', $the_active_site_template_dir);
        }

        if (defined('ACTIVE_SITE_TEMPLATE') == false) {
            define('ACTIVE_SITE_TEMPLATE', $the_active_site_template);
        }

        if (defined('TEMPLATES_DIR') == false) {
            define('TEMPLATES_DIR', templates_path());
        }

        $the_template_url = templates_url() . $the_active_site_template;

        $the_template_url = $the_template_url . '/';
        if (defined('TEMPLATE_URL') == false) {
            define('TEMPLATE_URL', $the_template_url);
        }

        if (defined('LAYOUTS_DIR') == false) {
            $layouts_dir = TEMPLATE_DIR . 'layouts/';

            define('LAYOUTS_DIR', $layouts_dir);
        } else {
            $layouts_dir = LAYOUTS_DIR;
        }

        if (defined('LAYOUTS_URL') == false) {
            $layouts_url = reduce_double_slashes(app()->url_manager->link_to_file($layouts_dir) . '/');

            define('LAYOUTS_URL', $layouts_url);
        }

        /*var_dump(CATEGORY_ID);
        var_dump(PAGE_ID);*/

        return true;
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
