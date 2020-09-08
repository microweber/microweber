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

                    <div class="mw-construct-itd">
                        <div class="mw-construct-itd-icon">
                            <span class="mw-micon-Data-Download"></span>
                        </div>
                        <div class="mw-construct-itd-content">
                            <h3>Create full backup of your site</h3>
                            <p>Use the button to export full backup of your website whit all data.</p>
                        </div>
                    </div>

                    <div class="mw-ui-box2 export-stepper-1-select">
                        <div class="mw-construct-itd">
                            <div class="mw-construct-itd-content">
                                <h5>Select file format for export</h5>
                                <p>Choose the file format you want to export your backup. </p>
                            </div>
                        </div>
                        <div class="mw-field w100">
                            <select class="js-export-format" name="export_format">
                                <option value="json">Json</option>
                                <option value="csv">CSV</option>
                                <option value="xml">XML</option>
                                <option value="xlsx">Excel</option>
                            </select>
                        </div>
                    </div>


                    <div class="mw-accordion" data-options="openFirst: false">
                        <div class="mw-accordion-item">
                            <div class="mw-ui-box-header mw-accordion-title">
                                <div class="header-holder">
                                    <i class="mai-modules"></i> Include Modules
                                </div>
                            </div>
                            <div class="mw-accordion-content mw-ui-box mw-ui-box-content" style="width:100%;height: 200px;overflow-y: scroll;">
                                <ul class="mw-ui-inline-list">
                                    <?php
                                    $modules = get_modules('order_by=module asc');
                                    foreach ($modules as $module):
                                        ?>
                                        <li style="width: 100%;">
                                            <label class="mw-ui-check">
                                                <input type="checkbox" class="js-export-modules" name="include_modules[]" value="<?php echo $module['module']; ?>">
                                                <span></span><span><?php echo $module['name']; ?></span>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="mw-accordion-item">
                            <div class="mw-ui-box-header mw-accordion-title">
                                <div class="header-holder">
                                    <i class="mw-icon-template"></i> Include Template
                                </div>
                            </div>
                            <div class="mw-accordion-content mw-ui-box mw-ui-box-content" style="width:100%;height: 200px;overflow-y: scroll;">
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

                    <div class="step-actions step-1-actions">
                        <span class="mw-ui-btn mw-ui-btn-info pull-left" onclick="mw.backup_export.export_fullbackup_start()">Create Full Backup</span>
                        <span class="mw-ui-link pull-right" data-mwstepper="next">Continue with advanced backup settings</span>
                    </div>

                </div>
            </div>
            <div class="export-stepper-2">
                <div class="export-stepper-content">

                    <div class="step-header-actions step-header-actions-2">
                        <a class="mw-ui-link mw-btn-prepend" data-mwstepper="prev"><span class="mw-icon-arrow-left-c mw-icon-round mw-icon-info"></span>back</a>
                    </div>

                    <div class="export-step-2-items-head">
                        <div class="mw-construct-itd">
                            <div class="mw-construct-itd-content">
                                <h5>Select types of data you want to export.</h5>
                            </div>
                        </div>
                        <label class="mw-ui-check mw-ui-check-lg">
                            <input type="radio" name="all" onchange="mw.backup_export.typesSelector.selectAll();">
                            <span></span><span>Select all</span>
                        </label>

                        <label class="mw-ui-check mw-ui-check-lg">
                            <input type="radio" name="all" onchange="mw.backup_export.typesSelector.selectNone();">
                            <span></span><span>Unselect all</span>
                        </label>
                    </div>

                    <div id="backup-select-options-to-export"></div>

                    <div class="mw-ui-box2 export-stepper-1-select">
                        <div class="mw-construct-itd">
                            <div class="mw-construct-itd-content">
                                <h5>Select file format for export</h5>
                                <p>Choose the file format you want to export your backup. </p>
                            </div>
                        </div>
                        <div class="mw-field w100">
                            <select class="js-export-format" name="export_format">
                                <option value="json">Json</option>
                                <option value="csv">CSV</option>
                                <option value="xml">XML</option>
                                <option value="xlsx">Excel</option>
                            </select>
                        </div>
                    </div>


                    <div class="step-actions step-actions-2">
                        <a class="mw-ui-btn mw-ui-btn-info" data-mwstepper="next">Next</a>
                    </div>

                </div>
            </div>
            <div class="export-stepper-3">

                <div class="export-stepper-content">
                    <div class="step-header-actions step-header-actions-2">
                        <a class="mw-ui-link mw-btn-prepend" data-mwstepper="prev"><span class="mw-icon-arrow-left-c mw-icon-round mw-icon-info"></span>back</a>
                    </div>

                    <div class="export-step-3-items-head">
                        <div class="mw-construct-itd">
                            <div class="mw-construct-itd-content">
                                <h5>Select which pages or categories of your website to export</h5>
                                <p>Select pages, categories, posts etc. you want to export.</p>
                            </div>
                        </div>
                        <label class="mw-ui-check mw-ui-check-lg">
                            <input type="radio" name="all" onchange="mw.backup_export.select_all();">
                            <span></span><span>Select all</span>
                        </label>

                        <label class="mw-ui-check mw-ui-check-lg">
                            <input type="radio" name="all" onchange="mw.backup_export.unselect_all();">
                            <span></span><span>Unselect all</span>
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
                <h3 class="export-step-4-action">Exporting your content</h3>
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
                <small class="text-muted d-block mb-3">Create a backup and export your website content</small>
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