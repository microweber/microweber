<?php

only_admin_access();


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

//d($confirm_key);

$get_existing_files_for_confirm = cache_get($confirm_key, 'composer');


//var_dump($get_existing_files_for_confirm);

?>


<script>
    mw.install_composer_package_confirm_by_key = function ($confirm_key, $require_name, $require_version) {
        mw.notification.success('Installing...',3000);

        mw.tools.loading(mwd.querySelector('.js-install-package-loading-container-confirm'), true)

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
                mw.tools.loading(mwd.querySelector('.js-install-package-loading-container-confirm'), false)

            }
        })

    }
</script>


<div class="js-install-package-loading-container-confirm">

    <h1>Please confirm the installation of <?php print $require_name ?></h1>
    <?php if ($get_existing_files_for_confirm) { ?>
        <ul>
            <?php foreach ($get_existing_files_for_confirm as $file) { ?>

                <li><?php print ($file) ?></li>
            <?php } ?>
        </ul>
    <?php } ?>


    <div id="js-buttons-confirm-install">

        <a class="mw-ui-btn" onclick="mw.tools.modal.get(this).remove()">Cancel</a>
        <a class="mw-ui-btn"
           onclick="mw.install_composer_package_confirm_by_key('<?php print $confirm_key ?>', '<?php print $require_name ?>','<?php print $require_version ?>')">Confirm</a>

    </div>
</div>