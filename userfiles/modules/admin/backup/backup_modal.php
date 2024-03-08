<template id="backup-modal">

    <div class="mw-backup" id="mw-backup-type">

        <div class="mw-backup-options">

            <h2 style="font-weight: bold"><?php _e("Want do you want to backup") ?>?</h2>
            <br/>

            <div class="card bg-light mb-4">
                <div class="card-body">

                    <label class="form-check py-2">
                        <input class="form-check-input mt-3 me-3" type="radio" name="backup_by_type" checked="checked" value="content" />

                        <label class="form-label"><?php _e("Content backup") ?></label>
                        <span class="fs-5"><?php _e("Create backup of your sites without sensitive information") ?>
                            <br/>
                            <small class="text-muted">
                                <?php _e("This will create a zip with live-edit css, media, post categories & pages") ?>.
                            </small>
                        </span>
                    </label>
                </div>
            </div>

            <div class="card bg-light mb-4">
                <div class="card-body">
                    <label class="form-check py-2">
                        <input class="form-check-input mt-3 me-3" type="radio" name="backup_by_type" value="custom" />

                        <label class="form-label"><?php _e("Custom backup") ?></label>
                        <span class="fs-5"><?php _e("Create backup with custom selected tables, users, api_keys, media, modules, templates") ?>...
                            <br/>
                            <small class="text-muted">
                                <?php _e("You can select one by one sensitive information included in zip") ?>.
                            </small>
                        </span>
                    </label>
                </div>
            </div>

            <div class="card bg-light mb-4">
                <div class="card-body">
                    <label class="form-check py-2 active">

                        <input class="form-check-input mt-3 me-3" type="radio" name="backup_by_type" value="full"/>

                        <label class="form-label"><?php _e("Full backup") ?></label>
                        <span class="fs-5">
                            <?php _e("Create full backup of all database tables, system settings and options") ?>
                            <br/>
                            <small class="text-muted">
                                <?php _e("Include sensitive information like users, passwords, api keys, settings") ?>.
                            </small>
                        </span>
                    </label>
                </div>
            </div>


            <div class="card bg-light mb-4" style="width:100%">
                    <div class="card-body">
                        <label>
                            <?php _e("Backup filename") ?>
                        </label>
                    <?php
                    $backupFileNamePrefix = 'backup_'.site_hostname().'_'.time();
                    $backupFileNamePrefix = str_slug($backupFileNamePrefix);
                    $backupFileNamePrefix = str_replace('-', '_', $backupFileNamePrefix);
                    ?>
                    <input class="form-control" name="backup_filename" style="width:100%" value="<?php echo $backupFileNamePrefix; ?>" />
                </div>
            </div>

        </div>

        <div class="mw-backup-buttons">
            <a class="btn btn-link me-2" onClick="mw.backup.close_modal();"><?php _e("Close") ?></a>
            <button class="btn btn-primary" onclick="mw.backup.next()" type="button"><?php _e("Next") ?>
            </button>
        </div>

    </div>

    <div id="mw-backup-custom" style="display:none">

        <div class="js-toggle-backup-select-items">

            <div class="card-body mb-3 card-collapse">
                <div class="card-header no-border cursor-pointer" data-bs-toggle="collapse" data-bs-target="#include-database-tables">
                    <h6><i class="mdi mdi-view-grid-plus text-primary mr-2"></i> <strong><?php _e("Database Tables"); ?></strong></h6>
                </div>

                <div class="card-body py-0">
                    <div class="collapse pb-4" id="include-database-tables">
                        <div style="width:100%;  ">
                            <ul class="mw-ui-inline-list">
                                <li style="width: 100%;">
                                    <label class="form-check py-2">
                                        <input type="checkbox" checked="checked" class="js-backup-tables-select-all">
                                        <span></span><span><?php _e("Select all"); ?></span>
                                    </label>
                                </li>
                                <?php
                                $tablePrefix = mw()->database_manager->get_prefix();
                                $tablesList = mw()->database_manager->get_tables_list(true);
                                foreach ($tablesList as $tableName):
                                    $tableNameWithoutPrefix = str_replace_first($tablePrefix, '', $tableName);
                                    ?>
                                    <li style="width: 100%;">
                                        <label class="form-check py-2">
                                            <input type="checkbox" class="js-include-tables" checked="checked" name="include_tables[]" value="<?php echo $tableNameWithoutPrefix; ?>">
                                            <span></span><span><?php echo $tableName; ?></span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (config('microweber.allow_php_files_upload')): ?>
            <div class="card-body mb-3 card-collapse">
                <div class="card-header no-border cursor-pointer" data-bs-toggle="collapse" data-bs-target="#include-modules">
                    <h6><i class="mdi mdi-view-grid-plus text-primary mr-2"></i> <strong><?php _e("Include Modules"); ?></strong></h6>
                </div>

                <div class="card-body py-0">
                    <div class="collapse pb-4" id="include-modules">
                        <div style="width:100%;  ">
                            <ul class="mw-ui-inline-list">
                                <?php
                                $modules = get_modules('order_by=module asc');
                                foreach ($modules as $module):
                                    ?>
                                    <li style="width: 100%;">
                                        <label class="form-check py-2">
                                            <input type="checkbox" class="js-include-modules" name="include_modules[]" value="<?php echo $module['module']; ?>">
                                            <span></span><span><?php _e($module['name']); ?></span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body mb-3 card-collapse">
                <div class="card-header no-border cursor-pointer" data-bs-toggle="collapse" data-bs-target="#include-templates">
                    <h6><i class="mdi mdi-pencil-ruler text-primary mr-2"></i> <strong><?php _e("Include Templates"); ?></strong></h6>
                </div>

                <div class="card-body py-0">
                    <div class="collapse pb-4" id="include-templates">
                        <div style="width:100%;">
                            <ul class="mw-ui-inline-list">
                                <?php
                                $templates = site_templates();
                                foreach ($templates as $template):
                                    ?>
                                    <li style="width: 100%;">
                                        <label class="form-check py-2">
                                            <input type="checkbox" class="js-include-templates" name="include_templates[]" value="<?php echo $template['dir_name']; ?>">
                                            <span></span><span><?php echo $template['name']; ?></span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <label class="form-check py-2">
                <input type="checkbox" class="js-include-media" name="include_media" value="1">
                <span></span><span><?php _e("Include media files") ?></span>
            </label>

        </div>

        <div class="mw-backup-buttons">
            <button class="btn btn-primary" onclick="mw.backup.start()" type="button">
                <?php _e("Start backup process") ?>
            </button>
            <a class="btn btn-link" onClick="mw.backup.choice_tab();"><?php _e("Cancel") ?></a>
        </div>

    </div>

    <div id="mw-backup-process" style="display:none">
        <div class="js-backup-log"></div>
    </div>

</template>
