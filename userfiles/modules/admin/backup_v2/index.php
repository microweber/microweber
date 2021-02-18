<?php must_have_access(); ?>
<link rel="stylesheet" href="<?php echo modules_url() . '/admin/backup_v2/css/style.css' ?>" type="text/css"/>

<script type="text/javascript">
    var importContentFromFileText = '<?php _e("Importing content from file"); ?>';
    var userfilesUrl = '<?php echo userfiles_url() ?>';
    var moduleImagesUrl = '<?php echo modules_url() . '/admin/backup_v2/images/' ?>';
    mw.require("<?php print $config['url_to_module']; ?>js/upload-file.js");
    mw.require("<?php print $config['url_to_module']; ?>js/backup-import.js?v=10");
    mw.require("<?php print $config['url_to_module']; ?>js/backup-export.js?v=10");
    mw.lib.require('mw_icons_mind');

</script>
<?php $here = $config['url_to_module']; ?>
<div class="mw_edit_page_default" id="mw_edit_page_left">
    <template id="export-template">
        <div class="export-stepper">
            <div class="export-stepper-1">
                <div class="export-stepper-content">
                    <div class="mw-construct-itd mb-4">
                        <div class="mw-construct-itd-icon">
                            <span class="mw-micon-Data-Download"></span>
                        </div>
                        <div class="mw-construct-itd-content">
                            <h3><?php _e("Create full backup of your site"); ?></h3>
                            <p><?php _e("Use the button to export full backup of your website whit all data"); ?>.</p>
                        </div>
                    </div>

                    <div class="mw-ui-box2 export-stepper-1-select">
                        <div class="mw-construct-itd">
                            <div class="mw-construct-itd-content">
                                <h5><?php _e("Select file format for export"); ?></h5>
                                <p><?php _e("Choose the file format you want to export your backup"); ?>. </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <select class="js-export-format form-control" data-width="100%" name="export_format">
                                <option value="json">Json</option>
                                <option value="csv">CSV</option>
                                <option value="xml">XML</option>
                                <option value="xlsx">Excel</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-none js-toggle-backup-select-items">
                        <div class="card style-1 mb-3 card-collapse">
                            <div class="card-header no-border cursor-pointer" data-toggle="collapse" data-target="#include-modules">
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
                                                    <label class="mw-ui-check">
                                                        <input type="checkbox" class="js-export-modules" name="include_modules[]" value="<?php echo $module['module']; ?>">
                                                        <span></span><span><?php echo _e($module['name']); ?></span>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card style-1 mb-3 card-collapse">
                            <div class="card-header no-border cursor-pointer" data-toggle="collapse" data-target="#include-templates">
                                <h6><i class="mdi mdi-pencil-ruler text-primary mr-2"></i> <strong><?php _e("Include Templates"); ?></strong></h6>
                            </div>

                            <div class="card-body py-0">
                                <div class="collapse pb-4" id="include-templates">
                                    <div style="width:100%; >
                                        <ul class="mw-ui-inline-list">
                                            <?php
                                            $templates = site_templates();
                                            foreach ($templates as $template):
                                                ?>
                                                <li style="width: 100%;">
                                                    <label class="mw-ui-check">
                                                        <input type="checkbox" class="js-export-templates" name="include_templates[]" value="<?php echo $template['dir_name']; ?>">
                                                        <span></span><span><?php echo $template['name']; ?></span>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-actions step-1-actions d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-primary" onclick="mw.backup_export.export_fullbackup_start()"><?php _e("Create Full Backup"); ?></button>
                        <span class="btn btn-link js-toggle-backup-select-items" onclick="mw.$('.js-toggle-backup-select-items').toggleClass('d-none')" ><?php _e("Advanced settings"); ?></span>
                        <span class="btn btn-link d-none js-toggle-backup-select-items" data-mwstepper="next"><?php _e("Other settings"); ?></span>
                    </div>
                </div>
            </div>
            <div class="export-stepper-2">
                <div class="export-stepper-content">
                    <div class="step-header-actions step-header-actions-2">
                        <a class="btn btn-link d-flex-inline align-items-center px-0" data-mwstepper="prev"><span class="mdi mdi-arrow-left bg-primary text-white p-2 rounded-circle mr-2"></span><?php _e("back"); ?></a>
                    </div>

                    <div class="export-step-2-items-head">
                        <div class="mw-construct-itd">
                            <div class="mw-construct-itd-content">
                                <h5><?php _e("Select types of data you want to export"); ?></h5>
                            </div>
                        </div>
                        <label class="mw-ui-check mw-ui-check-lg">
                            <input type="radio" name="all" onchange="mw.backup_export.typesSelector.selectAll();">
                            <span></span><span><?php _e("Select all"); ?></span>
                        </label>

                        <label class="mw-ui-check mw-ui-check-lg">
                            <input type="radio" name="all" onchange="mw.backup_export.typesSelector.selectNone();">
                            <span></span><span><?php _e("Unselect all"); ?></span>
                        </label>
                    </div>

                    <div id="backup-select-options-to-export"></div>
                    <br />

                    <div class="mw-ui-box2 export-stepper-1-select">
                        <div class="mw-construct-itd">
                            <div class="mw-construct-itd-content">
                                <h5><?php _e("Select file format for export"); ?></h5>
                                <p><?php _e("Choose the file format you want to export your backup"); ?>. </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <select class="js-export-format form-control" name="export_format">
                                <option value="json">Json</option>
                                <option value="csv">CSV</option>
                                <option value="xml">XML</option>
                                <option value="xlsx">Excel</option>
                            </select>
                        </div>
                    </div>

                    <div class="step-actions step-actions-2 text-right mt-3">
                        <a href="javascript:;" class="btn btn-primary" data-mwstepper="next"><?php _e("Next"); ?></a>
                    </div>

                </div>
            </div>
            <div class="export-stepper-3">

                <div class="export-stepper-content">
                    <div class="step-header-actions step-header-actions-2">
                        <a class="btn btn-link d-flex-inline align-items-center px-0" data-mwstepper="prev"><span class="mdi mdi-arrow-left bg-primary text-white p-2 rounded-circle mr-2"></span><?php _e("back"); ?></a>
                    </div>

                    <div class="export-step-3-items-head">
                        <div class="mw-construct-itd">
                            <div class="mw-construct-itd-content">
                                <h5><?php _e("Select which pages or categories of your website to export"); ?></h5>
                                <p><?php _e("Select pages, categories, posts etc. you want to export"); ?>.</p>
                            </div>
                        </div>
                        <label class="mw-ui-check mw-ui-check-lg">
                            <input type="radio" name="all" onchange="mw.backup_export.select_all();">
                            <span></span><span><?php _e("Select all"); ?></span>
                        </label>

                        <label class="mw-ui-check mw-ui-check-lg">
                            <input type="radio" name="all" onchange="mw.backup_export.unselect_all();">
                            <span></span><span><?php _e("Unselect all"); ?></span>
                        </label>
                    </div>

                    <div class="mw-ui-box2">
                        <div id="quick-parent-selector-tree"></div>
                    </div>

                    <div class="step-actions step-actions-3">
                        <a class="mw-ui-btn mw-ui-btn-info" onclick="mw.backup_export.export_start()">Export selected data</a>
                    </div>

                </div>
            </div>
            <div class="export-stepper-4">
                <h3 class="export-step-4-action"><?php _e("Exporting your content"); ?>Exporting your content</h3>
                <div class="export-stepper-content">
                    <div class="js-export-log"></div>
                </div>
            </div>

        </div>
    </template>
    <div class="row mt-3">
        <div class="col-6 mb-4">
            <?php if (user_can_access('module.admin.backup_v2.index')): ?>
                <h5 class="font-weight-bold"><?php _e('Create new backup'); ?></h5>
                <small class="text-muted d-block mb-3"><?php _e('Create a backup and export your website content'); ?></small>
                <a href="javascript:;" onclick="mw.backup_export.choice('#export-template')" class="btn btn-success btn-rounded"><i class="mdi mdi-plus"></i> <?php _e("Create backup"); ?></a>
            <?php endif; ?>
        </div>

        <?php if (user_can_access('module.admin.backup_v2.create') || user_can_access('module.admin.backup_v2.edit')): ?>
            <div class="col-6 mb-4">
                <h5 class="font-weight-bold"><?php _e('Upload your backup'); ?></h5>
                <small class="text-muted d-block mb-3"><?php _e("Supported files formats"); ?>
                    <a href="<?php print $here; ?>samples/sample.csv" class="mw-ui-link">csv</a>,
                    <a href="<?php print $here; ?>samples/sample.json" class="mw-ui-link">json</a>,
                    <a href="<?php print $here; ?>samples/sample.xlsx" class="mw-ui-link">xls</a>,
                    <a href="<?php print $here; ?>samples/other_cms.zip" class="mw-ui-link"><?php _e('other files'); ?></a>.
                </small>
                <span id="mw_uploader" class="btn btn-primary btn-rounded"><i class="mdi mdi-cloud-upload-outline"></i>&nbsp; <?php _e("Upload file"); ?></span>

                <div id="mw_uploader_loading" class="progress mb-3" style="display:none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="mw_edit_page_right">
    <module type="admin/backup_v2/manage"/>
</div>