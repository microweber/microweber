<?php


 
$type = 'page';
$act = url_param('action', 1);
?>
<?php

if(isset($params['page-id'])){
	$last_page_front = $params['page-id'];
} else {

$last_page_front = session_get('last_content_id');
if ($last_page_front == false) {
    if (isset($_COOKIE['last_page'])) {
        $last_page_front = $_COOKIE['last_page'];
    }
}
}
if ($last_page_front != false) {
    $cont_by_url = mw('content')->get_by_id($last_page_front, true);
    if (isset($cont_by_url) and $cont_by_url == false) {
        $past_page = mw('content')->get("order_by=updated_on desc&limit=1");
        $past_page = mw('content')->link($past_page[0]['id']);
    } else {
        $past_page = mw('content')->link($last_page_front);
    }
} else {
    $past_page = mw('content')->get("order_by=updated_on desc&limit=1");
    $past_page = mw('content')->link($past_page[0]['id']);
}
?>

<?php if(isset($past_page) and $past_page != false): ?>
 <script>
        $(function () {

            
            $('.go-live-edit-href-set').attr('href','<?php print $past_page; ?>');


        });
    </script>
<?php endif; ?>


<?php if (isset($params['keyword']) and $params['keyword'] != false): ?>
    <script>
        $(function () {

            $('[autofocus]').focus(function () {
                this.selectionStart = this.selectionEnd = this.value.length;
            });

            $('[autofocus]:not(:focus)').eq(0).focus();


        });
    </script>
<?php endif; ?>

<div class="admin-manage-toolbar-holder">
    <div class="admin-manage-toolbar">
        <div class="admin-manage-toolbar-content">
            <?php if(!isset($edit_page_info)): ?>
            <div class="mw-ui-row" style="width: 100%;">
                <div class="mw-ui-col">
                    <div class="mw-ui-row" style="width: 100%;padding-top: 19px;">
                        <div class="mw-ui-col">
                            <?php if (!isset($params['category-id']) and isset($page_info) and is_array($page_info)): ?>
                                <?php if ($page_info['is_shop'] == 'y') {
                                    $type = 'shop';
                                } elseif ($page_info['subtype'] == 'dynamic') {
                                    $type = 'dynamicpage';
                                } else {
                                    $type = 'page';
                                }

                                ?>
                                <h2><span
                                        class="mw-icon-<?php print $type; ?>"></span><?php print ($page_info['title']) ?>
                                </h2>
                            <?php elseif (isset($params['category-id'])): ?>
                                <?php $cat = get_category_by_id($params['category-id']); ?>
                                <?php if (isset($cat['title'])): ?>
                                    <h2><span class="mw-icon-category"></span> <?php print $cat['title'] ?> </h2>
                                <?php endif; ?>
                            <?php elseif ($act == 'pages'): ?>
                                <h2><span class="mw-icon-website"></span>
                                    <?php _e("Pages"); ?>
                                </h2>
                            <?php elseif ($act == 'posts'): ?>
                                <h2><span class="mw-icon-website"></span>
                                    <?php _e("Posts"); ?>
                                </h2>
                            <?php elseif ($act == 'products'): ?>
                                <h2><span class="mw-icon-website"></span>
                                    <?php _e("Products"); ?>
                                </h2>

                            <?php else: ?>
                                <h2><span class="mw-icon-website"></span>
                                    <?php _e("Website"); ?>
                                </h2>
                            <?php endif; ?>
                        </div>
                        <div class="mw-ui-col">
                            <div class="manage-toobar ">
                                <div class="manage-toobar-content">
                                    <div class="mw-ui-btn-nav pull-right">
                                        <?php if (isset($params['page-id']) and intval($params['page-id']) != 0): ?>
                                            <?php $edit_link = admin_url('view:content#action=editpost:' . $params['page-id']); ?>
                                        <?php endif; ?>
                                        <?php if (isset($params['category-id'])): ?>
                                            <?php $edit_link = admin_url('view:content#action=editcategory:' . $params['category-id']); ?>
                                        <?php endif; ?>
                                        <?php if (isset($params['page-id']) and intval($params['page-id']) != 0): ?>
                                            <?php $edit_link = admin_url('view:content#action=editpost:' . $params['page-id']); ?>
                                            <a href="<?php print $edit_link; ?>" class="mw-ui-btn edit-content-btn"
                                               id="edit-content-btn" data-tip="bottom-left"><span
                                                    class="mw-icon-pen"></span>
                                                <?php _e("Edit page"); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (isset($params['category-id'])): ?>
                                            <?php $edit_link = admin_url('view:content#action=editcategory:' . $params['category-id']); ?>
                                            <a href="<?php print $edit_link; ?>" class="mw-ui-btn edit-category-btn"
                                               id="edit-category-btn" data-tip="bottom-left"> <span
                                                    class="mw-icon-pen"></span>
                                                <?php _e("Edit category"); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="pull-right">
                                        <input
                                            onkeyup="mw.on.stopWriting(this,function(){mw.url.windowHashParam('search',this.value)})"
                                            value="<?php if (isset($params['keyword']) and $params['keyword'] != false): ?><?php print $params['keyword'] ?><?php endif; ?>"
                                            <?php  if (isset($params['keyword']) and $params['keyword'] != false): ?>
                                            autofocus="autofocus"
                                        <?php endif; ?>
                                            placeholder="<?php _e("Search for posts"); ?>"
                                            type="text"
                                            style="margin-right: 10px;max-width: 145px; <?php if (isset($params['keyword']) and $params['keyword'] != false): ?> min-width: 145px; <?php endif; ?>"
                                            class="mw-ui-searchfield pull-right"
                                            id="mw-search-field"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mw-ui-col col-bar-live-edit"><a href="<?php print $past_page; ?>?editmode=y"
                                                                    class="mw-ui-btn default-invert tip"
                                                                    data-tip="<?php _e("Go Live Edit"); ?>"
                                                                    data-tipposition="bottom-center"><span
                                    class="mw-icon-live"></span></a></div>
                    </div>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!isset($edit_page_info)): ?>
                <div class="manage-toobar manage-toolbar-top">
                    <div class="manage-toobar-content">
                        <div class="mw-ui-link-nav"> <span class="mw-ui-link"
                                                           onclick="mw.check.all('#pages_edit_container')">
            <?php _e("Select All"); ?>
            </span> <span class="mw-ui-link" onclick="mw.check.none('#pages_edit_container')">
            <?php _e("Unselect All"); ?>
            </span> <span class="mw-ui-link" onclick="delete_selected_posts();">
            <?php _e("Delete"); ?>
            </span></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
 
