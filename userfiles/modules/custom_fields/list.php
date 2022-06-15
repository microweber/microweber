<script>
    if (typeof __smart_field_opener !== 'function') {
        __smart_field_opener = function (e) {
            if (e === undefined) {
                return;
            }
            if (mw.tools.hasClass(e.target.className, 'mw-ui-field') || mw.tools.hasClass(e.target.className, 'mw-custom-fields-from-page-title-text')) {
                mw.tools.toggle('.custom_fields_selector', '#smart_field_opener');

            }
        }
    }
</script>

<script>mw.lib.require('mwui_init');</script>
<?php
$for = 'module';
if (isset($params['for'])) {
    $for = $params['for'];
}
$list_preview = false;
$live_edit = false;
if (isset($params['live_edit'])) {
    $live_edit = $params['live_edit'];
}

if (isset($params['rel_id'])) {
    $params['for_module_id'] = $params['rel_id'];
} elseif (!isset($params['for_module_id'])) {
    if (isset($params['id'])) {
        $params['for_module_id'] = $params['id'];
    }

    if (isset($params['data-id'])) {

        $params['for_module_id'] = $params['data-id'];
    }
}

if (isset($params['for_id'])) {

    $params['for_module_id'] = $params['for_id'];
}

if (isset($params['list-preview']) and $params['list-preview'] != 'false') {
    $list_preview = true;
}

$diff = false;

if (isset($params['save_to_content_id'])) {
    $diff = get_custom_fields($for, $params['save_to_content_id'], 1, false, false, false, true);
}

$suggest_from_rel = false;

if (isset($params['suggest-from-related']) and $params['suggest-from-related'] != 'false') {
    $suggest_from_rel = true;
}
?>

<?php
$data = array();
if (isset($params['for_module_id'])): ?>
    <?php
    if (isset($params['default-fields'])) {
        mw()->fields_manager->makeDefault($for, $params['for_module_id'], $params['default-fields']);
    }

    if(is_numeric($params['for_module_id']) and intval($params['for_module_id']) == 0){
        $more = get_custom_fields($for, $params['for_module_id'], 1, false, false, $field_type = false, $for_session = app()->user_manager->session_id());

    } else {
        $more = get_custom_fields($for, $params['for_module_id'], 1, false, false, $field_type = false);

    }


    if ($suggest_from_rel == true) {
        $par = array();
        $par['rel_type'] = $for;
        $more = get_custom_fields($for, 'all', 1, false, false);
        $have = array();
        if (isset($diff) and is_array($diff)) {
            $i = 0;
            foreach ($diff as $item) {
                if (isset($item['name']) and in_array($item['name'], $have)) {
                    unset($diff[$i]);
                } else if (isset($diff[$i]) and isset($item['name'])) {
                    $have[] = $item['name'];
                }
                $i++;
            }
        }

        if (is_array($more)) {
            $i = 0;
            foreach ($more as $item) {
                if (isset($item['name']) and in_array($item['name'], $have)) {
                    unset($more[$i]);
                } else if (isset($more[$i]) and isset($item['name'])) {
                    $have[] = $item['name'];
                }
                $i++;
            }
        }
    }
    $custom_names_for_content = array();
    if (is_array($diff) and is_array($more)) {
        $i = 0;
        foreach ($more as $item2) {
            foreach ($diff as $item1) {
                if (isset($more[$i]) and isset($item1['copy_of_field'])) {
                    if ($item1['copy_of_field'] == $item2['id']) {
                        unset($more[$i]);
                    }
                }
                if (isset($more[$i]) and isset($item1['name'])) {
                    if ($item1['name'] == $item2['name']) {
                        unset($more[$i]);
                    }
                }
            }
            $i++;
        }
    }

    if (!empty($data)) {
        //$more = $data;
    }

    ?>

    <style>
        .mobile-th {
            display: none;
        }

        #custom-fields-post-table [class*='mw-custom-field-icon-'] {
            font-size: 25px;
            display: block;
        }

        #custom-fields-post-table td {
            vertical-align: middle;
        }
    </style>

<script>

    $(document).ready(function (){
        mw.trigger('customFieldsRefresh', {data:  <?php print json_encode($more);  ?>})
    })

