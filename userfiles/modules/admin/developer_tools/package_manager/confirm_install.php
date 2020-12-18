<?php

if (!mw_is_installed()) {

} else {
    only_admin_access();

}


$confirm_key = $require_name = $require_version = $rel_type = '';

if (isset($params['confirm_key'])) {
    $confirm_key = $params['confirm_key'];
}

if (isset($params['require_name'])) {
    $require_name = $params['require_name'];
}

if (isset($params['require_version'])) {
    $require_version = $params['require_version'];
}


if (isset($params['rel_type'])) {
    $rel_type = $params['rel_type'];
}

if (!$confirm_key) {
    return;
}

$confirm_files_count = 0;
$get_existing_files_for_confirm = cache_get($confirm_key, 'composer');

if (is_array($get_existing_files_for_confirm)) {
    $confirm_files_count = count($get_existing_files_for_confirm);
} else {
    return;
}


?>


<script>
    mw.install_composer_package_confirm_by_key = function ($confirm_key, $require_name, $require_version) {
        mw.notification.success('Installing...', 6000);

        $('#js-buttons-confirm-install-link').addClass('disabled');
        if (typeof(mw.admin) != 'undefined') {
            mw.admin.admin_package_manager.set_loading(true)
        }
        mw.spinner({element: "#js-buttons-confirm-install-link", size: 30, color: 'white'}).show()


        var values = {confirm_key: $confirm_key, require_version: $require_version, require_name: $require_name};
        $.ajax({
            url: "<?php print api_link('mw_composer_install_package_by_name'); ?>",
            type: "post",
            data: values,
            success: function (msg) {
                mw.notification.msg(msg, 3000);
                if (msg.success) {
                    mw.tools.modal.get('#js-buttons-confirm-install').remove()
                }

                if (typeof(mw.marketplace_dialog_jquery_ui) != 'undefined') {
                    mw.marketplace_dialog_jquery_ui.dialog('close');
                }

                if (typeof(getTemplateForInstallScreen) != 'undefined') {
                    getTemplateForInstallScreen();
                }

                if (typeof(mw.admin) != 'undefined') {
                    mw.admin.admin_package_manager.set_loading(false)
                    mw.admin.admin_package_manager.reload_packages_list();
                }

            },
            always: function () {

                $('#js-buttons-confirm-install-link').removeClass('disabled');

            }
        })

    }
</script>
<script>

    $(document).ready(function () {

        $('.js-show-files').on('click', function () {

            $('.js-files').toggleClass('hidden');
        });

        $(function () {
            $(".js-show-files").click(function () {
                $(this).text(function (i, text) {
                    return text === "Hide files" ? "Show files" : "Hide files";
                })
            });
        })
    });
</script>

<style>
    .js-files {
        max-height: 300px;
        overflow: scroll;
    }
</style>


<div class="js-install-package-loading-container-confirm">
    <div class="">
        <div class="mw-flex-row text-center">
            <div class="mw-flex-col-xs-12">
                <div class="mw-ui-box text-center">
                    <div class="mw-ui-box-header">
                        <h4>Please confirm the installation of <br/> <strong><?php print $require_name ?></strong></h4>
                        <h5>Version <?php print $require_version ?> </h5>
                        <h6><?php print count($get_existing_files_for_confirm); ?> files will be installed</h6>

                    </div>
                    <div class="mw-ui-box-content mw-text p-0">
                        <?php if ($get_existing_files_for_confirm) { ?>
                            <div class="js-files hidden">
                                <table class="mw-ui-table mw-full-width mw-ui-table-basic text-left"
                                       style="table-layout: fixed;">
                                    <thead>
                                    <tr>
                                        <th>File location</th>
                                    </tr>


                                    </thead>

                                    <?php
                                    $i = 0;

                                    foreach ($get_existing_files_for_confirm as $file) { ?>
                                        <tr>
                                            <td><?php print ($file) ?></td>
                                        </tr>
                                        <?php

                                        if ($i > 1000) {
                                            break;
                                        }

                                        $i++;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <div id="js-buttons-confirm-install" style="padding: 20px;">
                            <a class="mw-ui-btn mw-ui-btn-important" onclick="mw.dialog.get(this).remove()">Cancel</a>

                            <?php if ($get_existing_files_for_confirm) { ?>
                                <button type="button" class="js-show-files mw-ui-btn mw-ui-btn-info">Show files</button>
                            <?php } ?>

                            <a id="js-buttons-confirm-install-link" class="mw-ui-btn mw-ui-btn-notification"
                               onclick="mw.install_composer_package_confirm_by_key('<?php print $confirm_key ?>', '<?php print $require_name ?>','<?php print $require_version ?>')">Confirm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>