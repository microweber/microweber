<?php
if (!is_admin()){
    return;
}
$data = false;
if (isset($params['content_id'])){
    $data = db_get('limit=100&order_by=id desc&table=content_revisions_history&rel_type=content&field=content&rel_id=' . $params['content_id']);
    $content = get_content_by_id($params['content_id']);
    if (!isset($content['content'])){
        return;
    }
}
?>



    <script src="<?php print $config['url_to_module'] ?>scripts.js"></script>


    <link rel="stylesheet" type="text/css"
          href="<?php print $config['url_to_module'] ?>styles.css"/>
<?php if ($data){ ?>
    <?php foreach ($data as $item) { ?>
        <?php $rev = mw_text_render_diff_from_string($item['value'], $content['content'], 'inline'); ?>
        <?php if ($rev){ ?>
            <div class="mw-ui-box active"
                 id="accordion-example<?php print $item['id'] ?>">
                <div

                    class="mw-ui-box-header">
                    <span onclick="mw.accordion('#accordion-example<?php print $item['id'] ?>');">
                    <?php print $item['created_at'] ?>
                    (<?php print mw()->format->ago($item['created_at']); ?>)
                    </span>
                    <a   class="pull-right mw-ui-btn mw-ui-btn-small"   href="javascript:mw.content_revisions_control.load_content_field_to_editor('<?php print $item['id'] ?>')">Load to editor</a>
                </div>
                <div class="mw-accordion-content mw-ui-box-content"
                     style="display: block;">
                    <div class="mwphpdiff">
                        <?php print $rev ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>