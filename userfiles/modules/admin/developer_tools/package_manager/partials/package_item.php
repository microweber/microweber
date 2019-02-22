<?php only_admin_access() ?>


<style>
    .package-image {
        display: block;
        width: 150px;
        height: 150px;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        margin: 10px auto 10px auto !important;
    }
</style>

<?php


$author = array_first(explode('/', $item['name']));
if (isset($item['authors']) and isset($item['authors'][0]) and isset($item['authors'][0]['name'])) {
    $author = $item['authors'][0]['name'];

}
$license = false;
if (isset($item['license']) and isset($item['license'][0])) {
    $license = $item['license'][0];

}

$author_icon = false;
if (isset($item['extra']) and isset($item['extra']['_meta']) and isset($item['extra']['_meta']['avatar'])) {
    $author_icon = $item['extra']['_meta']['avatar'];

}


$screenshot = false;
if (isset($item['extra']) and isset($item['extra']['_meta']) and isset($item['extra']['_meta']['screenshot'])) {
    $screenshot = $item['extra']['_meta']['screenshot'];

}


$key = $item['name'];
$vkey = 'latest';

if (isset($item['latest_version']) and isset($item['latest_version']['version'])) {
    $vkey = $item['latest_version']['version'];
}


$local_install = false;
$local_install_v = false;

if (isset($item['current_install']) and $item['current_install']) {

    $local_install = $item['current_install'];
    if (isset($local_install['version']) and $local_install['version']) {
        $local_install_v = $local_install['version'];
    }
}

$has_update = false;
if (isset($item['has_update']) and $item['has_update']) {
    $has_update = true;
}


?>

<div class="mw-ui-box" style="min-height: 300px;">
    <div class="mw-ui-box-header">
        <span class="mw-icon-gear"></span><span> <?php print $item['name'] ?></span>

        <?php if ($local_install) { ?>
            <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-notification pull-right">Your version <?php print $local_install_v ?></span>
        <?php } ?>
    </div>


    <div class="mw-ui-box-content">

        <p class="m-b-20"><?php print $item['description'] ?></p>

        <?php if ($screenshot): ?>
            <div class="package-image" style="background-image: url('<?php print $screenshot; ?>')"></div>

        <?php else: ?>
            <div class="package-image" style="background-image: url('')"></div>
        <?php endif; ?>

        <table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
            <tbody>
            <tr>
                <td>Version</td>
                <td><?php print $item['latest_version']['version'] ?></td>
            </tr>

            <tr>
                <td>License</td>
                <td>
                    <?php if ($license): ?>
                        <?php print $license; ?>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td>Website</td>
                <td>
                    <?php if (isset($item['homepage'])): ?>
                        <a href="<?php print $item['homepage']; ?>" target="_blank" class="mw-blue"><?php print $item['homepage']; ?></a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td>Author</td>
                <td><img src="<?php print $author_icon; ?>" style="max-height: 16px;"/> <?php print $author ?></td>
            </tr>

            <tr>
                <td>Release date</td>
                <td><?php print date('d M Y', strtotime($item['latest_version']['release_date'])) ?></td>
            </tr>

            <tr style="display: none">
                <td>Keywords</td>
                <td>
                    <?php if (isset($item['keywords'])): ?>
                        <?php print implode($item['keywords'], ", "); ?>
                    <?php endif; ?>
                </td>
            </tr>

            <tr style="display: none">
                <td>Support Source</td>
                <td>
                    <?php if (isset($item['support']) AND isset($item['support']['source'])): ?>
                        <?php print $item['support']['source']; ?>
                    <?php endif; ?>
                </td>
            </tr>

            <tr style="display: none">
                <td>Support Issues</td>
                <td>
                    <?php if (isset($item['support']) AND isset($item['support']['issues'])): ?>
                        <?php print $item['support']['issues']; ?>
                    <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="text-center m-t-20">


            <?php if (isset($item['homepage'])): ?>
                <a href="<?php print $item['homepage']; ?>" target="_blank"
                   class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">Read more</a>
            <?php endif; ?>

            <?php if ($has_update): ?>
                <a href="javascript:;" onClick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>','<?php print $vkey; ?>')" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-warn">Update</a>
            <?php else: ?>
                <a href="javascript:;" onClick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>','<?php print $vkey; ?>')" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification">Install</a>
            <?php endif; ?>


        </div>
    </div>
</div>




