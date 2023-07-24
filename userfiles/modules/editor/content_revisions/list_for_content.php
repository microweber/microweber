<?php
if (!is_admin()) {
    return;
}
$data = false;

$db_get_params = [];

$db_get_params['table'] = 'content_revisions_history';
$db_get_params['no_cache'] = true;
$db_get_params['limit'] = 100;
$db_get_params['order_by'] = 'id desc';

if (isset($params['content_id'])) {

    if (isset($params['from_url_string'])) {
        $params['from_url_string'] = str_replace(site_url(), '', $params['from_url_string']);
        $db_get_params2 = $db_get_params;
        $db_get_params2['url'] = $params['from_url_string'];

        $data = db_get($db_get_params2);
        $db_get_params2['table'] = 'content_revisions_history';

        $data_content_fields_curr = db_get($db_get_params2);

    } else  if (isset($params['from_url_string_home'])) {
        $params['from_url_string_home'] = str_replace(site_url(), '', $params['from_url_string_home']);
        $db_get_params2 = $db_get_params;
        $db_get_params2['__query_from_url_string_home'] = function ($query_filter) {
            $query_filter->where('url',   '')->orWhereNull('url');
            return $query_filter;
        };
        $db_get_params3 = $db_get_params2;
        $data = db_get($db_get_params2);
        $db_get_params2['table'] = 'content_revisions_history';

        $data_content_fields_curr = db_get($db_get_params3);



    } else {
        $data = db_get('no_cache=true&limit=100&order_by=id desc&table=content_revisions_history&rel_type=content&rel_id=' . $params['content_id']);
        $data_content_fields_curr = db_get('no_cache=true&limit=100&order_by=id desc&table=content_fields&rel_type=content&rel_id=' . $params['content_id']);

    }




    //$data = db_get('limit=100&order_by=id desc&table=content_revisions_history&rel_type=content&field=content&rel_id=' . $params['content_id']);
    $content = get_content_by_id($params['content_id']);

    if (!isset($content['id'])) {
        return;
    }
    $curr_val = '';
    if ($data) {
        foreach ($data as $data_key => $data_item) {
            $data_item['value_original'] = '';

            if ($data_content_fields_curr) {
                foreach ($data_content_fields_curr as $data_item_from_history) {



                    if (isset($data_item['field']) and isset($data_item['rel_type']) and isset($data_item['rel_id'])) {

                        if(isset($data_item['field']) and $data_item['field'] =='content'){
                            if($content and isset($content['content'])){
                                $data_item['value_original'] = $content['content'];

                            }
                        } else  if(isset($data_item['field']) and $data_item['field'] =='content_body'){
                            if($content and isset($content['content_body'])){
                                $data_item['value_original'] = $content['content_body'];

                            }
                        } else if (isset($data_item_from_history['field']) and isset($data_item_from_history['rel_type']) and isset($data_item_from_history['rel_id'])) {

                            if ($data_item_from_history['field'] == $data_item['field']) {
                                if ($data_item_from_history['rel_type'] == $data_item['rel_type']) {
                                    if ($data_item_from_history['rel_id'] == $data_item['rel_id']) {
                                        $data_item['value_original'] = $data_item_from_history['value'];
                                    }
                                }
                            }

                        }
                    }
                }
            }


            $data[$data_key] = $data_item;
        }
    }

}


?>


    <script src="<?php print $config['url_to_module'] ?>scripts.js"></script>

    <script>

        scroll_content_field_to_editor = function (field, rel_type) {
            mw.content_revisions_control.scroll_content_field_to_editor(field, rel_type);
        }
    </script>


    <link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>styles.css"/>
<?php if ($data) { ?>
    <?php foreach ($data as $item) { ?>

            <div class="mw-ui-box"
                 id="accordion-example<?php print $item['id'] ?>">
                <div

                        class="mw-ui-box-header">
                    <span class="mw-ui-link" onclick="mw.accordion('#accordion-example<?php print $item['id'] ?>');">
                    <em><?php print $item['field'] ?></em> &nbsp; <br> <?php print $item['created_at'] ?>
                        (<?php print mw()->format->ago($item['created_at']); ?>)
                    </span>


                    <a class="pull-right mw-ui-btn mw-ui-btn-small"
                       href="javascript:mw.content_revisions_control.load_content_field_to_editor('<?php print $item['id'] ?>')"><?php _e("Load to editor"); ?></a>

                    <?php if (isset($params['show_btn_for_find_element'])) { ?>
                        <a class="pull-right mw-ui-btn mw-ui-btn-small mr-3"
                           href="javascript:scroll_content_field_to_editor('<?php print $item['field'] ?>','<?php print $item['rel_type'] ?>')"><span
                                    class="mdi mdi-note-text-outline"></span></a>


                    <?php } ?>


                    <a class="pull-right mw-ui-btn mw-ui-btn-small mr-3"
                       href="javascript:mw.accordion('#accordion-example<?php print $item['id'] ?>');"><span
                                class="mdi mdi-note-text-outline"></span></a>


                </div>
                <div class="mw-accordion-content mw-ui-box-content" style="display: none">
                    <table class="mw-ui-table" style="display: none">
                        <tr>

                            <td>
                                <small>field</small>

                            </td>
                            <td>
                                <small><?php print $item['field'] ?></small>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <small>rel_type</small>
                            </td>
                            <td>
                                <small><?php print $item['rel_type'] ?></small>

                            </td>

                        </tr>
                        <tr>
                            <td>
                                <small>rel_id</small>
                            </td>
                            <td>
                                <small><?php print $item['rel_id'] ?></small>

                            </td>

                        </tr>
                    </table>
        <?php $rev = mw_text_render_diff_from_string($item['value'], $item['value_original'], 'inline'); ?>
        <?php if ($rev) { ?>
                    <div class="mwphpdiff">
                        <?php print $rev ?>
                    </div>
        <?php } ?>
                </div>
            </div>

    <?php } ?>
<?php } else { ?>
    <div class="mw-ui-box">
        <div class="mw-ui-box-content">
            <h3 class="mw-ui-box-header">
                <?php _e("No revisions for this content"); ?>
            </h3>
        </div>
    </div>
<?php } ?>
