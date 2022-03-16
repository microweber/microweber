<?php must_have_access(); ?>
<?php $lic = mw()->update->get_licenses('limit=10000'); ?>

<script>
    mw.delete_licence = function ($lic_id) {

        $.ajax({
            url: "<?php print site_url('api') ?>/mw_delete_license?id=" + $lic_id
        }).done(function () {
            mw.reload_module("#<?php print $params['id'] ?>");

        });
        mw.reload_module("#lic_" + $lic_id);
    }

    mw.edit_licence = function ($lic_id) {

         licensemodal = mw.dialog({
            content: '<div type="settings/group/license_edit"  lic_id="' + $lic_id + '" class="module" id="lic_' + $lic_id + '"></div>',
            onremove: function () {
                mw.reload_module("#<?php print $params['id'] ?>");
            },
            name: 'licensemodal'
        });

        mw.reload_module("#lic_" + $lic_id);
    }


    mw.validate_licenses = function () {
        $.ajax({
            url: "<?php print site_url('api') ?>/mw_validate_licenses"
        }).done(function () {
            mw.reload_module("#<?php print $params['id'] ?>");

        });
    }
</script>

<?php if (is_array($lic) and !empty($lic)): ?>
    <div class="row p-2">
            <div class="col-12">
                <label class="control-label mb-0"><?php _e('Check your license'); ?></label>
                <small class="text-muted d-block"><?php _e('From this modal you can manipulate your licenses'); ?></small>
            </div>
        <div class="col-12 d-flex justify-content-center text-center p-3">
            <?php foreach ($lic as $item): ?>
                <div class="col-5 border p-1">
                    <label class="control-label my-2"> <?php _e('License'); ?> </label>
                    <p> <?php print $item['rel_type']; ?>
                        <?php if (isset($item['status']) and $item['status'] == 'active'): ?>
                            <small>
                                <label class="font-weight-bold">
                                    <?php if (isset($item['rel_name']) and $item['rel_name'] != ''): ?>
                                        <?php print $item['rel_name']; ?>
                                    <?php endif; ?>
                                    <?php if (isset($item['registered_name']) and $item['registered_name'] != ''): ?>
                                        <?php print $item['registered_name']; ?>
                                    <?php endif; ?>
                                    <?php if (isset($item['company_name']) and $item['company_name'] != ''): ?>
                                        <?php print $item['company_name']; ?>
                                    <?php endif; ?>
                                    <?php if (isset($item['reg_on']) and $item['reg_on'] != ''): ?>
                                </label>
                                    <br><label><?php _e("Registered on"); ?></label>
                                     <?php print date('d M ,Y', strtotime($item['reg_on'])); ?><br>
                                <?php endif; ?>
                                <?php if (isset($item['due_on']) and $item['due_on'] != ''): ?>
                                    <label><?php _e("Next payment on"); ?></label> <?php print date('d M ,Y', strtotime($item['due_on'])); ?>
                                <?php endif; ?>
                                </ul>
                            </small>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-5 border p-1">
                    <label class="control-label my-2">
                        <?php _e('Key'); ?>
                    </label>
                    <p>
                        <?php print $item['local_key']; ?>
                    </p>
                </div>
                <div class="col-2 border p-1">
                    <label class="control-label my-2">
                        <?php _e('Status'); ?>
                    </label>
                    <p>
                        <?php print ucwords($item['status']); ?>
                    </p>
                </div>
                <div class="col-2 border p-1">
                    <label class="control-label my-2">
                        <?php _e('Action'); ?>
                    </label>
                    <p>
                        <a class="btn btn-danger btn-sm" href="javascript:mw.delete_licence('<?php echo $item['id'] ?>');"><?php _e('Delete'); ?></a>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col">
            <?php if (is_array($lic) and !empty($lic)): ?>
                <a class="btn btn-primary btn-sm" href="javascript:mw.validate_licenses();"><?php _e('Validate'); ?></a>
            <?php endif; ?>
        </div>
        <div class="col text-end text-right">
            <a class="btn btn-success btn-sm" href="javascript:mw.edit_licence('0');"><?php _e('Add License'); ?></a>
    </div>
    </div>
<?php else : ?>

    <a class="btn btn-success btn-sm" href="javascript:mw.edit_licence('0');"><?php _e('Add License'); ?></a>


<?php endif; ?>
