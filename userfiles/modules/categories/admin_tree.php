<?php
only_admin_access();
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


if (!isset($params['link'])) {
    if (isset($params['append_to_link'])) {
        $append_to_link = $params['append_to_link'];
    } else {
        $append_to_link = '';
    }

    $params['link'] = '<a data-page-id="{id}" class="{active_class} {active_parent_class} pages_tree_link {nest_level} {exteded_classes}" href="{link}' . $append_to_link . '">{title}</a>';

} else {

    $params['link'] = '<a data-page-id="{id}" class="{active_class} {active_parent_class} pages_tree_link {nest_level} {exteded_classes}"  href="' . $params['link'] . '">{title}</a>';
}


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

?>
<?php

$params['return_data'] = true;

?>
<?php $pages_tree = category_tree($params); ?>
<?php if ($pages_tree != ''): ?>

<div class="pages-nav">
  <div class="well" style="padding: 0;"> <?php print $pages_tree ?> </div>
</div>
<?php endif; ?>
 
