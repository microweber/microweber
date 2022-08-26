<?php


$field_name = "content_id";
$selected = 0;


if (isset($params['field-name'])) {
    $field_name = $params['field-name'];
}

if (isset($params['selected-id'])) {
    $selected = intval($params['selected-id']);
}

$rand = uniqid();
$no_parent_title = _e("None", 1);
if (isset($params['no-parent-title'])) {
    $no_parent_title = $params['no-parent-title'];
}

$top_item = false;
if (isset($params['recommended-id']) and $params['recommended-id'] != false) {
    $recommended_parent = $params['recommended-id'];
    $top_item = get_content_by_id(intval($recommended_parent));
}

?>

<?php if (isset($params['change-field'])): ?>
    <script>
        $(document).ready(function () {
            mw.$('#content_selector_<?php print $rand ?>').on('change', function (e) {
                var val = $(this).val();
                mw.$('#<?php print $params['change-field'] ?>').val(val).trigger("change");
                mw.$('[name="<?php print $params['change-field'] ?>"]').val(val).trigger("change");
            });
        });
    </script>
<?php endif; ?>

<select name="<?php print $field_name ?>" data-width="100%" class="selectpicker selector-<?php print $config['module_class'] ?>" id="content_selector_<?php print $rand ?>" title="<?php _e("Select a parent page"); ?>">
    <?php if (isset($top_item) and is_array($top_item) and !empty($top_item)) : ?>
        <option value="<?php print $top_item['id'] ?>">-- <?php print $top_item['title'] ?></option>
    <?php endif; ?>

    <option value="0" <?php if ((0 == intval($selected))): ?>   selected="selected"  <?php endif; ?>><?php print $no_parent_title ?></option>

    <?php
    $pt_opts = array();
    $pt_opts['link'] = "{empty}{title}";
    $pt_opts['list_tag'] = " ";
    $pt_opts['list_item_tag'] = "option";
    $pt_opts['active_ids'] = $selected;
    if (isset($params['remove_ids'])) {
        $pt_opts['remove_ids'] = $params['remove_ids'];
    }
    $pt_opts['active_code_tag'] = '   selected="selected"  ';
    pages_tree($pt_opts);
    ?>
</select>

<?php $treeId = 'tree-item-selector-' . $rand; ?>

<div id="<?php print $treeId; ?>"></div>

<script>

    ;(function (){
        function tree() {
            var selected = [];
            mw.admin.tree('#<?php print $treeId; ?>', {
                options: {

                }
            }).then(function (res){
                res.tree.select(<?php print $selected; ?>, 'page')
            });
        }
        if (document.readyState !== 'loading') {
            tree();
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                tree();
            });
        }
    })();
</script>
