<?php

namespace Modules\Content\Support;

class BreadcrumbLinks
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }
    public function get($params = false)
    {
        $result = array();
        $cur_page = false;
        $cur_content = false;
        $cur_category = false;
        if (defined('PAGE_ID') and PAGE_ID != false) {
            $cur_page = content_id();
        }
        if (defined('POST_ID') and CONTENT_ID != false) {
            $cur_content = CONTENT_ID;
            if ($cur_content == $cur_page) {
                $cur_content = false;
            }
        }
        if (defined('CATEGORY_ID') and CATEGORY_ID != false) {
            $cur_category = category_id();
        }


        $start_from = false;
        if (isset($params['start_from'])) {
            $start_from = trim($params['start_from']);
        }

        if ($cur_page != false) {
            if ($start_from != 'category') {

                $content = $this->get_by_id($cur_page);
                if (isset($content['id'])) {
                    $result_item = array();
                    $result_item['title'] = $content['title'];
                    $result_item['url'] = $this->link($content['id']);
                    $result_item['description'] = $content['description'];
                    $result_item['is_active'] = false;

                    if ($cur_content == $content['id']) {
                        $result_item['is_active'] = true;
                    } elseif ($cur_content != false and $cur_page == $content['id']) {
                        $result_item['is_active_as_parent'] = true;
                        $result_item['is_active'] = false;
                    } elseif ($cur_category == false and $cur_content == false and $cur_page == $content['id']) {
                        $result_item['is_active'] = true;
                    } else {
                        $result_item['is_active'] = false;
                    }
                    $result_item['parent_content_id'] = $content['parent'];
                    $result_item['content_type'] = $content['content_type'];
                    $result_item['subtype'] = $content['subtype'];
                    $result[] = $result_item;
                }


                $content_parents = $this->get_parents($cur_page);
                if (!empty($content_parents)) {
                    foreach (($content_parents) as $item) {
                        $item = intval($item);
                        if ($item > 0) {
                            $content = $this->get_by_id($item);
                            if (isset($content['id'])) {
                                $result_item = array();
                                $result_item['title'] = $content['title'];
                                $result_item['url'] = $this->link($content['id']);
                                $result_item['description'] = $content['description'];
                                if ($cur_content == $content['id']) {
                                    $result_item['is_active'] = true;
                                } else {
                                    $result_item['is_active'] = false;
                                }
                                $result_item['parent_content_id'] = $content['parent'];
                                $result_item['content_type'] = $content['content_type'];
                                $result_item['subtype'] = $content['subtype'];
                                $result[] = $result_item;
                            }
                        }
                    }
                }

                if ($result) {
                    $result = array_reverse($result);
                }
            }
        }

        if ($cur_category != false) {
            $cur_category_data = $this->app->category_manager->get_by_id($cur_category);
            if ($cur_category_data != false and isset($cur_category_data['id'])) {
                $cat_parents = $this->app->category_manager->get_parents($cur_category);

                if (!empty($cat_parents)) {
                    foreach (($cat_parents) as $item) {
                        $item = intval($item);
                        if ($item > 0) {
                            $content = $this->app->category_manager->get_by_id($item);
                            if (isset($content['id'])) {
                                $result_item = array();
                                $result_item['title'] = $content['title'];
                                $result_item['description'] = $content['description'];

                                if (isset($params['current-page-as-root']) and $params['current-page-as-root'] != false) {
                                    $result_item['url'] = page_link() . '/category:' . $content['id'];
                                } else {
                                    $result_item['url'] = $this->app->category_manager->link($content['id']);
                                }


                                $result_item['content_type'] = 'category';
                                if ($cur_content == false and $cur_category == $content['id']) {
                                    $result_item['is_active'] = true;
                                } else {
                                    $result_item['is_active'] = false;
                                }
                                $result[] = $result_item;
                            }
                        }
                    }
                }
            }
            $content = $cur_category_data;
            if (isset($content['id'])) {
                $result_item = array();
                $result_item['title'] = $content['title'];
                $result_item['description'] = $content['description'];
                $result_item['url'] = $this->app->category_manager->link($content['id']);
                $result_item['content_type'] = 'category';
                if ($cur_content == false and $cur_category == $content['id']) {
                    $result_item['is_active'] = true;
                } else {
                    $result_item['is_active'] = false;
                }
                $result[] = $result_item;
            }
        }

        if ($cur_content != false) {
            $content = $this->get_by_id($cur_content);
            if (isset($content['id'])) {
                $result_item = array();
                $result_item['title'] = $content['title'];
                $result_item['url'] = $this->link($content['id']);
                $result_item['description'] = $content['description'];
                if ($cur_content == $content['id']) {
                    $result_item['is_active'] = true;
                } else {
                    $result_item['is_active'] = false;
                }
                $result_item['parent_content_id'] = $content['parent'];
                $result_item['content_type'] = $content['content_type'];
                $result_item['subtype'] = $content['subtype'];
                $result[] = $result_item;
            }
        }


        return $result;
    }


}
