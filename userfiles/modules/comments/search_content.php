<?php


return print('This file is deprecated ' . __FILE__);


only_admin_access();
$comments_data = array();
//$comments_data['in_table'] = 'comments';
$comments_data['cache_group'] = 'comments/global';
if (isset($params['search-keyword'])) {
    $comments_data['keyword'] = $params['search-keyword'];
}
if (isset($params['content_id'])) {
    $comments_data['content_id'] = $params['content_id'];
}

if (isset($params['limit'])) {
    $comments_data['limit'] = intval($params['limit']);
}


$comments_data = array();
if (isset($params['content_id'])) {

     $comments_data['content_id'] = $params['content_id'];


} else {

    if (isset($params['rel_type'])) {
        $comments_data['rel_type'] = $params['rel_type'];
    }

    if (isset($params['rel_id'])) {
        $comments_data['rel_id'] = $params['rel_id'];
    }

}

$kw = false;
//$comments_data['in_table'] =  'comments';
//$comments_data['cache_group'] =  'comments/global';
if (isset($params['search-keyword']) and $params['search-keyword']) {
    $kw = $comments_data['keyword'] = $params['search-keyword'];
    $comments_data['search_in_fields'] = 'comment_name,comment_body,comment_email,comment_website,from_url,comment_subject';
}
if ($kw) {
    $kw = strip_tags($kw);
    $kw = addslashes($kw);
}
$comments_data['group_by'] = 'rel_id,rel_type';
$comments_data['order_by'] = 'created_at desc';

 //  $comments_data['debug'] =  'rel,rel_id';
$data = get_comments($comments_data);


//$data = get_content($comments_data);
?>


<script type="text/javascript">

    mw.require("<?php print $config['url_to_module']; ?>edit_comments.js");
</script>


<div class="comments-holder">
    <?php if (is_array($data)): ?>

        <div class="mw-admin-comments-search-holder">
            <?php foreach ($data as $item){ ?>
            <?php if (isset($item['rel_type']) and $item['rel_type'] == 'content'): ?>
            <module type="comments/comments_for_post" id="mw_comments_for_post_<?php print $item['rel_id'] ?>"  content_id="<?php print $item['rel_id'] ?>" search-keyword="<?php print $kw ?>">
                <?php endif; ?>
                <?php if (isset($item['rel_type']) and $item['rel_type'] == 'modules'): ?>
                <module type="comments/comments_for_module" id="mw_comments_for_post_<?php print $item['rel_id'] ?>"   rel_id="<?php print $item['rel_id'] ?>" rel="<?php print $item['rel_type'] ?>">
                    <?php endif; ?>
                    <?php // _d($item);  break;  ?>
                    <?php }; ?>
        </div>
    <?php else: ?>
        <h5><?php _e('There are not comments here.'); ?></h5>
        <br/>
        <a href="#content_id=0" class="mw-ui-btn">
            <?php _e("See all comments"); ?>
        </a>
    <?php endif; ?>
</div>
