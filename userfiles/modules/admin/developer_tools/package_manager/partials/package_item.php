<?php
if (!user_can_access('module.marketplace.index')) {
    return;
}
?>
<?php
include(__DIR__ . '/package_data.php');
?>

<div class="js-package-install-content">
    <div class="card style-1 bg-light">
        <div class="card-body pb-3">
            <?php if ($item['type'] != 'microweber-core-update'): ?>
                <?php if ($screenshot): ?>
                    <?php if ($item['type'] == 'microweber-template'): ?>
                        <a target="_blank" href="<?php print $item['homepage']; ?>" class="package-image package-<?php print $item['type'] ?>" style="width: calc(100% + 24px); margin: -12px -12px 0 -12px !important;">
                            <img src="<?php print $screenshot; ?>" alt="">
                        </a>
                    <?php else: ?>
                        <a target="_blank" href="<?php print $item['homepage']; ?>" class="package-image package-<?php print $item['type'] ?>" style="background-image: url('<?php print $screenshot; ?>')"></a>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (!isset($no_img)): ?>
                        <div class="package-image" style="  background-image: none"></div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <div class="package-item-footer">
                <div class="row">
                    <div class="col">
                        <a <?php print (isset($item['homepage']) ? 'href="' . $item['homepage'] . '"' : ''); ?> class="btn btn-lg btn-link text-dark p-0"><?php print $item['description'] ?></a>
                    </div>

                    <div class="col text-right">
                        <div>
                            <?php _e('Version'); ?>
                            <div class="d-inline-block">
                                <select class="mw-sel-item-key-install selectpicker" data-style="btn-sm" data-width="80px" data-size="5" data-vkey="<?php print $key; ?>">
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
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <?php if ($is_core_update): ?>
                            <div>
                                <?php include(__DIR__ . '/package_data_tooltip.php'); ?>
                            </div>
                        <?php endif; ?>
                        <?php $tooltipid = uniqid('tooltip'); ?>
                        <span class="btn btn-link btn-sm p-0 text-muted tip" data-tip="#<?php print $tooltipid ?>" data-trigger="click">Information</span>
                        <div id="<?php print $tooltipid ?>" style="display: none">
                            <?php include(__DIR__ . '/package_data_tooltip.php'); ?>
                        </div>
                    </div>

                    <div class="col text-right">
                        <?php if ($has_update): ?>
                            <a vkey="<?php print $vkey; ?>" href="javascript:;" onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)" class="btn btn-sm btn-warning js-package-install-btn"><?php _e('Update'); ?></a>
                        <?php elseif (!$has_update AND isset($item['current_install']) and $item['current_install']): ?>
                            <div class="text-success js-package-install-btn-help-text">Installed</div>
                            <a vkey="<?php print $vkey; ?>" href="javascript:;" onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)" class="btn btn-sm btn-success js-package-install-btn" style="display: none"><?php if ($is_commercial): ?>Buy & <?php endif; ?> <?php _e('Install'); ?></a>
                        <?php else: ?>
                            <a vkey="<?php print $vkey; ?>" href="javascript:;" onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('<?php print $key; ?>',$(this).attr('vkey'), this)" class="btn btn-sm btn-success js-package-install-btn"><?php if ($is_commercial): ?>Buy & <?php endif; ?> <?php _e('Install'); ?></a>
                        <?php endif; ?>

                        <div class="js-package-install-preload"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
