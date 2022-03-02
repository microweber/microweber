<?php
if (!user_can_access('module.marketplace.index')) {
    return;
}
?>
<?php

include(__DIR__ . '/package_data.php');


if(!$item){
    return;
}

if(!isset($key)){
    return;
}
if(!isset($item['type'] )){
    return;
}

?>
<?php $tooltipid = uniqid('tooltip'); ?>

<?php if ($item['type'] === 'microweber-module'): ?>
    <div class="js-package-install-content h-100">
        <div class="card style-1 bg-light h-100 w-100">
            <div class="card-body p-3 d-flex flex-column justify-content-between h-100">
                <div>
                    <?php if ($item['type'] != 'microweber-core-update'): ?>

                        <?php if ($screenshot): ?>
                            <img src="<?php print thumbnail($screenshot, 70, 70); ?>" style="width: 65px;"
                                 class="pl-2 pb-2 float-right"/>
                        <?php else: ?>
                            <div class="pl-2 pb-2 float-right">
                                <i class="mdi mdi-view-grid-plus mdi-64px text-muted lh-1_0" style="opacity:0.5"></i>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (!$has_update AND isset($item['current_install']) and $item['current_install']): ?>
                    <?php
                    $href_open_mod = module_admin_url($item['current_install']['module_details']['module']);
                    ?>
                    <a href="<?php echo $href_open_mod; ?>" class="btn btn-md btn-link  p-0 font-weight-bold">
                        <?php print $item['description'] ?>
                    </a>
                    <?php else: ?>
                        <span class=" p-0 font-weight-bold">
                            <?php print $item['description'] ?>
                        </span>
                    <?php endif; ?>

                    <!---
                    <?php if (isset($item['homepage'])): ?>
                    <a href="<?php echo $item['homepage'];?>" class="btn btn-link btn-sm p-0 text-muted tip">
                        <i class="mdi mdi-home-outline"></i>
                    </a>
                    <?php endif; ?>
