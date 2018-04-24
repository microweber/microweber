<script type="text/javascript">
    mw.require('<?php print modules_url() ?>comments/edit_comments.js');
</script>


<div class="comments-holder">
    <?php if (is_array($data) and !empty($data)): ?>

        <div class="mw-admin-comments-search-holder">
            <?php foreach ($data as $item){ ?>
            <?php if (isset($item['rel_type']) and $item['rel_type'] == 'content'): ?>
            <module type="comments/comments_list" id="mw_comments_for_post_<?php print $item['rel_id'] ?>"
                    content_id="<?php print $item['rel_id'] ?>" search-keyword="<?php print $kw ?>">
                <?php endif; ?>
                <?php if (isset($item['rel_type']) and $item['rel_type'] == 'modules'): ?>
                <module type="comments/comments_list" id="mw_comments_for_post_<?php print $item['rel_id'] ?>"
                        rel_id="<?php print $item['rel_id'] ?>" rel="<?php print $item['rel_type'] ?>">
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
