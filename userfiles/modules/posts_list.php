<?
// d($params);

$post_params = $params;

if (isset($post_params['id'])) {
    $paging_param = 'curent_page' . crc32($post_params['id']);
    unset($post_params['id']);
} else {
    $paging_param = 'curent_page';
}



if (isset($post_params['data-page-number'])) {

    $post_params['curent_page'] = $post_params['data-page-number'];
    unset($post_params['data-page-number']);
}





if (isset($params['data-paging-param'])) {

    $paging_param = $params['data-paging-param'];
//	d($paging_param);
}





$show_fields = false;
if (isset($post_params['data-show'])) {
    //  $show_fields = explode(',', $post_params['data-show']);

    $show_fields = $post_params['data-show'];
} else {
    $show_fields = option_get('data-show', $params['id']);
}

if ($show_fields != false and is_string($show_fields)) {
    $show_fields = explode(',', $show_fields);
}





if (!isset($post_params['data-limit'])) {
    $post_params['limit'] = option_get('data-limit', $params['id']);
}

if (isset($post_params['data-page-id'])) {
    $post_params['parent'] = intval($post_params['data-page-id']);
} else {
    $cfg_page_id = option_get('data-page-id', $params['id']);
    if ($cfg_page_id != false and intval($cfg_page_id) > 0) {
        $post_params['parent'] = $cfg_page_id;
    }
}

$tn_size = array('150');

if (isset($post_params['data-thumbnail-size'])) {
    $temp = explode('x', strtolower($post_params['data-thumbnail-size']));
    if (!empty($temp)) {
        $tn_size = $temp;
    }
} else {
    $cfg_page_item = option_get('data-thumbnail-size', $params['id']);
    if ($cfg_page_item != false) {
        $temp = explode('x', strtolower($cfg_page_item));

        if (!empty($temp)) {
            $tn_size = $temp;
        }
    }
}






//$post_params['debug'] = 'posts';
$post_params['content_type'] = 'post';
$content   =$data = get_content($post_params);
?>
<?
$post_params_paging = $post_params;
//$post_params_paging['count'] = true;




$post_params_paging['page_count'] = true;
$pages = get_content($post_params_paging);


$pages_count = intval($pages);
?>
<? if (intval($pages) > 1): ?>
    <? $paging_links = paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
    <? if (!empty($paging_links)): ?>

        <div class="paging">
            <? foreach ($paging_links as $k => $v): ?>
                <span class="paging-item" data-page-number="<? print $k; ?>" ><a  data-page-number="<? print $k; ?>" data-paging-param="<? print $paging_param; ?>" href="<? print $v; ?>"  class="paging-link"><? print $k; ?></a></span>
            <? endforeach; ?>
        </div>
    <? endif; ?>
<? endif; ?>
<hr>
<div class="content-list">
    <? if (!empty($content)): ?>
        <? if ($show_fields == false): ?>
            <? $show_fields = array('thumbnail', 'title', 'description', 'read_more'); ?>
        <? endif; ?>
        <? foreach ($content as $item): ?>
            <div class="content-item" data-content-id="<? print ($item['id']) ?>">

                <? if (is_array($content) and !empty($content)): ?>
                    <? foreach ($show_fields as $show_field): ?>
                        <?
                        $show_field = trim($show_field);

 $fv = false;
                        switch ($show_field) {

                            case 'read_more':
                                $u = post_link($item['id']);
                                $fv = "<a href='{$u}' class='read_more'>Read more</a>";
                                break;

                            case 'thumbnail':
                                if (isset($item[$show_field])) {
                                    $u = post_link($item['id']);

                                    if (isset($tn_size[0])) {
                                        $wstr = " width='{$tn_size[0]}' ";
                                    } else {
                                        $wstr = '';
                                    }


                                    if (isset($tn_size[1])) {
                                        $hstr = " height='{$tn_size[1]}' ";
                                    } else {
                                        $hstr = '';
                                    }

                                    //  d($hstr);

                                    $iu = $item[$show_field];
									if(trim($iu != '')){
                                    $fv = $fv_i = "<img src='{$iu}' {$wstr} {$hstr} />";
									}
                                    // $fv = "<a href='{$u}' class='thumbnail'>{$fv_i}</a>";
                                }

                                break;

                            default:
                                $fv = false;
                                if (isset($item[$show_field])) {
                                    $fv = $item[$show_field];
                                } else {

                                }





                                break;
                        }
                        ?>
                        <? if ($fv != false and trim($fv) != ''): ?>
                            <div class="post-field-<? print $show_field ?>"><? print $fv ?></div>
                        <? endif; ?>
                    <? endforeach; ?>
                    <? // d($show_fields); ?>
                <? endif; ?>

            </div>
        <? endforeach; ?>
    <? else: ?>
        <div class="content-list-empty"> No posts </div>
    <? endif; ?>
</div>