-->
                    <span class="btn btn-link btn-sm p-0 text-muted tip" data-tip="#<?php print $tooltipid ?>" data-trigger="click">
                        <i class="mdi mdi-information-outline"></i>
                    </span>

                    <!---
                    <?php if (!$has_update AND isset($item['current_install']) and $item['current_install']): ?>
                        <p class="mt-3 text-success">
                            <?php
                            $href_open_mod = module_admin_url($item['current_install']['module_details']['module']);
                            ?>
                            <a href="<?php print $href_open_mod ?>" class="btn btn-sm btn-info">
                                <?php _e('Open'); ?>
                            </a>
                        </p>
                    <?php else : ?>
                        <p class="mt-3"></p>
                    <?php endif; ?>
                    -->
                </div>

                <div class="package-item-footer">
                    <div id="<?php print $tooltipid ?>" style="display: none">
                        <?php include(__DIR__ . '/package_data_tooltip.php'); ?>
                    </div>

                    <?php if ($is_core_update): ?>
                        <div class="row mt-2">
                            <div class="col">
                                <?php include(__DIR__ . '/package_data_tooltip.php'); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row mt-2">
                        <div class="col-auto">
                            <div>
                                <small class="text-muted">v.</small>
                                <div class="d-inline-block">
                                    <select class="mw-sel-item-key-install selectpicker" data-style="btn-sm"
                                            data-width="80px" data-size="5" data-vkey="<?php print $key; ?>">
                                        <option
                                            value="<?php print $item['latest_version']['version'] ?>"><?php print $item['latest_version']['version'] ?></option>
                                        <?php if (isset($item['versions']) and is_array($item['versions'])): ?>
                                            <?php $item['versions'] = array_reverse($item['versions']) ?>
                                            <?php foreach ($item['versions'] as $v_sel): ?>
                                                <?php if ($v_sel['version'] != $item['latest_version']['version']): ?>
                                                    <option value="<?php print $v_sel['version'] ?>">
                                                        <?php print $v_sel['version'] ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col text-end text-right">

                            <?php  if(isset($item['is_symlink']) && $item['is_symlink']): ?>


                            <?php else: ?>

                            <?php if ($has_update): ?>
                                <a vkey="<?php print $vkey; ?>" href="javascript:;" id="js-install-package-<?php echo $item['target-dir']; ?>"
                                   onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                                   class="btn btn-sm btn-warning js-package-install-btn"><?php _e('Update'); ?></a>
                            <?php elseif (!$has_update AND isset($item['current_install']) and $item['current_install']): ?>
                                <div
                                    class="text-success js-package-install-btn-help-text"><?php _e('Installed'); ?></div>
                                <a vkey="<?php print $vkey; ?>" href="javascript:;" id="js-install-package-<?php echo $item['target-dir']; ?>"
                                   onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                                   class="btn btn-sm btn-outline-primary js-package-install-btn"
                                   style="display: none"><?php if ($is_commercial): ?>Buy & <?php endif; ?> <?php _e('Install'); ?></a>
                            <?php else: ?>
                                <a vkey="<?php print $vkey; ?>" href="javascript:;" id="js-install-package-<?php echo $item['target-dir']; ?>"
                                   onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                                   class="btn btn-sm btn-outline-primary js-package-install-btn"><?php if ($is_commercial): ?>Buy & <?php endif; ?> <?php _e('Install'); ?></a>
                            <?php endif; ?>

                            <div class="js-package-install-preload"></div>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="js-package-install-content">
        <div class="card style-1 bg-light">
            <div class="card-body pb-3">
                <?php if ($item['type'] != 'microweber-core-update'): ?>
                    <?php if (isset($screenshot) and $screenshot): ?>
                        <a target="_blank" href="<?php print $item['homepage']; ?>"
                           class="package-image package-<?php print $item['type'] ?>"
                           style="width: calc(100% + 24px); margin: -12px -12px 0 -12px !important;">
                            <img src="<?php print thumbnail($screenshot, 400); ?>" alt="">
                        </a>

                    <?php else: ?>
                        <?php if (!isset($no_img)): ?>
                            <div class="package-image" style="  background-image: none"></div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="package-item-footer">

                    <div class="row">
                        <div class="col">
                            <a <?php print (isset($item['homepage']) ? 'href="' . $item['homepage'] . '"' : ''); ?>
                                class="btn btn-lg btn-link text-dark p-0"><?php print $item['description'] ?></a>
                        </div>

                        <div class="col text-end text-right">

                            <?php
                            //$item['is_symlink'] = true; // niki otkomentirai tova ako iskash da go stilnesh, sled tva pak si go vurni
                            if (isset($item['is_symlink']) && $item['is_symlink']):
                            ?>

                            <?php else: ?>

                            <div>
                                <small class="text-muted"><?php _e('v.'); ?></small>
                                <div class="d-inline-block">
                                    <select class="mw-sel-item-key-install selectpicker" data-style="btn-sm"
                                            data-width="80px" data-size="5" data-vkey="<?php print $key; ?>">
                                        <option
                                            value="<?php print $item['latest_version']['version'] ?>"><?php print $item['latest_version']['version'] ?></option>
                                        <?php if (isset($item['versions']) and is_array($item['versions'])): ?>
                                            <?php $item['versions'] = array_reverse($item['versions']) ?>
                                            <?php foreach ($item['versions'] as $v_sel): ?>
                                                <?php if ($v_sel['version'] != $item['latest_version']['version']): ?>
                                                    <option value="<?php print $v_sel['version'] ?>">
                                                        <?php print $v_sel['version'] ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col">
                            <a href="#" onclick="previewPackage('<?php echo $item['name']; ?>','<?php echo $item['latest_version']['version']; ?>')" class="btn btn-link btn-sm p-0">
                                <?php _e("Information"); ?>
                            </a>
                        </div>

                        <div class="col text-end text-right">

                            <?php if (template_name() == $item['target-dir']): ?>
                                <div class="text-success js-package-install-btn-help-text"><?php _e('Current'); ?></div>
                            <?php endif; ?>

                            <?php
                                if(isset($item['extra']['preview_url'])):
                            ?>
                            <a href="<?php echo $item['extra']['preview_url']; ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                <?php _e('Demo'); ?>
                            </a>
                            <?php
                            endif;
                            ?>

                            <?php if ($has_update): ?>
                                <a vkey="<?php print $vkey; ?>" href="javascript:;" id="js-install-package-<?php echo $item['target-dir']; ?>"
                                   onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                                   class="btn btn-sm btn-warning js-package-install-btn"><?php _e('Update'); ?></a>
                            <?php elseif (!$has_update AND isset($item['current_install']) and $item['current_install']): ?>
                                <div
                                    class="text-success js-package-install-btn-help-text"><?php _e('Installed'); ?></div>
                                <a vkey="<?php print $vkey; ?>" href="javascript:;" id="js-install-package-<?php echo $item['target-dir']; ?>"
                                   onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                                   class="btn btn-sm btn-success js-package-install-btn"
                                   style="display: none"><?php if ($is_commercial): ?>Buy & <?php endif; ?> <?php _e('Install'); ?></a>
                            <?php else: ?>
                                <a vkey="<?php print $vkey; ?>" href="javascript:;" id="js-install-package-<?php echo $item['target-dir']; ?>"
                                   onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                                   class="btn btn-sm btn-success js-package-install-btn"><?php if ($is_commercial): ?>Buy & <?php endif; ?> <?php _e('Install'); ?></a>
                            <?php endif; ?>

                            <?php if (isset($item['current_install']) and $item['current_install']): ?>
                            <a href="<?php echo admin_url(); ?>view:content/action:settings?group=template&template=<?php echo $item['target-dir']; ?>" class="btn btn-sm btn-outline-primary"><?php _e("Use"); ?></a>
                            <?php endif; ?>

                            <div class="js-package-install-preload"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
