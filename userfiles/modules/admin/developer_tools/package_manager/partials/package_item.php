<?php only_admin_access() ?>





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

$key = $item['name'];
$vkey = 'latest';



?>

<div class="mw-ui-box" style="min-height: 300px;">
    <div class="mw-ui-box-header">
        <span class="mw-icon-gear"></span><span> <?php print $item['name'] ?></span>
    </div>


    <div class="mw-ui-box-content">

        <p class="m-b-20"><?php print $item['description'] ?></p>

        <table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
            <tbody>
            <tr>
                <td>Version</td>
                <td><?php print $item['latest_version']['version'] ?></td>
            </tr>


            <?php if ($license): ?>
            <tr>
                <td>License</td>
                <td><?php print $license; ?></td>
            </tr>
            <?php endif; ?>


            <?php if (isset($item['homepage'])): ?>
            <tr>
                <td>Website</td>
                <td>
                    <?php if (isset($item['homepage'])): ?>
                        <a href="<?php print $item['homepage']; ?>" target="_blank" class="mw-blue"><?php print $item['homepage']; ?></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endif; ?>
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
            <a   href="<?php print $item['homepage']; ?>" target="_blank"    class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">Read more</a>
            <?php endif; ?>


            <a href="javascript:;" onClick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>','<?php print $vkey; ?>')" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification">Install</a>





        </div>
    </div>
</div>




