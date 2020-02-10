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


$screenshot = false;

if (isset($item['latest_version']) and isset($item['latest_version']['extra']) and isset($item['latest_version']['extra']['_meta']['screenshot'])) {
    $screenshot = $item['latest_version']['extra']['_meta']['screenshot'];
}

if (!$screenshot and isset($item['extra']) and isset($item['extra']['_meta']) and isset($item['extra']['_meta']['screenshot'])) {
    $screenshot = $item['extra']['_meta']['screenshot'];

}

if($item['name'] == 'microweber/update') {
    $screenshot = mw()->ui->admin_logo;
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


if(!isset($box_class)){
    $box_class = '';
}

?>




<div class="mw-ui-box ">


    <div class="mw-ui-box-content js-package-install-content">





        <?php  if ($screenshot): ?>
            <?php if($item['type'] == 'microweber-template' ) {

                ?>
                <div class="package-image package-<?php print $item['type'] ?>">
                    <img src="<?php print $screenshot; ?>" alt="">
                </div>

                <?php
            } else { ?>
                <div class="package-image package-<?php print $item['type'] ?>" style="background-image: url('<?php print $screenshot; ?>')"></div>
            <?php } ?>


        <?php else: ?>
            <?php if (!isset($no_img)): ?>
            <div class="package-image" style="  background-image: none"></div>
            <?php endif; ?>
        <?php endif; ?>



        <div class="package-item-footer">
            <div><a <?php print (isset($item['homepage']) ? 'href="' . $item['homepage'] . '"' : ''); ?> ><?php print $item['description'] ?></a></div>
            <div>
                <div>
                <?php _e('Version');?>

                <select class="mw-sel-item-key-install"  data-vkey="<?php print $key; ?>">

                    <option value="<?php print $item['latest_version']['version'] ?>">
                        <?php print $item['latest_version']['version'] ?>
                    </option>

                    <?php if (isset($item['versions']) and is_array($item['versions'])): ?>
                        <?php $item['versions'] = array_reverse($item['versions'])  ?>
                        <?php foreach($item['versions'] as $v_sel): ?>
                            <?php if ($v_sel['version']  !=  $item['latest_version']['version'] ): ?>
                                <option value="<?php print $v_sel['version']  ?>" >
                                    <?php print $v_sel['version']  ?>
                                </option>



                            <?php endif; ?>
                        <?php endforeach; ?>


                    <?php endif; ?>


                </select>
                </div>




                <div class="text-center">



                    <?php if ($has_update): ?>
                        <a
                            vkey="<?php print $vkey; ?>"
                            href="javascript:;"
                            onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                            class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-warn js-package-install-btn">
                            <?php _e('Update'); ?>
                        </a>
                    <?php else: ?>
                        <a
                            vkey="<?php print $vkey; ?>"
                            href="javascript:;"
                            onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                            class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification js-package-install-btn">
                            <?php _e('Install'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="js-package-install-preload"></div>

                </div>

            </div>
        </div>
        <?php
            $tooltipid =  uniqid('tooltip')
        ?>
        <span class="mw-ui-link tip" data-tip="#<?php print $tooltipid ?>">Information</span>
        <div id="<?php print $tooltipid ?>" style="display: none">
            <table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic" width="100%">
                <tbody>

                <tr>
                    <td><?php _e('License');?></td>
                    <td>
                        <?php if ($license): ?>
                            <?php print $license; ?>
                        <?php else: ?>
                            <?php _e(' N/A');?>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td><?php _e('Website');?></td>
                    <td>
                        <?php if (isset($item['homepage'])): ?>
                            <a href="<?php print $item['homepage']; ?>" target="_blank" class="mw-blue package-ext-link"><?php print $item['homepage']; ?></a>
                        <?php else: ?>
                            <?php _e(' N/A');?>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td><?php _e('Author');?></td>
                    <td><img src="<?php print $author_icon; ?>" style="max-height: 16px;"/> <?php print $author ?></td>
                </tr>

                <tr>
                    <td><?php _e('Release date');?></td>
                    <td><?php print date('d M Y', strtotime($item['latest_version']['release_date'])) ?></td>
                </tr>

                <tr style="display: none">
                    <td><?php _e('Keywords');?></td>
                    <td>
                        <?php if (isset($item['keywords'])): ?>
                            <?php print implode(", ",$item['keywords']); ?>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr style="display: none">
                    <td><?php _e('Support Source'); ?></td>
                    <td>
                        <?php if (isset($item['support']) AND isset($item['support']['source'])): ?>
                            <?php print $item['support']['source']; ?>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr style="display: none">
                    <td><?php _e('Support Issues'); ?></td>
                    <td>
                        <?php if (isset($item['support']) AND isset($item['support']['issues'])): ?>
                            <?php print $item['support']['issues']; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>



    </div>
</div>






