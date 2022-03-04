<?php

use MicroweberPackages\Package\MicroweberComposerClient;

$composerClient = new MicroweberComposerClient();
$item = $composerClient->getPackageByName($params['package_name']);
$item = \MicroweberPackages\Package\MicroweberComposerPackage::format($item);

include_once 'partials/package_data.php';
?>

<style>
    .mw-universal-tooltip {
        background: transparent;
        border-color: #eeeff1;
        -webkit-box-shadow: -1px 1px 1px 0px rgba(0, 0, 0, 0.11);
        -moz-box-shadow: -1px 1px 1px 0px rgba(0, 0, 0, 0.11);
        box-shadow: -1px 1px 1px 0px rgba(0, 0, 0, 0.11);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        padding: 0;
    }

    .mw-tooltip-content {
        padding: 0 !important;
    }
</style>

<div class="row">
    <div class="col-md-8">

        <?php if (isset($screenshot) and $screenshot): ?>
            <img src="<?php print thumbnail($screenshot, 880); ?>" alt="">
        <?php endif; ?>

        <?php
        if (isset($item['extra']['_meta']['readme'])) {
            $readmeContent = app()->http->url($item['extra']['_meta']['readme'])->get();
            echo $readmeContent;
        }
        ?>

    </div>
    <div class="col-md-4">
        <table cellspacing="0" cellpadding="0" class="table table-striped bg-white m-0" width="100%">
            <tbody>

            <tr>
                <td><?php _e('License'); ?></td>
                <td>
                    <?php if ($license): ?>
                        <?php print $license; ?>
                    <?php else: ?>
                        <?php _e('N/A'); ?>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td><?php _e('Website'); ?></td>
                <td>
                    <?php if (isset($item['homepage'])): ?>
                        <a href="<?php print $item['homepage']; ?>" target="_blank"
                           class="mw-blue package-ext-link"><?php print $item['homepage']; ?></a>
                    <?php else: ?>
                        <?php _e('N/A'); ?>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td><?php _e('Author'); ?></td>
                <td><?php if (isset($author_icon) and $author_icon): ?><img src="<?php print $author_icon; ?>"
                                                                            style="max-height: 16px;"/><?php endif; ?> <?php print $author ?>
                </td>
            </tr>

            <?php if (isset($item['latest_version']['changelog'])): ?>
                <tr>
                    <td><?php _e('Changelog'); ?></td>
                    <td><a href="<?php print $item['latest_version']['changelog']; ?>" target="_blank">Click to
                            open</a></td>
                </tr>
            <?php endif; ?>

            <tr>
                <td><?php _e('Release date'); ?></td>

                <td>
                    <?php if (isset($item['latest_version']['release_date'])): ?>
                        <?php print date('d M Y', strtotime($item['latest_version']['release_date'])) ?>
                    <?php else: ?>
                        No release date
                    <?php endif; ?>
                </td>
            </tr>


            <tr>
                <td><?php _e('Keywords'); ?></td>
                <td>
                    <?php if (isset($item['keywords'])): ?>
                        <?php print implode(", ", $item['keywords']); ?>
                    <?php endif; ?>
                </td>
            </tr>

            </tbody>
        </table>


        <div class="row mt-2">

            <?php

            if (isset($item['latest_version']['version'])): ?>
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
            <?php endif; ?>

            <div class="col text-end text-center">

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
