<?php only_admin_access(); ?>

<?php

  $search_packages = mw()->update->composer_search_packages();
//

//dd($search_packages);
?>


<script>
     mw.install_composer_package_by_package_name = function($key) {
        $.post("<?php print api_link('mw_composer_install_package_by_name'); ?>", { require_name: $key, require_version: "latest" })
            .done(function (msg) {
                d(msg);
                mw.notification.msg(this);
             //   reload_changes();
            });

    }
</script>


<pre id="remote_patch_log"><?php print_r($search_packages); ?></pre>
<table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table">
    <thead>
    <tr>
        <th>Package</th>
        <th>Version</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($search_packages)): ?>
        <tr>
        <?php foreach ($search_packages as $key => $val): ?>
            <td><?php print $key; ?></td>
            <td><?php print $val['type']; ?></td>
            <td><button type="button" onClick="mw.install_composer_package_by_package_name('<?php print $key; ?>')">install</button></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>