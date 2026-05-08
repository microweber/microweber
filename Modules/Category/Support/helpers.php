<?php




if (!function_exists('get_category_by_id')) {

    /**
     * @desc        Get a single row from the categories_table by given ID and returns it as one dimensional array
     *
     * @param int
     *
     * @return array
     *
     * @author      Peter Ivanov
     *
     * @version     1.0
     *
     * @since       Version 1.0
     */
    function get_category_by_id($id = 0)
    {
        return app()->category_manager->get_by_id($id);
    }
}

if (!function_exists('get_category_by_url')) {
    function get_category_by_url($url)
    {
        return app()->category_repository->getByColumnNameAndColumnValue('url', $url);
    }
}

if (!function_exists('get_categories')) {
    function get_categories($data)
    {
        return app()->category_manager->get($data);
    }
}

if (!function_exists('save_category')) {
    function save_category($data)
    {
        return app()->category_repository->save($data);
    }
}

if (!function_exists('delete_category')) {
    function delete_category($data)
    {
        return app()->category_manager->delete($data);
    }
}

if (!function_exists('reorder_categories')) {
    function reorder_categories($data)
    {
        return app()->category_manager->reorder($data);
    }
}

if (!function_exists('content_categories')) {
    function content_categories($content_id = false, $data_type = 'categories')
    {
        return get_categories_for_content($content_id, $data_type);
    }
}

if (!function_exists('content_tags')) {
    function content_tags($content_id = false, $return_full = false)
    {
        return app()->content_manager->tags($content_id, $return_full);
    }
}

if (!function_exists('media_tags')) {
    function media_tags($media_id = false, $return_full = false)
    {
        return app()->media_manager->tags($media_id, $return_full);
    }
}

if (!function_exists('picture_tags')) {
    function picture_tags($media_id = false)
    {
        return media_tags($media_id);
    }
}

if (!function_exists('get_categories_for_content')) {
    function get_categories_for_content($content_id = false, $data_type = 'categories')
    {
        if (intval($content_id) == 0) {
            $content_id = content_id();
            if ($content_id == 0) {
                return false;
            }
        }

        return app()->category_manager->get_for_content($content_id, $data_type);
    }
}

if (!function_exists('category_link')) {
    function category_link($id)
    {
        if (intval($id) == 0) {
            return false;
        }

        return app()->category_manager->link($id);
    }
}

if (!function_exists('category_title')) {
    function category_title($id = false)
    {
        if (!$id) {
            $id = category_id();
        }
        if (intval($id) == 0) {
            return false;
        }
        $cat = get_category_by_id($id);
        if (isset($cat['title'])) {
            return $cat['title'];
        }
    }
}

if (!function_exists('get_category_children')) {
    function get_category_children($parent_id = 0, $type = false, $visible_on_frontend = false)
    {
        return app()->category_manager->get_children($parent_id, $type, $visible_on_frontend);
    }
}

if (!function_exists('get_page_for_category')) {
    function get_page_for_category($category_id)
    {
        return app()->category_manager->get_page($category_id);
    }
}

if (!function_exists('get_category_id_from_url')) {
    function get_category_id_from_url($url = false)
    {
        return app()->category_manager->get_category_id_from_url($url);
    }
}

if (!function_exists('category_tree')) {
    /**
     * AI-82 / TICKET-UU (cycle-93 2026-05-09): default `return_data=1`
     * so the helper consistently RETURNS the rendered tree string
     * instead of printing it directly. Pre-fix the underlying renderer
     * `print`s by default (KnpCategoryTreeRenderer:278) and only
     * returns when `return_data` is set, which forced the Category
     * skin templates to use raw `<?php category_tree($params); ?>`
     * (discarding the return + relying on the print side-effect).
     * Defaulting to return-mode lets the templates use the canonical
     * Blade `{!! category_tree($params) !!}` shape, which:
     *   - composes cleanly with Blade output filters
     *   - is consistent with how other Microweber helpers
     *     (`menu_tree()`, `module(...)`) are invoked from Blade
     *   - keeps the side-effect-free contract any future renderer
     *     refactor would expect.
     * Callers that explicitly pass `return_data=0` keep the legacy
     * print-side-effect — only the unset case flips.
     */
    function category_tree($params = false)
    {
        if (!is_array($params)) {
            $params = parse_params($params);
        }
        if (!isset($params['return_data'])) {
            $params['return_data'] = 1;
        }
        return app()->category_manager->tree($params);
    }
}

if (!function_exists('get_category_items')) {
    function get_category_items($category_id, $rel_type = false, $relId = false)
    {
        return app()->category_repository->getItems($category_id, $rel_type, $relId);
    }
}

if (!function_exists('get_category_items_count')) {
    function get_category_items_count($category_id)
    {
        return app()->category_repository->getItemsCount($category_id);
    }
}

if (!function_exists('get_category_edit_link')) {
    function get_category_edit_link($category_id)
    {
        if ($category_id == 0) {
            $admin_edit_url = admin_url('categories/create/');
        } else {
            $admin_edit_url = admin_url('categories/' . $category_id . '/edit');
        }

        return $admin_edit_url;
    }
}
