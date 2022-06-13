<?php
$entry = false;

if (isset($item['rel_id']) AND !isset($is_entry)) {
    $entry_params['id'] = $item['rel_id'];
    $entry = get_contact_entry_by_id($entry_params);
    $item_id = $item['rel_id'];
} elseif (isset($item['id']) AND isset($is_entry)) {
    $entry_params['id'] = $item['id'];
    $entry = get_contact_entry_by_id($entry_params);
    $item_id = $item['id'];
}


$form_files = [];
$form_values = [];

$find_form_values = \MicroweberPackages\Form\Models\FormDataValue::where('form_data_id', $item_id)->get();
if ($find_form_values != null) {
    // seperate uploads from this array
    $new_form_values = [];
    foreach ($find_form_values->toArray() as $form_value) {

        if (isset($form_value['field_type']) && $form_value['field_type'] == 'upload') {
            $form_files[$form_value['field_name']] = $form_value['field_value_json'];
            continue;
        }

        if (!empty($form_value['field_value_json'])) {
            $new_form_values[$form_value['field_name']] = $form_value['field_value_json'];
        } else {
            $new_form_values[$form_value['field_name']] = $form_value['field_value'];
        }
    }
    $form_values = $new_form_values;
}

$form_values_formated = [];
if ($form_values) {
    $countArrays = ceil(count($form_values) / 2);
    $form_values_formated[] = array_slice($form_values, 0, $countArrays);
    $form_values_formated[] = array_slice($form_values, $countArrays);
}


$created_by = false;
if (isset($item['created_by'])) {
    $created_by = get_user_by_id($item['created_by']);

    if (isset($created_by['username'])) {
        $created_by_username = $created_by['username'];
    } else {
        $created_by_username = false;
    }
}
?>

<script>
    $( document ).ready(function() {
        $('.collapse', '.js-form-entry-<?php print $item_id ?>').on('shown.bs.collapse', function () {
            $('.js-form-entry-<?php print $item_id ?>').prop('disabled',true).removeAttr('data-toggle');
        });
    });
</script>

<div class="js-form-entry-<?php print $item_id ?> card mb-2 not-collapsed-border collapsed card-message-holder <?php if (!isset($is_entry)): ?>card-bubble<?php endif; ?> <?php if (isset($item['is_read']) AND $item['is_read'] == 0): ?>active<?php endif; ?> bg-silver" data-bs-toggle="collapse" data-target="#notif-entry-item-<?php print $item_id ?>" aria-expanded="false" aria-controls="collapseExample">
    <div class="card-body">
        <?php if (isset($params['module']) and $params['module'] == 'admin/notifications'): ?>
            <div class="row align-items-center mb-3">
                <div class="col text-start text-left">
                    <span class="text-primary text-break-line-2"><?php _e("New form entry"); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="row align-items-center" data-bs-toggle="collapse" data-target="#notif-entry-item-<?php print $item_id ?>" >
            <div class="col" style="max-width:55px;">
                <i class="mdi mdi-email text-primary mdi-24px"></i>
            </div>
            <div class="col-10 col-sm item-id"><span class="text-primary">#<?php echo $entry_params['id']; ?></span></div>

            <div class="col-6 col-sm">
                <?php print date('M d, Y', strtotime($item['created_at'])); ?>
                <small class="text-muted"><?php print date('h:s', strtotime($item['created_at'])); ?>h</small>
            </div>

            <div class="col-6 col-sm"><?php print mw('format')->ago($item['created_at']); ?></div>
        </div>

        <div class="collapse" id="notif-entry-item-<?php print $item_id ?>">
            <hr class="thin" />
            <div class="row">

                <?php
                $iformVr=0;
                foreach ($form_values_formated as $form_values_row) {
                ?>
                <div class="col-md-6">
                    <?php  if ($iformVr==0){   ?><h6><strong><?php _e("Fields"); ?></strong></h6><?php  } ?>

                    <?php $iformVr++;?>

                    <?php if ($form_values_row){ ?>
                        <?php foreach ($form_values_row as $key => $val1){ ?>
                            <?php if (!is_array($val1)){ ?>
                                <div>
                                    <small class="text-muted"><?php echo str_replace('_', ' ', $key); ?>:</small>
                                    <p><?php print htmlentities($val1); ?></p>
                                </div>
                            <?php } else { ?>
                                <small class="text-muted"><?php echo str_replace('_', ' ', $key); ?>:</small>
                                <?php foreach ($val1 as $val1_1){ ?>
                                    <p><?php print htmlentities($val1_1) . '<br />'; ?></p>
                                <?php }?>
                            <?php } ?>
                        <?php } ?>
                        <?php } ?>

                    <?php
                    if ($iformVr == count($form_values_formated) && !empty($form_files)) {
                        ?>
                        <h6><strong><?php _e("Attached files"); ?></strong></h6>
                        <?php
                        foreach ($form_files as $fileNameKey=>$fileSettings){
                            if(!isset($fileSettings['url'])){
                                continue;
                            }


                            ?>
                            <div>
                                <small class="text-muted"><?php echo $fileNameKey; ?></small> <br />
                                <a href="<?php echo $fileSettings['url']; ?>" target="_blank">
                                    <i class="mdi mdi-file-check text-primary mdi-18px"></i> <?php echo str_limit(basename($fileSettings['url']),20); ?>
                                    (<?php echo app()->format->human_filesize($fileSettings['file_size']) ?>)
                                </a>
                            </div>
                            <?php
                        }
                    }
                    ?>

                </div>
                <?php
                }
                ?>

                <div class="col-md-12">
                    <script type="text/javascript">
                        function deleteFormEntry(e, entryId) {
                            e.stopPropagation();
                            mw.confirm('<?php _e('Are you sure you want to delete?'); ?>', function () {
                                mw.notification.msg('<?php _e('Deleting...'); ?>');
                                $.post(mw.settings.api_url+'delete_form_entry', {id: entryId}, function(msg) {
                                    $('.js-form-entry-' + entryId).fadeOut();
                                    mw.reload_module('#admin-dashboard-contact-form');
                                });
                            });
                        }
                    </script>
                    <button type="button" class="btn btn-outline-danger pull-right" onclick="deleteFormEntry(event,<?php echo $item_id; ?>)"><i class="mdi mdi-delete-outline"></i> <?php _e('Delete'); ?></button>
                </div>
            </div>


        </div>
    </div>
</div>
