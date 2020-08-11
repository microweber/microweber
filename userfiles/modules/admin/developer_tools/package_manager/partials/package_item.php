<?php only_admin_access() ?>
<?php


include(__DIR__ . '/package_data.php');


?>


<div class="mw-ui-box ">
    <div class="mw-ui-box-content js-package-install-content">

        <?php if ($item['type'] != 'microweber-core-update'): ?>


            <?php if ($screenshot): ?>
                <?php if ($item['type'] == 'microweber-template'): ?>
                    <a target="_blank" href="<?php print $item['homepage']; ?>"
                       class="package-image package-<?php print $item['type'] ?>"
                       style="width: calc(100% + 24px); margin: -12px -12px 0 -12px !important;">
                        <img src="<?php print $screenshot; ?>" alt="">
                    </a>
                <?php else: ?>
                    <a target="_blank" href="<?php print $item['homepage']; ?>"
                       class="package-image package-<?php print $item['type'] ?>"
                       style="background-image: url('<?php print $screenshot; ?>')"></a>
                <?php endif; ?>
            <?php else: ?>
                <?php if (!isset($no_img)): ?>
                    <div class="package-image" style="  background-image: none"></div>
                <?php endif; ?>
            <?php endif; ?>

        <?php endif; ?>


        <div class="package-item-footer">
            <div class="mw-ui-row">
                <div class="mw-ui-col title">
                    <a <?php print (isset($item['homepage']) ? 'href="' . $item['homepage'] . '"' : ''); ?> ><?php print $item['description'] ?></a>
                </div>
                <div class="mw-ui-col text-right" style="align-items: flex-end;">
                    <div>
                        <?php _e('Version'); ?>

                        <select class="mw-sel-item-key-install" data-vkey="<?php print $key; ?>">
                            <option value="<?php print $item['latest_version']['version'] ?>"><?php print $item['latest_version']['version'] ?></option>
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

            <div class="mw-ui-row m-t-10">
                <div class="mw-ui-col">
                    <?php if ($is_core_update): ?>
                        <div>
                            <?php include(__DIR__ . '/package_data_tooltip.php'); ?>
                        </div>
                    <?php endif; ?>


                    <?php $tooltipid = uniqid('tooltip'); ?>
                    <span class="mw-ui-link tip" data-tip="#<?php print $tooltipid ?>"
                          data-trigger="click">Information</span>
                    <div id="<?php print $tooltipid ?>" style="display: none">
                        <?php include(__DIR__ . '/package_data_tooltip.php'); ?>
                    </div>

                </div>

                <div class="mw-ui-col" style="align-items: flex-end;">
                    <div class="text-center">
                        <?php if ($has_update): ?>
                            <a vkey="<?php print $vkey; ?>" href="javascript:;"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                               class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-warn js-package-install-btn"><?php _e('Update'); ?></a>

                        <?php elseif (!$has_update AND isset($item['current_install']) and $item['current_install']): ?>
                            <div class="text-success js-package-install-btn-help-text">Installed</div>
                            <a vkey="<?php print $vkey; ?>" href="javascript:;"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                               class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification js-package-install-btn" style="display: none">
                                <?php if ($is_commercial): ?>Buy & <?php endif; ?> <?php _e('Install'); ?>
                            </a>
                        <?php else: ?>
                            <a vkey="<?php print $vkey; ?>" href="javascript:;"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)"
                               class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification js-package-install-btn">
                                <?php if ($is_commercial): ?>Buy & <?php endif; ?> <?php _e('Install'); ?>
                            </a>
                        <?php endif; ?>

                        <div class="js-package-install-preload"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






