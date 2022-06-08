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

    $(document).ready(function () {
        $('.js-license-status').each(function (i,item) {
            var licenseId = $(item).data('id');
            $.ajax({
                type: "POST",
                data: {id:licenseId},
                url: "<?php print site_url('api') ?>/mw_consume_license",
            }).done(function (resp) {
                if (resp.valid) {
                    $(item).html("<div class='text-success'>"+resp.status+"</div>");
                } else {
                    $(item).html("<div class='text-danger'>"+resp.status+"</div>");
                }
            });
        });
    });
</script>

<?php if (is_array($lic) and !empty($lic)): ?>
    <div class="row p-2">
            <div class="col-12">
                <label class="control-label mb-0"><?php _e('Check your license'); ?></label>
                <small class="text-muted d-block"><?php _e('From this modal you can manipulate your licenses'); ?></small>
            </div>
        <div class="col-12 d-flex justify-content-center text-center p-3">

            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Key</th>
                    <th scope="col">Owner</th>
                    <th scope="col">Details</th>
                    <th style="col">Status</th>
                </tr>
                </thead>
                <tbody>

            <?php foreach ($lic as $item): ?>

                <tr>
                    <th scope="row">  <?php print $item['local_key']; ?></th>
                    <td>

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

                    </td>
                    <td>
                        <?php print $item['rel_type']; ?>
                        <?php if (isset($item['status']) and $item['status'] == 'active'): ?>
                            <small>
                                <label><?php _e("Registered on"); ?></label>
                                <?php print date('d M ,Y', strtotime($item['reg_on'])); ?><br>
                                <?php endif; ?>
                                <?php if (isset($item['due_on']) and $item['due_on'] != ''): ?>
                                    <label><?php _e("Next payment on"); ?></label> <?php print date('d M ,Y', strtotime($item['due_on'])); ?>
                                <?php endif; ?>
                                </ul>
                            </small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="js-license-status" data-id="<?php echo $item['id']; ?>">
                            <img src="<?php echo userfiles_url(); ?>modules/microweber/img/img_check_loading.gif" />
                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <div class="col">
            <?php if (is_array($lic) and !empty($lic)): ?>

                <a class="btn btn-primary btn-sm" href="javascript:mw.validate_licenses();"><?php _e('Validate'); ?></a>
            <?php endif; ?>
        </div>
        <div class="col text-end text-right">
            <a class="btn btn-danger btn-sm" href="javascript:mw.delete_licence('<?php echo $item['id'] ?>');"><?php _e('Delete'); ?></a>

            <a class="btn btn-success btn-sm" href="javascript:mw.edit_licence('0');"><?php _e('Add License'); ?></a>
    </div>
    </div>
<?php else : ?>

    <a class="btn btn-success btn-sm" href="javascript:mw.edit_licence('0');"><?php _e('Add License'); ?></a>


<?php endif; ?>


