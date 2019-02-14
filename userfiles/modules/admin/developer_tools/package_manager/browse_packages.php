<?php only_admin_access(); ?>

<?php

  $search_packages = mw()->update->composer_search_packages();


//

//dd($search_packages);
?>


<script>

    mw.require('admin_package_manager.js');


</script>


<table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table js-install-package-loading-container"  >
    <thead>
    <tr>
        <th>Package</th>
        <th>Type</th>
        <th></th>
        <th>Versions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($search_packages)): ?>
        <tr>
        <?php foreach ($search_packages as $key => $val): ?>
            <td><?php print $key; ?></td>
            <td><?php print $val['type']; ?></td>
            <td><button type="button" onClick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>','latest')">install</button></td>
            <td>

            <?php if (isset($val['versions']) and !empty($val['versions'])): ?>
                <?php foreach ($val['versions'] as $vkey => $vval): ?>
                    <button type="button" onClick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>','<?php print $vkey; ?>')"><?php print $vkey; ?></button>

                <?php endforeach; ?>


                <?php //  dd ($val['versions']); ?>
            <?php endif; ?>
            </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>