</script>

    <?php if (!empty($more)): ?>
        <?php if ($list_preview == false): ?>
            <div class="mw-ui-field mw-tag-selector mw-custom-fields-tags" onclick="__smart_field_opener(event)">
                <?php if (isset($params['save_to_content_id']) and isset($params["rel_id"]) and intval(($params["rel_id"]) > 0)): ?>
                    <?php $p = get_content_by_id($params["rel_id"]); ?>
                    <?php if (isset($p['title'])): ?>
                        <div class="mw-custom-fields-from-page-title"><span class="mw-custom-fields-from-page-title-text"><?php _e("From"); ?> <strong><?php print $p['title'] ?></strong></span></div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php foreach ($more as $field): ?>
                    <?php if (isset($params['save_to_content_id'])): ?>
                        <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['type']; ?>" href="javascript:;" onmouseup="mw.custom_fields.copy_field_by_id('<?php print $field['id'] ?>', 'content', '<?php print intval($params['save_to_content_id']); ?>');"><span class="ico ico-<?php print $field['type']; ?>"></span><?php print ($field['title']); ?></a>
                    <?php else: ?>
                        <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['type']; ?>" href="javascript:;" data-id="<?php print $field['id'] ?>" id="custom-field-<?php print $field['id'] ?>" onmouseup="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<?php print $field['id'] ?>', false, event);">
                            <span class="ico ico-<?php print $field['type'] ?>"></span> <span onclick="mw.admin.custom_fields.del(<?php print $field['id'] ?>, this.parentNode);" class="mw-icon-close"></span> <?php print ($field['title']); ?> </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover table-hover-silver" id="custom-fields-post-table">
                    <thead>
                    <tr>
                        <th>
                            <small><?php _e("Type"); ?></small>
                        </th>
                        <th>
                            <small><?php _e("Name"); ?></small>
                        </th>
                        <?php
                        /*  <th>
                           <small><?php _e("Placeholder"); ?></small>
                       </th>*/

                        ?>
                       <th>
                           <small><?php _e("Value"); ?></small>
                       </th>
                       <th class="text-center">
                           <small><?php _e("Settings"); ?></small>
                       </th>
                       <th class="text-center">
                           <small><?php _e("Delete"); ?></small>
                       </th>
                   </tr>
                   </thead>
                   <tbody>
                   <?php
                   foreach ($more as $field): ?>
                       <tr id="mw-custom-list-element-<?php print $field['id']; ?>" data-id="<?php print $field['id']; ?>" class="show-on-hover-root">
                           <td data-tip="<?php print  ucfirst($field['type']); ?>" class="tip custom-field-icon" data-tipposition="top-left">
                               <span class="mobile-th"><?php _e("Type"); ?>:</span>
                               <div><span class="mw-custom-field-icon-<?php print $field['type']; ?>"></span></div>
                           </td>

                           <td data-id="<?php print $field['id']; ?>">
                               <span class="mobile-th"><?php _e("Name"); ?>: </span>
                               <span class="mw-custom-fields-list-preview">
                                    <span class="text-capitalize d-inline-block px-3 py-1" data-id="<?php print $field['id']; ?>"><small class="px-1 py-1"><?php print $field['name']; ?></small></span>
                                </span>
                           </td>

                        <?php
                        /*   <td data-id="<?php print $field['id']; ?>">
                               <span class="mobile-th"><?php _e("Placeholder"); ?>: </span>
                               <span class="mw-admin-custom-field-placeholder-edit-inline" data-id="<?php print $field['id']; ?>"><?php print $field['placeholder']; ?></span>
                           </td>*/

                         ?>

                            <td data-id="<?php print $field['id']; ?>" >
                                <span class="mobile-th"><?php _e("Settings"); ?></span>
                                <div id="mw-custom-fields-list-preview-<?php print $field['id']; ?>" class="mw-custom-fields-list-preview">
                                    <module type="custom_fields/values_preview" field-id="<?php print $field['id']; ?>" id="mw-admin-custom-field-edit-item-preview-<?php print $field['id']; ?>"/>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="javascript:mw.admin.custom_fields.edit_custom_field_item('#mw-custom-fields-list-settings-<?php print $field['id']; ?>',<?php print $field['id']; ?>);" class="btn btn-outline-primary btn-sm">
                                    <?php _e('Settings'); ?>
                                </a>
                            </td>

                            <td class="text-center">
                                <a class="text-danger" href="javascript:;" onclick="mw.admin.custom_fields.del(<?php print $field['id']; ?>,'#mw-custom-list-element-<?php print $field['id']; ?>');" data-bs-toggle="tooltip" title="<?php _e('Delete'); ?>"><i class="mdi mdi-close mdi-20px"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <script>mw.require('admin_custom_fields.js');</script>
            <script>


                $(document).ready(function () {

                    if (typeof( mw.admin.custom_fields) != 'undefined') {
                        mw.admin.custom_fields.initValues();
                    }

                    mw.responsive.table('#custom-fields-post-table', {
                        minWidth: 270
                    });

                    mw.$("#custom-fields-post-table tbody").sortable({
                        handle: "td.custom-field-icon",
                        axis: 'y',
                        placeholder: "ui-state-highlight",
                        start: function (e, ui) {
                            ui.placeholder.height(ui.item.height());
                        },
                        update: function () {
                            var _data = $(this).sortable('serialize');
                            var xhr = $.post(mw.settings.api_url + 'fields/reorder', _data);
                            xhr.success(function () {
                                <?php if(isset($params['for_module_id'])){ ?>
                                mw.reload_module_parent('#<?php print $params['for_module_id']; ?>');
                                <?php } else { ?>
                                mw.reload_module_parent('#<?php print $params['id']; ?>');
                                <?php } ?>
                            });

                            mw.custom_fields.after_save();
                        }
                    })
                });
            </script>
        <?php endif; ?>
    <?php else : ?>
        <?php if (!isset($params['save_to_content_id']) and $suggest_from_rel == false and $list_preview == false): ?>
            <div class="mw-ui-field mw-tag-selector mw-custom-fields-tags" onclick="__smart_field_opener(event)">
                <div class="mw-custom-fields-from-page-title">
                    <span class="mw-custom-fields-from-page-title-text"><?php _e("You dont have any custom fields"); ?>.</span>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
