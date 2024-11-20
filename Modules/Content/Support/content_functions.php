<?php

if (!function_exists('is_page')) {
    function is_page()
    {
        if (page_id() == content_id()) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_post')) {
    function is_post()
    {
        if (post_id()) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_home')) {
    function is_home()
    {
        if (defined('IS_HOME')) {
            return IS_HOME;
        }
    }
}

if (!function_exists('is_category')) {
    function is_category()
    {
        if (category_id()) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_product')) {
    function is_product()
    {
        if (product_id()) {
            return true;
        }
        return false;
    }
}

if (!function_exists('page_id')) {
    function page_id()
    {
        return app()->content_manager->page_id();
    }
}

if (!function_exists('main_page_id')) {
    function main_page_id()
    {
        return app()->content_manager->main_page_id();
    }
}

if (!function_exists('post_id')) {
    function post_id()
    {
        return app()->content_manager->post_id();
    }
}

if (!function_exists('product_id')) {
    function product_id()
    {
        return app()->content_manager->product_id();
    }
}

if (!function_exists('content_id')) {
    function content_id()
    {
        return app()->content_manager->content_id();
    }
}

if (!function_exists('category_id')) {
    function category_id()
    {
        return app()->content_manager->category_id();
    }
}

if (!function_exists('get_content')) {
    function get_content($params = false)
    {
        return app()->content_manager->get($params);
    }
}

if (!function_exists('get_content_admin')) {
    function get_content_admin($params)
    {
        if (is_admin() == false) {
            return false;
        }

        if (isset($params['keyword'])) {
            $params['keywords_exact_match'] = true;
        }

        if (!is_shop_module_enabled_for_user()) {
            $params['is_shop'] = '[neq]1';
            $params['content_type'] = '[neq]product';
        }

        return get_content($params);
    }
}

if (!function_exists('get_posts')) {
    function get_posts($params = false)
    {
        return app()->content_manager->get_posts($params);
    }
}

if (!function_exists('get_pages')) {
    function get_pages($params = false)
    {
        return app()->content_manager->get_pages($params);
    }
}

if (!function_exists('get_products')) {
    function get_products($params = false)
    {
        return app()->content_manager->get_products($params);
    }
}

if (!function_exists('get_content_by_id')) {
    function get_content_by_id($params = false)
    {
        return app()->content_manager->get_by_id($params);
    }
}

if (!function_exists('content_link')) {
    function content_link($id = false)
    {
        return app()->content_manager->link($id);
    }
}

if (!function_exists('content_edit_link')) {
    function content_edit_link($id = false)
    {
        return app()->content_manager->edit_link($id);
    }
}

if (!function_exists('content_create_link')) {
    function content_create_link($contentType = 'page')
    {
        return app()->content_manager->create_link($contentType);
    }
}

if (!function_exists('content_title')) {
    function content_title($id = false)
    {
        return app()->content_manager->title($id);
    }
}

if (!function_exists('content_description')) {
    function content_description($id = false)
    {
        return app()->content_manager->description($id);
    }
}

if (!function_exists('content_date')) {
    function content_date($id = false)
    {
        if ($id == false) {
            $id = CONTENT_ID;
        }

        $cont = app()->content_manager->get_by_id($id);
        if (isset($cont['created_at'])) {
            return $cont['created_at'];
        }
    }
}

if (!function_exists('delete_content')) {
    function delete_content($data)
    {
        return app()->content_manager->helpers->delete($data);
    }
}

if (!function_exists('paging')) {
    function paging($params)
    {
        return app()->content_manager->paging($params);
    }
}

if (!function_exists('content_parents')) {
    function content_parents($id = 0)
    {
        return app()->content_manager->get_parents($id);
    }
}

if (!function_exists('get_content_children')) {
    function get_content_children($id = 0)
    {
        return app()->content_manager->get_children($id);
    }
}

if (!function_exists('page_link')) {
    function page_link($id = false)
    {
        if ($id == false and defined('PAGE_ID')) {
            $id = PAGE_ID;
        }

        return app()->content_manager->link($id);
    }
}

if (!function_exists('page_title')) {
    function page_title($id = false)
    {
        if ($id == false and defined('PAGE_ID')) {
            $id = PAGE_ID;
        }

        return app()->content_manager->title($id);
    }
}

if (!function_exists('post_link')) {
    function post_link($id = false)
    {
        return app()->content_manager->link($id);
    }
}

if (!function_exists('pages_tree')) {
    function pages_tree($params = false)
    {
        return app()->content_manager->pages_tree($params);
    }
}

if (!function_exists('save_edit')) {
    function save_edit($post_data)
    {
        return app()->content_manager->save_edit($post_data);
    }
}

if (!function_exists('save_content')) {
    function save_content($data, $delete_the_cache = true)
    {
        return app()->content_manager->save_content($data, $delete_the_cache);
    }
}

if (!function_exists('save_content_admin')) {
    function save_content_admin($data, $delete_the_cache = true)
    {
        return app()->content_manager->save_content_admin($data, $delete_the_cache);
    }
}

if (!function_exists('save_content_field')) {
    function save_content_field($data, $delete_the_cache = true)
    {
        return app()->content_manager->save_content_field($data, $delete_the_cache);
    }
}

if (!function_exists('content_custom_fields')) {
    function content_custom_fields($content_id, $full = true, $field_type = false)
    {
        return app()->content_manager->custom_fields($content_id, $full, $field_type);
    }
}

if (!function_exists('get_content_field_draft')) {
    function get_content_field_draft($data)
    {
        return app()->content_manager->helpers->get_edit_field_draft($data);
    }
}

if (!function_exists('get_content_field')) {
    function get_content_field($data, $debug = false)
    {
        return app()->content_manager->edit_field($data, $debug);
    }
}

if (!function_exists('content_data')) {
    function content_data($content_id, $field_name = false)
    {
        return app()->content_manager->data($content_id, $field_name);
    }
}

if (!function_exists('content_attributes')) {
    function content_attributes($content_id)
    {
        return app()->content_manager->attributes($content_id);
    }
}

if (!function_exists('next_content')) {
    function next_content($content_id = false)
    {
        return app()->content_manager->next_content($content_id);
    }
}

if (!function_exists('next_post')) {
    function next_post($content_id = false)
    {
        return app()->content_manager->next_content($content_id, $mode = 'next', $content_type = 'post');
    }
}

if (!function_exists('prev_post')) {
    function prev_post($content_id = false)
    {
        return app()->content_manager->next_content($content_id, $mode = 'prev', $content_type = 'post');
    }
}

if (!function_exists('prev_content')) {
    function prev_content($content_id = false)
    {
        return app()->content_manager->prev_content($content_id);
    }
}

if (!function_exists('breadcrumb')) {
    function breadcrumb($params = false)
    {
        return app()->content_manager->breadcrumb($params);
    }
}

if (!function_exists('helper_body_classes')) {
    function helper_body_classes()
    {
        $template = template_name();

        $classes = array();

        if (page_id()) {
            $classes[] = 'page-id-' . page_id();
        }
        if (post_id()) {
            $classes[] = 'post-id-' . post_id();
        }
        if (content_id()) {
            $classes[] = 'content-id-' . content_id();
        }
        if (category_id()) {
            $classes[] = 'category-id-' . category_id();
        }
        $seg = url_segment(0);
        if ($seg) {
            if (category_id()) {
                $slugs = app()->permalink_manager->link(category_id(), 'category', $returnSlug = 1);
                if (isset($slugs['slug']) and $slugs['slug']) {
                    $classes[] = 'category-' . $slugs['slug'];
                }
            }

            $slugs = app()->permalink_manager->link(content_id(), 'content', $returnSlug = 1);
            if (isset($slugs['slug']) and $slugs['slug']) {
                $classes[] = 'page-' . $slugs['slug'];
            }
        }

        if ($template) {
            $classes[] = $template;
        }

        return implode(' ', $classes);
    }
}
