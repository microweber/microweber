<?php
must_have_access();
/**
 * Print the site pages as tree
 *
 * @param string append_to_link
 *            You can pass any string to be appended to all pages urls
 * @param string link
 *            Replace the link href with your own. Ex: link="<?php print site_url('page_id:{id}'); ?>"
 * @return string prints the site tree
 * @uses pages_tree($params);
 * @usage  type="pages" append_to_link="/editmode:y"
 */
$append_to_link = '';

//$params['content_link_class'] = "mw-tree-renderer-admin-content-link-item";
$params['link_class'] = "mw-tree-renderer-admin-link-item pages_tree_link ";
$params['categories_extra_attributes'] = array(
    'data-page-id' => '{category_page}',
    'content_link_class' => 'mw-tree-renderer-admin-link-item pages_tree_link',
    'data-count' => '{count}',
    'data-categories-type' => '{category_type}',
    'value' => '{id}'
);

$params['link'] = '<a data-page-id="{id}" class="mw-tree-renderer-admin-link-item {content_link_class} {active_class} {active_parent_class} pages_tree_link {nest_level} content-item-{id} {exteded_classes}" href="{link}' . $append_to_link . '">{title}</a>';
$params['categories_link'] = '<a data-category-id="{id}" class="mw-tree-renderer-admin-link-item {content_link_class} {active_class} {active_parent_class} pages_tree_link {nest_level} category-item-{id}" href="{link}' . $append_to_link . '">{title}</a>';


if (isset($params['data-parent'])) {
    $params['parent'] = intval($params['data-parent']);
} else {
    $o = get_option('data-parent', $params['id']);
    if ($o != false and intval($o) > 0) {
        $params['parent'] = $o;
    } else {
        if (isset($params['content_id'])) {
            $params['parent'] = intval($params['content_id']);
        }
    }
}

if (!isset($params['parent'])) {
    //	$params['parent'] = 0;
}

$include_categories = false;
if (isset($params['data-include_categories']) and isset($params['parent'])) {
    $params['include_categories'] = intval($params['parent']);
} else {
    $o = get_option('include_categories', $params['id']);
    if ($o != false and ($o) == 'y') {
        $include_categories = $params['include_categories'] = true;
    }
}

$o = get_option('maxdepth', $params['id']);

if ($o != false and intval($o) > 0) {
    $params['maxdepth'] = $o;
}

if (is_admin() == false) {
    $params['is_active'] = 1;
}

$params['categories_link_class'] = 'mw-tree-renderer-admin-link-item pages_tree_link';
$params['categories_ul_class'] = 'category_tree';
$params['categories_li_class'] = 'category_element';


$params['categories_ul_class_deep'] = 'category_tree';
$params['categories_li_class_deep'] = 'category_element';
//categories_li_class  categories_ul_class_deep    categories_li_class_deep     categories_ul_class_deep

$params['return_data'] = true;
//$params['no_cache'] = true;

?>
<?php $pages_tree = pages_tree($params); ?>

<?php if ($pages_tree != ''): ?>
    <div class="pages-nav">
        <div class="well" style="padding: 0;">
            <?php print $pages_tree ?>
        </div>
    </div>
<?php endif; ?>
<?php $is_del = get_content('count=1&is_deleted=1'); ?>

<ul class="pages_tree pages_trash_holder depth-1">
    <li class="pages_trash pages_tree_item depth-1" title="<?php _e("Trash"); ?>">
        <a data-page-id="deleted" class="pages_trash_link pages_tree_link depth-1" onclick="mw.url.windowHashParam('action', 'trash');" href="javascript: return false;">
            <span class="pages_tree_link_text pages_trash_text"><?php _e("Trash"); ?></span>
        </a>
    </li>
</ul>
